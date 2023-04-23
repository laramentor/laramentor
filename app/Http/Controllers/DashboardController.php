<?php

namespace App\Http\Controllers;

use App\Models\Mentor;
use App\Models\Session;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index(): Response
    {
        // get a list of mentors with their sessions and mentees
        $mentors = Mentor::with(['user', 'sessions.mentee.user'])->get();

        return Inertia::render('Dashboard', [
            'mentors' => $mentors,
        ]);
    }

    /**
     * Book a mentor session. (ONLY FOR DEMO PURPOSES)
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'mentor_id' => ['required', 'exists:users,id'],
        ]);

        $session = new Session;
        $session->name = 'Demo session';
        $session->description = 'This is a demo session';
        $session->mentee_id = auth()->user()->mentee->id;
        $session->mentor_id = $request->mentor_id;
        $session->start_date_time = now()->addDays(7); // Change this to date picker
        $session->end_date_time = now()->addDays(7)->addHours(1); // Change this to date picker
        $session->save();

        $session->createMeeting();

        return Redirect::route('dashboard')->with('success', 'Mentor booked successfully!');
    }

    /**
     * Show the booked session. (ONLY FOR DEMO PURPOSES)
     */
    public function show(Session $session): Response
    {
        return Inertia::render('Session', [
            'session' => $session,
        ]);
    }
}
