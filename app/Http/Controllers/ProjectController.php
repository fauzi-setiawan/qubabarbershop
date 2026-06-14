<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    // Tampilkan daftar proyek & pencarian dengan filter prioritas (Use Case 002)
    public function index(Request $request)
    {
        $query = Project::with('modules');

        if ($request->has('priority') && $request->priority !== 'all') {
            $query->where('priority', $request->priority);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $projects = $query->latest()->get();

        return response()->json([
            'status' => 'success',
            'data' => $projects
        ]);
    }

    // Menyimpan project baru (Use Case 002 - Step 5)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:projects',
            'description' => 'required|string',
            'priority' => 'required|in:High,Medium,Low',
        ]);

        $project = Project::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'priority' => $validated['priority'],
            'created_by' => Auth::id()
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Project berhasil disimpan',
            'data' => $project
        ], 201);
    }

    // Mengubah data project (Use Case 002 - Step 10)
    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:projects,name,' . $project->id,
            'description' => 'required|string',
            'priority' => 'required|in:High,Medium,Low',
        ]);

        $project->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Project berhasil diubah',
            'data' => $project
        ]);
    }

    // Menghapus project (Use Case 002 - Step 15)
    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Project berhasil dihapus'
        ]);
    }

    // Mengambil data visualisasi statistik modul untuk Project Manager (Use Case 008 - Step 4)
    public function getReportData($id)
    {
        $project = Project::with(['modules.testCases.executions'])->findOrFail($id);
        
        $labels = [];
        $passData = [];
        $failData = [];
        $blockedData = [];

        foreach ($project->modules as $module) {
            $labels[] = $module->name;
            
            $passCount = 0;
            $failCount = 0;
            $blockedCount = 0;

            foreach ($module->testCases as $tc) {
                // Ambil status eksekusi manual QA terakhir
                $latestExec = $tc->executions()->latest()->first();
                if ($latestExec) {
                    if ($latestExec->status === 'Pass') $passCount++;
                    elseif ($latestExec->status === 'Fail') $failCount++;
                    elseif ($latestExec->status === 'Blocked') $blockedCount++;
                }
            }

            $passData[] = $passCount;
            $failData[] = $failCount;
            $blockedData[] = $blockedCount;
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'projectName' => $project->name,
                'totalTests' => $project->modules->flatMap->testCases->count(),
                'labels' => $labels,
                'datasets' => [
                    ['label' => 'Pass', 'data' => $passData, 'backgroundColor' => '#10B981'],
                    ['label' => 'Fail', 'data' => $failData, 'backgroundColor' => '#EF4444'],
                    ['label' => 'Blocked', 'data' => $blockedData, 'backgroundColor' => '#F59E0B'],
                ]
            ]
        ]);
    }
}