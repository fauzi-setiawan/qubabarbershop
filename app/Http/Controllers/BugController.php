<?php

namespace App\Http\Controllers;

use App\Models\BugReport;
use Illuminate\Http\Request;
use App\Services\TrelloService;

class BugController extends Controller
{
    protected $trello;

    public function __construct(TrelloService $trello)
    {
        $this->trello = $trello;
    }

     public function index()
    {
        return response()->json([
            'success' => true,
            'data' => BugReport::with([
                'reporter',
                'testExecution'
            ])->latest()->get()
        ]);
    }

    public function report(Request $request)
    {
        $request->validate([
            'test_execution_id' => 'required',
            'title' => 'required',
            'severity' => 'required'
        ]);

        return $this->trello->createBugCard($request);
    }

    public function sync()
    {
        return $this->trello->syncAllBugs();
    }
}