<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Project;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    /**
     * Menampilkan daftar modul berdasarkan project ID.
     */
    public function index($projectId)
    {
        $project = Project::findOrFail($projectId);
        $modules = $project->modules()->get();

        return response()->json([
            'status' => 'success',
            'data' => $modules
        ]);
    }

    /**
     * Menyimpan modul baru untuk project tertentu.
     */
    public function store(Request $request, $projectId)
    {
        $project = Project::findOrFail($projectId);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $module = $project->modules()->create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Modul berhasil ditambahkan',
            'data' => $module
        ], 201);
    }

    /**
     * Mengubah data modul.
     */
    public function update(Request $request, $id)
    {
        $module = Module::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $module->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Modul berhasil diubah',
            'data' => $module
        ]);
    }

    /**
     * Menghapus modul.
     */
    public function destroy($id)
    {
        $module = Module::findOrFail($id);
        $module->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Modul berhasil dihapus'
        ]);
    }

    /**
     * Menampilkan test cases untuk modul tertentu.
     */
    public function testCases($moduleId)
    {
        $module = Module::with('testCases')->findOrFail($moduleId);

        return response()->json([
            'status' => 'success',
            'data' => $module->testCases
        ]);
    }
}
