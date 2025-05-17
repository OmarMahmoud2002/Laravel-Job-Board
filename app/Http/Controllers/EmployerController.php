<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EmployerController extends Controller
{
    /**
     * Display the employer dashboard with their job listings
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Get all jobs posted by this employer
        $jobs = Job::where('employer_id', Auth::id())
            ->withCount('applications')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get some statistics
        $stats = [
            'totalJobs' => $jobs->count(),
            'approvedJobs' => $jobs->where('is_approved', true)->count(),
            'pendingJobs' => $jobs->where('is_approved', false)->count(),
            'totalApplications' => $jobs->sum('applications_count'),
        ];

        return view('employer.dashboard', compact('jobs', 'stats', 'user'));
    }

    /**
     * Show the employer's profile
     *
     * @return \Illuminate\View\View
     */
    public function profile()
    {
        $employer = Auth::user();
        return view('employer.profile', compact('employer'));
    }

    /**
     * Update the employer's profile information
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'company_name' => 'required|string|max:255',
            'website' => 'nullable|url',
            'bio' => 'nullable|string|max:1000',
        ]);

        // Handle company logo upload if provided
        if ($request->hasFile('company_logo')) {
            $request->validate([
                'company_logo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Delete old logo if exists
            $employer = Auth::user();
            if ($employer->company_logo) {
                Storage::disk('public')->delete($employer->company_logo);
            }

            // Store new logo
            $path = $request->file('company_logo')->store('company_logos', 'public');
            $employer->company_logo = $path;
        }

        $employer = Auth::user();
        $employer->update($request->only([
            'name', 'email', 'company_name', 'website', 'bio'
        ]));

        return redirect()->route('employer.profile')
            ->with('success', 'Your profile has been updated successfully.');
    }
}
