<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TestCase extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id',
        'name',
        'description',
        'steps',
        'expected_result',
        'priority',
        'category',
        'source',
        'created_by',
    ];

     public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function executions()
    {
        return $this->hasMany(TestExecution::class);
    }

     public function latestExecution()
    {
        return $this->hasOne(TestExecution::class)->latestOfMany();
    }
}
