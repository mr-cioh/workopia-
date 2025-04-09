<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{   
    // @desc Show home index form 
    // @route GET /register
    public function index(): View
    {
        $jobs = Job::latest()->limit(6)->get();
        return view('pages.index')->with('jobs', $jobs);
    }
}
