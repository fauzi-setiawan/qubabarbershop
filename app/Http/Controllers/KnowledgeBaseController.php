<?php

namespace App\Http\Controllers;

use App\Services\DocumentRagService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class KnowledgeBaseController extends Controller
{
    protected DocumentRagService $ragService;

    public function __construct()
    {
        $this->ragService = new DocumentRagService();
    }

    /**
     * Daftar semua dokumen knowledge yang tersedia.
     */
    public function index()
    {
        $documents = $this->ragService->listDocuments();

        return response()->json([
            'status' => 'success',
            'data'   => $documents,
        ]);
    }

    /**
     * Baca konten dokumen knowledge berdasarkan nama file.
     */
    public function show(Request $request)
    {
        $filename = $request->query('file');

        if (empty($filename)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Parameter file wajib diisi.',
            ], 400);
        }

        $content = $this->ragService->readDocument($filename);

        if ($content === null) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Dokumen tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data'   => [
                'filename' => $filename,
                'content'  => $content,
            ],
        ]);
    }

    /**
     * Upload dokumen knowledge baru (.md atau .docx).
     */
    public function upload(Request $request)
    {
        $request->validate([
            'document' => 'required|file|mimes:md,docx,txt|max:10240',
        ]);

        $file = $request->file('document');
        $filename = $file->getClientOriginalName();
        $destinationPath = resource_path('document_urs');

        // Pastikan direktori ada
        if (!is_dir($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $file->move($destinationPath, $filename);

        // Hapus cache RAG agar dokumen baru langsung terindeks
        Cache::forget('rag_document_chunks');

        return response()->json([
            'status'  => 'success',
            'message' => "Dokumen '{$filename}' berhasil diunggah dan siap digunakan sebagai knowledge base RAG.",
        ]);
    }

    /**
     * Hapus dokumen knowledge berdasarkan nama file.
     */
    public function destroy(Request $request)
    {
        $filename = $request->query('file');

        if (empty($filename)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Parameter file wajib diisi.',
            ], 400);
        }

        $filePath = resource_path('document_urs/' . basename($filename));

        if (!file_exists($filePath)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Dokumen tidak ditemukan.',
            ], 404);
        }

        unlink($filePath);

        // Hapus cache RAG
        Cache::forget('rag_document_chunks');

        return response()->json([
            'status'  => 'success',
            'message' => "Dokumen '{$filename}' berhasil dihapus.",
        ]);
    }

    /**
     * Bersihkan cache RAG agar perubahan dokumen langsung terbaca.
     */
    public function clearCache()
    {
        Cache::forget('rag_document_chunks');

        return response()->json([
            'status'  => 'success',
            'message' => 'Cache RAG berhasil dibersihkan. Dokumen akan diindeks ulang pada permintaan berikutnya.',
        ]);
    }
}
