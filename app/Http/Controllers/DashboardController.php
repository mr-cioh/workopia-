<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    // @desc show all users job listings
    // @route GET /dashboard
    public function index(): View
    {
        // get logged in user
        $user = Auth::user();

        // get user listings
        $jobs = Job::where('user_id', $user->id)->with('applicants')->get();

        return view('dashboard.index', compact('user', 'jobs'));
    }


}
