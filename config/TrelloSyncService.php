<?php

namespace App\Services;

use App\Models\BugReport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class TrelloService
{

    public function createBugCard($request)
{
   
    try {

        $config = config('services.trello');
        

        $response = Http::post(
            'https://api.trello.com/1/cards',
            [
                'key' => $config['key'],
                'token' => $config['token'],
                'idList' => $config['lists']['bug'],
                'name' => "[{$request->severity}] {$request->title}",
                'desc' => $request->description
            ]
        );
        

        if (!$response->successful()) {

            return response()->json([
                'status' => 'error',
                'message' => $response->body()
            ], 500);
        }

        $card = $response->json();
        
        $bug = BugReport::create([
            'test_execution_id' => $request->test_execution_id,
            'reported_by' => auth()->id(),
            'trello_card_id' => $card['id'],
            'trello_card_url' => $card['shortUrl'],
            'title' => $request->title,
            'severity' => $request->severity,
            'status' => 'Open',
            'current_trello_list' => $config['lists']['bug']
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $bug
        ]);

    } catch (\Exception $e) {

        \Log::error($e);

        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
}
    public function syncBug(BugReport $bug)
    {
        $config = config('services.trello');

        $response = Http::get(
            "https://api.trello.com/1/cards/{$bug->trello_card_id}",
            [
                'key' => $config['key'],
                'token' => $config['token']
            ]
        );

        if (!$response->successful()) {
            return false;
        }

        $card = $response->json();

        $listId = $card['idList'];

        $mapping = [
            $config['lists']['bug']   => 'Open',
            $config['lists']['todo']  => 'Assigned',
            $config['lists']['doing'] => 'In Progress',
            $config['lists']['done']  => 'Resolved'
        ];

        $bug->update([
            'current_trello_list' => $listId,
            'status' => $mapping[$listId] ?? 'Unknown'
        ]);

        return true;
    }

    public function syncAllBugs()
    {
        $bugs = BugReport::all();

        foreach ($bugs as $bug) {
            $this->syncBug($bug);
        }

        return response()->json([
            'success' => true
        ]);
    }
}