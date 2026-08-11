<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class RoleDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),

            'staff' => redirect()->route('staff.dashboard'),

            default => redirect()->route('dashboard'),
        };
    }
}

