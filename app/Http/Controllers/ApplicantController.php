<?php

namespace App\Http\Controllers;

use App\Mail\JobApplied;
use App\Models\Applicant;
use App\Models\Job;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ApplicantController extends Controller
{
    // @desc    store new job application
    // route    POST /jobs/{job}/apply

    public function store(Request $request, Job $job): RedirectResponse
    {   
        //check if the user has already applied 
        $existingApplication = Applicant::where('job_id', $job->id)->where('user_id', Auth()->id())->exists();

        if($existingApplication) 
        {
            return redirect()->back()->with('error', 'you have already applied to this job');
        }

        //validate incoming data
        $validatedData = $request->validate([
            'full_name' => 'required|string',
            'contact_phone' => 'string',
            'contact_email' => 'required|string|email',
            'message' => 'string',
            'location' => 'string',
            'resume' => 'required|file|mimes:pdf|max:2048',
        ]);

        // Handle resume upload
        if ($request->hasFile('resume')) {
            $path = $request->file('resume')->store('resume', 'public');
            $validatedData['resume_path'] = $path;
        }

        $application = new Applicant($validatedData);
        $application->job_id = $job->id;
        $application->user_id = Auth::user()->id;
        $application->save();

        // Send email to owner
        // Mail::to($job->user->email)->send(new JobApplied($application, $job));

        return redirect()->back()->with('success', 'Your application has been submitted successfully');
    }

    // @desc   Delete a job application
    // @route  DELETE /applicants/{applicant}
    public function destroy($id): RedirectResponse
    {
        $applicant = Applicant::findOrFail($id);
        $applicant->delete();
        return redirect()->route('dashboard')->with('success', 'Applicant deleted successfully.');
    }
}
