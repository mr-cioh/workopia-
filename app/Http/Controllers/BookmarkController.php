<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BookmarkController extends Controller
{
    // @desc get all users bookmarks 
    // @route GET /bookmarks
    public function index(): View 
    {
        $user = Auth::user();
        $bookmarks = $user->bookmarkedJobs()->orderBy('job_user_bookmarks.created_at', 'desc')->paginate(9);

        return view('jobs.bookmarked')->with('bookmarks', $bookmarks);
    }

    // @desc create new bookmarked job 
    // @route POST /bookmarks/{job}
    public function store(Job $job): RedirectResponse
    {
        $user = Auth::user();
        
        // check if the job is already bookmarked 
        if($user->bookmarkedJobs()->where('job_id', $job->id)->exists()) {
            return back()->with('success', 'Job is already bookmarked');
        }

        // create new bookmark
        $user->bookmarkedJobs()->attach($job->id);

        return back()->with('success', 'Job bookmarked successfully');
    }
   
    // @desc remove a bookmarked job 
    // @route DELETE /bookmarks/{job}
    public function destroy(Job $job): RedirectResponse
    {
        $user = Auth::user();
    
        // Check if the job is bookmarked before trying to remove it
        if (!$user->bookmarkedJobs()->where('job_id', $job->id)->exists()) {
            return back()->with('error', 'Job is not bookmarked.');
        }
    
        // Remove the bookmark
        $user->bookmarkedJobs()->detach($job->id);
    
        return back()->with('success', 'Job removed from bookmarks successfully.');
    }
}
