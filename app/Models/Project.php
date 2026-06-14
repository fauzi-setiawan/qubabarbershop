<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'priority',
        'created_by',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

     public function modules()
    {
        return $this->hasMany(Module::class);
    }

    public function automationRuns()
    {
        return $this->hasMany(AutomationRun::class);
    }
}
