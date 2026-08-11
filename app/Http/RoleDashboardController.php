<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class RoleDashboardController extends Controller
{
    public function index(): RedirectResponse
    {
        $user = auth()->user();

        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'staff' => redirect()->route('staff.dashboard'),
            default => redirect()->route('dashboard'),
        };
    }
}