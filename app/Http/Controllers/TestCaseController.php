<?php

namespace App\Http\Controllers;

use App\Models\TestCase;
use App\Services\DocumentRagService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class TestCaseController extends Controller
{
    public function index(Request $request) {
        $query = TestCase::with('module.project');
        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%");
        }
        if ($request->priority && $request->priority !== 'all') {
            $query->where('priority', $request->priority);
        }
        return response()->json(['status' => 'success', 'data' => $query->latest()->get()]);
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'module_id'       => 'required|exists:modules,id',
            'name'            => 'required|string',
            'steps'           => 'required|string',
            'expected_result' => 'required|string',
            'priority'        => 'required|in:High,Medium,Low',
            'category'        => 'nullable|in:Positive Test,Negative Test,Boundary Test,Validation Test,UI/UX Test',
            'source'          => 'nullable|in:manual,ai',
        ]);
        $validated['created_by'] = Auth::id();
        $validated['source']     = $validated['source'] ?? 'manual';
        $testCase = TestCase::create($validated);
        return response()->json(['status' => 'success', 'data' => $testCase], 201);
    }

    public function update(Request $request, $id)
    {
        $testCase = TestCase::findOrFail($id);
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'steps'           => 'required|string',
            'expected_result' => 'required|string',
            'priority'        => 'required|in:High,Medium,Low',
            'category'        => 'nullable|in:Positive Test,Negative Test,Boundary Test,Validation Test,UI/UX Test',
        ]);

        $testCase->update($validated);
        return response()->json(['status' => 'success', 'data' => $testCase]);
    }

    public function destroy($id)
    {
        $testCase = TestCase::findOrFail($id);
        $testCase->delete();
        return response()->json(['status' => 'success', 'message' => 'Test case berhasil dihapus']);
    }

    // FITUR AI GENERATE dengan RAG (UC-007)
    public function generateWithAI(Request $request) {
        $request->validate([
            'description' => 'required|min:20',
            'test_type'   => 'required',
            'module_name' => 'nullable|string',
            'module_id'   => 'nullable|integer|exists:modules,id',
        ]);

        $apiKey     = config('services.gemini.key');
        $modulePart = $request->module_name ? "pada modul '{$request->module_name}'" : '';

        $categoryMap = [
            'Positive'   => 'Positive Test',
            'Negative'   => 'Negative Test',
            'Boundary'   => 'Boundary Test',
            'UI/UX'      => 'UI/UX Test',
            'Validation' => 'Validation Test',
        ];
        $categoryValue = $categoryMap[$request->test_type] ?? 'Positive Test';

        // Tentukan filter dokumen RAG berdasarkan proyek
        $documentFilter = null;
        if ($request->module_id) {
            $module = \App\Models\Module::with('project')->find($request->module_id);
            if ($module && $module->project) {
                $projectName = $module->project->name;
                $lowerProjectName = strtolower($projectName);
                if (str_contains($lowerProjectName, 'saucedemo') || str_contains($lowerProjectName, 'swag labs')) {
                    $documentFilter = 'knowledge-base-saucedemo.md';
                } elseif (str_contains($lowerProjectName, 'alpha') || str_contains($lowerProjectName, 'bni')) {
                    $documentFilter = 'FSD_FIX.docx';
                }
            }
        }

        // Fallback berdasarkan nama modul jika module_id tidak disertakan
        if (!$documentFilter && $request->module_name) {
            $lowerModuleName = strtolower($request->module_name);
            if (str_contains($lowerModuleName, 'saucedemo') || str_contains($lowerModuleName, 'swag labs')) {
                $documentFilter = 'knowledge-base-saucedemo.md';
            }
        }

        // ── RAG: Ambil konteks relevan dari dokumen URS/FSD ──────────────────
        $ragContext     = '';
        $contextSection = '';

        if ($documentFilter) {
            $ragService = new DocumentRagService();
            $ragQuery   = "{$request->module_name} {$request->description}";
            $ragContext = $ragService->getRelevantContext($ragQuery, $documentFilter);

            if (!empty($ragContext)) {
                $contextSection = <<<CONTEXT

## Konteks Requirement dari Dokumen URS/FSD

Gunakan informasi berikut sebagai ACUAN UTAMA dalam membuat test case. Pastikan test case mencerminkan requirement yang tertulis:

{$ragContext}

---
CONTEXT;
            }
        }
        // ────────────────────────────────────────────────────────────────────

        $prompt = <<<PROMPT
Anda adalah Senior QA Engineer yang berpengalaman dalam pengujian perangkat lunak.
{$contextSection}
## Tugas

Buatkan tepat 5 test case bertipe **{$request->test_type}** {$modulePart} berdasarkan deskripsi pengguna berikut:

"{$request->description}"

## Format Output

Output HARUS berupa JSON array dengan tepat 5 objek. Setiap objek WAJIB memiliki properti:
- "name": string — nama test case yang jelas dan deskriptif dalam Bahasa Indonesia formal
- "category": string — SELALU gunakan nilai "{$categoryValue}"
- "steps": string — langkah pengujian yang detail, berurutan, dan dapat dieksekusi (pisahkan tiap langkah dengan newline)
- "expected_result": string — hasil yang diharapkan secara spesifik dan terukur
- "priority": string — salah satu dari: "High", "Medium", atau "Low"

## Aturan
- Prioritaskan requirement dari dokumen URS/FSD jika tersedia.
- Pastikan setiap test case unik dan tidak duplikat.
- Gunakan Bahasa Indonesia formal.
- Ikuti praktik Software Quality Assurance profesional.
PROMPT;

        $response = Http::timeout(90)->post(
            "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}",
            [
                'contents'         => [['parts' => [['text' => $prompt]]]],
                'generationConfig' => ['responseMimeType' => 'application/json'],
            ]
        );

        if ($response->successful()) {
            $raw  = $response->json()['candidates'][0]['content']['parts'][0]['text'] ?? '[]';
            $data = json_decode($raw, true);
            if (!is_array($data)) $data = [];

            // Sertakan flag apakah RAG aktif dalam respons (untuk UI feedback)
            return response()->json([
                'status'      => 'success',
                'data'        => $data,
                'rag_enabled' => !empty($ragContext),
                'rag_source'  => !empty($ragContext) ? ($documentFilter ?: 'document_urs') : null,
            ]);
        }

        return response()->json(['status' => 'error', 'message' => 'AI gagal merespon. Coba lagi.'], 500);
    }
}
