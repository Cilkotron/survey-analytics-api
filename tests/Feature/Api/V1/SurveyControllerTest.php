<?php

namespace Tests\Feature\Api\V1;

use App\Models\Survey;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SurveyControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_surveys(): void
    {
        Survey::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/surveys');

        $response->assertOk()
            ->assertJsonStructure(['data', 'current_page', 'total', 'per_page' ]);
    }

    public function test_can_filter_surveys_by_status(): void
    {
        Survey::factory()->active()->count(2)->create();
        Survey::factory()->create(['status' => 'paused']);

        $response = $this->getJson('/api/v1/surveys?status=active');

        $response->assertOk()
            ->assertJsonCount(2, 'data');
    }

    public function test_can_create_survey(): void
    {
        $payload = [
            'title' => 'Test Survey',
            'description' => 'Test description',
            'target_responses' => 1000,
            'questions' => [
                ['id' => 'q1', 'type' => 'rating', 'label' => 'Question 1', 'scale' => 5],
            ],
        ];

        $response = $this->postJson('/api/v1/surveys', $payload);

        $response->assertCreated()
            ->assertJsonFragment(['title' => 'Test Survey']);

        $this->assertDatabaseHas('surveys', ['title' => 'Test Survey']);
    }

    public function test_validates_required_fields_on_create(): void
    {
        $response = $this->postJson('/api/v1/surveys', []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['title', 'questions']);
    }
}
