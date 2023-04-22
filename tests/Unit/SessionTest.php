<?php

namespace Tests\Unit;

use App\Models\Mentee;
use App\Models\Mentor;
use App\Models\Session;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->mentor = User::factory()->create()->mentor()->create();
    $this->mentee = User::factory()->create()->mentee()->create();
    $this->session = Session::factory()->create([
        'mentee_id' => $this->mentee->id,
        'mentor_id' => $this->mentor->id,
    ]);
});

it('has start and end', function () {
    $startTime = Carbon::now()->toDateTimeString();
    $endTime = Carbon::now()->addHour()->toDateTimeString();

    $this->session->start_date_time = $startTime;
    $this->session->end_date_time = $endTime;
    $this->session->save();

    expect($this->session->start_date_time)->toEqual($startTime);
    expect($this->session->end_date_time)->toEqual($endTime);
});

it('has a name', function () {
    $this->session->name = 'Test Session';
    $this->session->save();

    expect($this->session->name)->toBe('Test Session');
});

it('has a description', function () {
    $this->session->description = 'Test Session Description';
    $this->session->save();

    expect($this->session->description)->toBe('Test Session Description');
});

it('has a mentor', function () {
    expect($this->session->mentor)->toBeInstanceOf(Mentor::class);
    expect($this->session->mentor->id)->toEqual($this->mentor->id);
});

it('has a mentee', function () {
    expect($this->session->mentee)->toBeInstanceOf(Mentee::class);
    expect($this->session->mentee->id)->toEqual($this->mentee->id);
});

it('has a uuid', function () {
    expect($this->session->uuid)->toBeString();
});

it('can be updated', function () {
    $this->session->name = 'New Session Name';
    $this->session->description = 'New Session Description';
    $this->session->save();

    expect($this->session->name)->toBe('New Session Name');
    expect($this->session->description)->toBe('New Session Description');
});

it('can be deleted', function () {
    $this->session->delete();
    expect($this->session->fresh())->toBeNull();
});

it('can create a meeting', function () {
    $this->session->createMeeting();

    expect($this->session->google_event_id)->toBeString();
    expect($this->session->google_meeting_link)->toBeString();
});

it('can update a meeting', function () {
    $this->session->createMeeting();
    $this->session->start_date_time = now()->addHour();
    $this->session->end_date_time = now()->addHour()->addHour();
    $this->session->save();

    $this->session->updateMeeting();

    expect($this->session->fresh()->start_date_time)->toEqual($this->session->start_date_time);
    expect($this->session->fresh()->end_date_time)->toEqual($this->session->end_date_time);
});

it('can delete a meeting', function () {
    $this->session->createMeeting();
    $this->session->deleteMeeting();

    expect($this->session->fresh()->google_event_id)->toBeNull();
    expect($this->session->fresh()->google_meeting_link)->toBeNull();
});
