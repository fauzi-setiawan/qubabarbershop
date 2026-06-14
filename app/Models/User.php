<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

     protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

     public function projects()
    {
        return $this->hasMany(Project::class, 'created_by');
    }

    public function testCases()
    {
        return $this->hasMany(TestCase::class, 'created_by');
    }

    public function testExecutions()
    {
        return $this->hasMany(TestExecution::class, 'executed_by');
    }

     public function bugReports()
    {
        return $this->hasMany(BugReport::class, 'reported_by');
    }
}

