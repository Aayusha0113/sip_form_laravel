<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApplicationController extends Controller
{
    public function viewApplication($id)
    {
        $application = Application::findOrFail($id);

    // Log admin view
    if (Auth::check() && Auth::user()->role == 'admin') {
        UserActivity::create([
            'user_id' => Auth::id(),
            'activity' => "Viewed application ID $id - " . $application->customer_name,
            'activity_time' => now()
        ]);
    }
        // --- END LOGGING ---

        return view('dashboard.view_client', compact('application'));
    }
}
