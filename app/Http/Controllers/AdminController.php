<?php
namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\User;
use App\Models\Application;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard with summary statistics
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        // Get recent jobs and users for the dashboard
        $jobs = Job::latest()->take(5)->get();
        $users = User::latest()->take(5)->get();

        // Get some statistics
        $stats = [
            'totalJobs' => Job::count(),
            'pendingJobs' => Job::where('is_approved', false)->count(),
            'totalUsers' => User::count(),
            'totalApplications' => Application::count(),
        ];

        return view('admin.dashboard', compact('jobs', 'users', 'stats'));
    }

    /**
     * Display a list of all users for management
     *
     * @return \Illuminate\View\View
     */
    public function manageUsers()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.users.manage', compact('users'));
    }

    /**
     * Display a list of pending jobs awaiting approval
     *
     * @return \Illuminate\View\View
     */
    public function pendingJobs()
    {
        $jobs = Job::where('is_approved', false)
            ->with('employer')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.jobs.pending', compact('jobs'));
    }

    /**
     * Approve a job listing
     *
     * @param int $id The job ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approveJob($id)
    {
        $job = Job::findOrFail($id);
        $job->is_approved = true;
        $job->save();

        return redirect()->route('admin.jobs.pending')
            ->with('success', "Job '{$job->title}' has been approved and is now visible to candidates.");
    }

    /**
     * Reject a job listing
     *
     * @param int $id The job ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function rejectJob($id)
    {
        $job = Job::findOrFail($id);
        $jobTitle = $job->title;

        // Since we only have a boolean is_approved field, we'll just delete rejected jobs
        // Alternatively, you could add a 'rejected' status column to the job_posts table
        $job->delete();

        return redirect()->route('admin.jobs.pending')
            ->with('success', "Job '{$jobTitle}' has been rejected and removed from the system.");
    }
}