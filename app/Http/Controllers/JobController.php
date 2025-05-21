<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class JobController extends Controller
{   
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     * @desc Show all job listings
     * @route GET /jobs
     */
    public function index(): View
    {   
        
        $jobs = Job::paginate(6);
        
        return view('jobs.index')->with('jobs', $jobs);
    }

    /**
     * Show the form for creating a new resource.
     * @desc Show create job form
     * @route GET /jobs/create
     */
    public function create(): View
    {   
        return view('jobs.create');
    }

    /**
     * Store a newly created resource in storage.
     * @desc save job to database
     * @route POST /jobs
     */
    public function store(Request $request): RedirectResponse
    {
        // $title = $request->input('title');
        // $description = $request->input('description');

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'salary' => 'required|integer',
            'tags' => 'nullable|string',
            'job_type' => 'required|string',
            'remote' => 'required|boolean',
            'requirements' => 'nullable|string',
            'benefits' => 'nullable|string',
            'address' => 'nullable|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'zipcode' => 'required|string',
            'contact_email' => 'required|email',
            'contact_phone' => 'nullable|string',
            'company_name' => 'required|string',
            'company_description' => 'nullable|string',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'company_website' => 'nullable|url',
        ]);
        //hardcode user ID
        $validatedData['user_id'] = Auth::id();

        //check for image
        if($request->hasFile('company_logo')) {
            //store the file and get path
            $path = $request->file('company_logo')->store('logos', 'public');

            //add path to validated data 
            $validatedData['company_logo'] = $path;
        }

        //submit to database
        Job::create($validatedData);

        return redirect()->route('jobs.index')->with('success', 'Job listing created successfully!' );
    }

    /**
     * Display the specified resource.
     * @desc Show a single job listing
     * @route GET /jobs/{$id}
     */
    public function show(Job $job): View
    {
        return view('jobs.show')->with('job', $job);
    }

    /**
     * Show the form for editing the specified resource.
     * @desc Show edit job form
     * @route GET /jobs/{$id}/edit
     */
    public function edit(Job $job): View    
    {   
        // Check if the user is authorized
        $this->authorize('update', $job);
        
        return view('jobs.edit')->with('job', $job);
    }

    /**
     * Update the specified resource in storage.
     * @desc update a job listing
     * @route PUT /jobs/{$id}
     */
    public function update(Request $request,  Job $job): RedirectResponse
    {   
        // Check if the user is authorized
        $this->authorize('update', $job);

        // Validate the incoming request data
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'salary' => 'required|integer',
            'tags' => 'nullable|string',
            'job_type' => 'required|string',
            'remote' => 'required|boolean',
            'requirements' => 'nullable|string',
            'benefits' => 'nullable|string',
            'address' => 'nullable|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'zipcode' => 'required|string',
            'contact_email' => 'required|email',
            'contact_phone' => 'nullable|string',
            'company_name' => 'required|string|max:255',
            'company_description' => 'nullable|string',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'company_website' => 'nullable|url',
        ]);
    
    
        // Check if a file was uploaded
        if ($request->hasFile('company_logo')) {
            // Delete the old company logo from storage
            if ($job->company_logo) {
                Storage::delete('public/logos/' . basename($job->company_logo));
            }
            // Store the file and get the path
            $path = $request->file('company_logo')->store('logos', 'public');
    
            // Add the path to the validated data array
            $validatedData['company_logo'] = $path;
        }
    
        // Update with the validated data
        $job->update($validatedData);
    
        return redirect()->route('jobs.index')->with('success', 'Job listing updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     * @desc Delete a job listings
     * @route DELETE /jobs/{$id}
     */
    public function destroy(Job $job): RedirectResponse
    {   
        // Check if the user is authorized
        $this->authorize('delete', $job);

        //if logo, then delete it 
        if($job->company_logo) {
            Storage::delete('public/logo'. $job->company_logo);
        }

        $job->delete();

        // Check if the request came from the dashboard page
        if (request()->query('from') === 'dashboard') {
            return redirect()->route('dashboard')->with('success', 'Job listing deleted successfully!');
        }

        return redirect()->route('jobs.index')->with('success', 'Job listing deleted successfully');
    }

    //@desc Search job listings
    //@route /GET /jobs/search
    public function search(Request $request): View
    {
        $keywords = strtolower(preg_replace('/\s+/', ' ', trim($request->input('keywords', ''))));
        $location = strtolower(preg_replace('/\s+/', ' ', trim($request->input('location', ''))));

        $query = Job::query();

        if ($keywords) {
            $query->where(function ($q) use ($keywords) {
                $q->whereRaw('LOWER(title) like ?', ['%' . $keywords . '%'])
                    ->orWhereRaw('LOWER(description) like ?', ['%' . $keywords . '%'])
                    ->orWhereRaw('LOWER(tags) like ?', ['%' . $keywords . '%']);
                    
                    if ($keywords === 'remote') {
                        $q->orWhere('remote', true);
                    }
                    
                    if ($keywords === 'on site') {
                        $q->orWhere('remote', false);
                    }
                });
        }
    
        if ($location) {
            $query->where(function ($q) use ($location) {
                $q->whereRaw('LOWER(address) like ?', ['%' . $location . '%'])
                    ->orWhereRaw('LOWER(city) like ?', ['%' . $location . '%'])
                    ->orWhereRaw('LOWER(state) like ?', ['%' . $location . '%'])
                    ->orWhereRaw('LOWER(zipcode) like ?', ['%' . $location . '%']);
            });
        }

        $jobs = $query->paginate(12);

        return view('jobs.index')->with('jobs', $jobs);
    }
}
