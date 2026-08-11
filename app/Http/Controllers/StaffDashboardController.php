<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class StaffDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('staff.dashboard', [
            'user' => $user,
        ]);
    }
}