<?php

namespace Database\Seeders;

use App\Models\Job;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class BookmarkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /// Get the test user
        $testUser = User::where('email', 'test@test.com')->firstOrFail();

        // Get all job IDs 
        $jobIds = Job::pluck('id')->toArray();

        // Randomly select job IDs to bookmark indexes
        $randomJobIds = array_rand($jobIds, 3); // Change 3 to however many you want to bookmark

        // Attach the selected jobs as bookmarks for the test user
        foreach ($randomJobIds as $jobId) {
            $testUser->bookmarkedJobs()->attach($jobIds[$jobId]);
        }
    }
}
