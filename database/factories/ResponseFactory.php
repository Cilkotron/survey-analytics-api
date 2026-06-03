<?php

namespace Database\Factories;

use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Survey;
use App\Models\SurveyMember;

/**
 * @extends Factory<Model>
 */
class ResponseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'survey_id' => Survey::inRandomOrder()->first()->id,
            'user_id' => SurveyMember::inRandomOrder()->first()->id,
            'answers' => json_encode([
                'q1' => $this->faker->numberBetween(1, 5),
                'q2' => $this->faker->randomElement(['yes', 'no', 'maybe']),
                'q3' => $this->faker->paragraph(),
            ]),
            'duration_seconds' => $this->faker->numberBetween(30, 1800),
            'completion_status' => $this->faker->randomElement(['completed', 'partial', 'abandoned']),
            'incentive_paid' => $this->faker->randomFloat(2, 0.5, 5.0),
            'completed_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
