<?php

namespace App\Models;

use App\Services\GoogleCalendarEventService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Session extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'start_date_time' => 'datetime',
        'end_date_time' => 'datetime',
    ];

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function mentee(): BelongsTo
    {
        return $this->belongsTo(Mentee::class);
    }

    public function mentor(): BelongsTo
    {
        return $this->belongsTo(Mentor::class);
    }

    public function createMeeting()
    {
        $event = new GoogleCalendarEventService;
        $event->name = $this->name;
        $event->description = $this->description;
        $event->startDateTime = $this->start_date_time;
        $event->endDateTime = $this->end_date_time;
        $event->addAttendee([
            'email' => $this->mentee->user->email,
        ]);
        $event->addAttendee([
            'email' => $this->mentor->user->email,
        ]);
        $event->addMeetLink();

        $newEvent = $event->save();

        $this->google_event_id = $newEvent->googleEvent->id;
        $this->google_meeting_link = $newEvent->googleEvent->hangoutLink;
        $this->save();
    }

    public function updateMeeting()
    {
        $event = GoogleCalendarEventService::find($this->event_id);
        $event->name = $this->name;
        $event->description = $this->description;
        $event->startDateTime = $this->start_date_time;
        $event->endDateTime = $this->end_date_time;
        $event->addAttendee([
            'email' => $this->mentee->user->email,
        ]);
        $event->addAttendee([
            'email' => $this->mentor->user->email,
        ]);

        $event->save();
    }

    public function deleteMeeting()
    {
        $event = GoogleCalendarEventService::find($this->event_id);
        $event->delete($event->id);

        $this->google_event_id = null;
        $this->google_meeting_link = null;
        $this->save();
    }
}
