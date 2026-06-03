<?php

namespace Tests\Unit\Jobs;

use App\Jobs\ProcessResponseJob;
use App\Models\Response;
use App\Models\Survey;
use App\Models\SurveyMember;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProcessResponseJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_increments_member_stats(): void
    {
        $survey = Survey::factory()->create();
        $member = SurveyMember::factory()->create([
            'total_responses' => 5,
            'total_earnings' => 10.00,
        ]);

        $response = Response::create([
            'survey_id' => $survey->id,
            'survey_member_id' => $member->id,
            'answers' => ['q1' => 5],
            'duration_seconds' => 100,
            'completion_status' => 'completed',
            'incentive_paid' => 2.50,
            'completed_at' => now(),
        ]);

        (new ProcessResponseJob($response))->handle();

        $member->refresh();
        $this->assertEquals(6, $member->total_responses);
        $this->assertEquals(12.50, $member->total_earnings);
    }
}
