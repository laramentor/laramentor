<?php

namespace Tests\Unit;

use App\Models\Session;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SessionTest extends TestCase
{
    use RefreshDatabase;

    private $mentor;

    private $mentee;

    private $session;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mentor = User::factory()->create()->mentor()->create();
        $this->mentee = User::factory()->create();
        $this->session = Session::factory()->create([
            'user_id' => $this->mentee->id,
            'mentor_id' => $this->mentor->id,
        ]);
    }

    /** @test */
    public function test_session_has_start_and_end()
    {
        $startTime = Carbon::now()->toDateTimeString();
        $endTime = Carbon::now()->addHour()->toDateTimeString();

        $this->session->start_date_time = $startTime;
        $this->session->end_date_time = $endTime;
        $this->session->save();

        $this->assertEquals($startTime, $this->session->start_date_time);
        $this->assertEquals($endTime, $this->session->end_date_time);
    }

    /** @test */
    public function test_session_has_a_name()
    {
        $this->session->name = 'Test Session';
        $this->session->save();

        $this->assertEquals('Test Session', $this->session->name);
    }

    /** @test */
    public function test_session_has_a_description()
    {
        $this->session->description = 'Test Session Description';
        $this->session->save();

        $this->assertEquals('Test Session Description', $this->session->description);
    }

    /** @test */
    public function test_session_has_a_mentor()
    {
        $this->assertEquals($this->mentor->id, $this->session->mentor->id);
    }

    /** @test */
    public function test_session_has_a_mentee()
    {
        $this->assertEquals($this->mentee->id, $this->session->user->id);
    }

    /** @test */
    public function test_session_can_be_updated()
    {
        $this->session->name = 'New Session Name';
        $this->session->description = 'New Session Description';
        $this->session->save();

        $this->assertEquals('New Session Name', $this->session->name);
        $this->assertEquals('New Session Description', $this->session->description);
    }

    /** @test */
    public function test_session_can_be_deleted()
    {
        $this->session->delete();
        $this->assertNull($this->session->fresh());
    }

    /** @test */
    public function test_meeting_can_be_created_for_session()
    {
        $this->session->createMeeting();
        $this->assertNotNull($this->session->event_id);
        $this->assertNotNull($this->session->meeting_link);
    }

    /** @test */
    public function test_meeting_time_can_be_updated_for_session()
    {
        $this->session->createMeeting();
        $this->session->start_date_time = now()->addHour();
        $this->session->end_date_time = now()->addHour()->addHour();
        $this->session->save();

        $this->session->updateMeeting();
        $this->assertEquals($this->session->start_date_time, $this->session->fresh()->start_date_time);
        $this->assertEquals($this->session->end_date_time, $this->session->fresh()->end_date_time);
    }

    /** @test */
    public function test_meeting_can_be_deleted_for_session()
    {
        $this->session->createMeeting();
        $this->session->deleteMeeting();
        $this->assertNull($this->session->event_id);
        $this->assertNull($this->session->meeting_link);
    }
}
