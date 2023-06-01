<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Mentor;
use App\Models\Skill;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Update the user's timezone.
     */
    public function updateTimezone(Request $request): RedirectResponse
    {
        $request->validate([
            'timezone' => ['required', 'timezone'],
        ]);

        $request->user()->fill($request->only('timezone'));
        $request->user()->save();

        return Redirect::route('profile.edit');
    }

    /**
     * Update user mentor status
     */
    public function updateMentorStatus(Request $request): RedirectResponse
    {
        $request->validate([
            'is_mentor' => ['required', 'boolean'],
        ]);

        Auth::user()->mentor()->save(new Mentor);

        return Redirect::route('profile.edit');
    }

    /**
     * Show mentor information
     */
    public function showMentorInformation(Request $request)
    {
        return response()->json([
            'mentor' => $request->user()->mentor()->with('skills')->first(),
        ]);
    }

    /**
     * Update user mentor information
     */
    public function updateMentorInformation(Request $request): RedirectResponse
    {
        $request->validate([
            'job_title' => ['nullable', 'string'],
            'company' => ['nullable', 'string'],
            'hourly_rate' => ['nullable', 'numeric'],
            'skills' => ['nullable', 'array'],
        ]);

        $mentor = $request->user()->mentor;
        $mentor->job_title = $request->job_title;
        $mentor->company = $request->company;
        $mentor->hourly_rate = $request->hourly_rate;
        $mentor->save();

        $mentor->skills()->sync(collect($request->skills)->pluck('id'));

        return Redirect::route('profile.edit');
    }

    /**
     * Show skills
     */
    public function showSkills(Request $request)
    {
        return response()->json([
            'skills' => Skill::all(),
        ]);
    }
}
