<?php

namespace App\Http\Controllers;

use App\Models\TestExecution;
use App\Models\BugReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class TestExecutionController extends Controller
{
    public function index() {
        $executions = TestExecution::with([
            'testCase.module',
            'tester',
            'bugReport'
        ])->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $executions
        ]);
    }


    public function store(Request $request) {
        $validated = $request->validate([
            'test_case_id' => 'required|exists:test_cases,id',
            'status' => 'required|in:Pass,Fail,Blocked,Not Executed',
            'notes' => 'nullable'
        ]);
        $validated['executed_by'] = Auth::id();
        $execution = TestExecution::create($validated);
        return response()->json(['status' => 'success', 'data' => $execution]);
    }

    // INTEGRASI TRELLO (UC-005)
    public function reportToTrello(Request $request) {

        try {

        $config = config('services.trello');

        return response()->json([
            'config' => $config
        ]);

    } catch (\Exception $e) {

        return response()->json([
            'error' => $e->getMessage()
        ], 500);

    }

        $request->validate(['test_execution_id' => 'required', 'title' => 'required', 'severity' => 'required']);
        
        $config = config('services.trello');
        dd([
            'key' => $config['key'],
            'token' => $config['token'],
            'list_id' => $config['list_id'],
        ]);
       $response = Http::post('https://api.trello.com/1/cards', [
            'key' => $config['key'],
            'token' => $config['token'],
            'idList' => $config['list_id'],
            'name' => "[{$request->severity}] " . $request->title,
            'desc' => "Dilaporkan otomatis dari QA Dashboard.\n\nDetail: " . $request->description,
        ]);

        if ($response->successful()) {
            $trello = $response->json();
            $bug = BugReport::create([
                'test_execution_id' => $request->test_execution_id,
                'reported_by' => Auth::id(),
                'trello_card_id' => $trello['id'],
                'trello_card_url' => $trello['shortUrl'],
                'title' => $request->title,
                'severity' => $request->severity,
            ]);
            return response()->json(['status' => 'success', 'data' => $bug]);
        }
        return response()->json(['status' => 'error', 'message' => 'Gagal kirim ke Trello'], 500);
    }
}