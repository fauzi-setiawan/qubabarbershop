<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BugReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_execution_id',
        'reported_by',
        'trello_card_id',
        'trello_card_url',
        'title',
        'description',
        'severity',
        'status',
        'current_trello_list',
    ];


    public function testExecution()
    {
        return $this->belongsTo(TestExecution::class);
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }
}
