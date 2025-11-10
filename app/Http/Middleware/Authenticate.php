<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            // Kalau akses route admin → redirect ke login admin
            if ($request->is('admin/*')) {
                return route('admin.login.form');
            }

            // Default → redirect ke login user
            return route('user.login.form');
        }
    }
}
