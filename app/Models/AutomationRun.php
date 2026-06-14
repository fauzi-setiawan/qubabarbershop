<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AutomationRun extends Model
{
     use HasFactory;

    protected $fillable = [
        'project_id',
        'run_by',
        'repo_url',
        'branch',
        'environment',
        'total_pass',
        'total_fail',
        'total_error',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

     public function runner()
    {
        return $this->belongsTo(User::class, 'run_by');
    }
}
