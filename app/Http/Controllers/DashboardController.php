<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        return view('user.dashboard');
    }

    public function layanan()
    {
        return view('layouts.user.layanan');
    }

    public function profile()
    {
        $user = Auth::user();
        return view('layouts.user.profile', compact('user'));
    }

    public function history()
    {
        return view('layouts.user.history');
    }

    public function booking()
    {
        return view('layouts.user.booking');
    }
}
