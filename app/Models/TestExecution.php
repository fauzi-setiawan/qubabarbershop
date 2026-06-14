<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TestExecution extends Model
{
     use HasFactory;

    protected $fillable = [
        'test_case_id',
        'executed_by',
        'status',
        'notes',
    ];

    
    public function testCase()
    {
        return $this->belongsTo(TestCase::class);
    }

     public function tester()
    {
        return $this->belongsTo(User::class, 'executed_by');
    }

     public function bugReport()
    {
        return $this->hasOne(BugReport::class);
    }
}
