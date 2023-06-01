<?php

namespace App\Http\Controllers;

use App\Models\Mentor;
use App\Models\Session;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
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
        $mentors = Mentor::with(['user', 'sessions.mentee.user', 'sessions.mentor', 'skills'])->get();

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
            'mentor_id' => ['required', 'exists:mentors,id'],
            'start_date_time' => ['required', 'date'],
        ]);

        $start_date_time = Carbon::parse($request->start_date_time, auth()->user()->timezone)->setTimezone(config('app.timezone'));

        $session = new Session;
        $session->uuid = Str::uuid();
        $session->name = 'Demo session';
        $session->description = 'This is a demo session';
        $session->mentee_id = auth()->user()->mentee->id;
        $session->mentor_id = $request->mentor_id;
        $session->start_date_time = $start_date_time;
        $session->end_date_time = $start_date_time->addMinutes(60); // change to a config value set by mentor
        $session->save();

        $session->createMeeting();

        return Redirect::route('dashboard')->with('success', 'Mentor booked successfully!');
    }

    /**
     * Show the booked session. (ONLY FOR DEMO PURPOSES)
     */
    public function show(Session $session): Response
    {
        $session->load(['mentee.user', 'mentor.user']);

        return Inertia::render('Session', [
            'session' => $session,
        ]);
    }
}
