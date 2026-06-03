<?php

namespace Tests\Feature\Api\V1;

use App\Jobs\ProcessResponseJob;
use App\Models\Survey;
use App\Models\SurveyMember;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ResponseControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_creates_response_and_dispatches_job(): void
    {
        Queue::fake();

        $survey = Survey::factory()->create();
        $member = SurveyMember::factory()->create();

        $payload = [
            'survey_id' => $survey->id,
            'survey_member_id' => $member->id,
            'answers' => ['q1' => 5, 'q2' => 'yes'],
            'duration_seconds' => 120,
            'completion_status' => 'completed',
            'incentive_paid' => 2.50,
        ];

        $response = $this->postJson('/api/v1/responses', $payload);

        $response->assertCreated();
        $this->assertDatabaseHas('responses', ['survey_id' => $survey->id]);

        Queue::assertPushed(ProcessResponseJob::class);
    }

    public function test_validates_survey_exists(): void
    {
        $member = SurveyMember::factory()->create();

        $response = $this->postJson('/api/v1/responses', [
            'survey_id' => 99999,
            'survey_member_id' => $member->id,
            'answers' => ['q1' => 1],
            'duration_seconds' => 60,
            'completion_status' => 'completed',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['survey_id']);
    }
}
