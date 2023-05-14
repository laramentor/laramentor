<?php

namespace App\Services;

use Spatie\GoogleCalendar\Event;

class GoogleCalendarEventService extends Event
{
    protected $use_live_api;

    public function __construct()
    {
        parent::__construct();

        $this->use_live_api = config('google-calendar.use_live_api');
    }

    public function addMeetLink()
    {
        // if we're not using the live api, set a fake meet link
        if (! $this->use_live_api) {
            $this->googleEvent->setHangoutLink('https://meet.google.com/abc-defg-hij');
        } else {
            // otherwise, create a meet link using the parent class
            parent::addMeetLink();
        }
    }

    public static function find($eventId, string $calendarId = null): self
    {
        // if we're not using the live api, set a fake event id and meet link
        $event = new self;
        if (! $event->use_live_api) {
            $event->googleEvent->setId('abcdefg');
            $event->googleEvent->setHangoutLink('https://meet.google.com/abc-defg-hij');

            return $event;
        }

        // otherwise, find the event using the parent class
        return parent::find($eventId);
    }

    public function save(string $method = null, $optParams = []): self
    {
        // if we're not using the live api, set a fake event id
        if (! $this->use_live_api) {
            $this->googleEvent->setId('abcdefg');

            return $this;
        }

        // otherwise, save the event using the parent class
        return parent::save($method, $optParams);
    }

    public function delete(string $eventId = null, $optParams = [])
    {
        // if we're not using the live api, do nothing
        if (! $this->use_live_api) {
            return;
        }

        // otherwise, delete the event using the parent class
        parent::delete();
    }
}
