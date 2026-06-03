<?php

namespace Database\Factories;

use App\Models\Survey;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Survey>
 */
class SurveyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
     public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement(['active', 'paused', 'completed']),
            'target_responses' => $this->faker->numberBetween(1000, 50000),
            'questions' => [
                ['id' => 'q1', 'type' => 'rating', 'label' => $this->faker->sentence(), 'scale' => 5],
                ['id' => 'q2', 'type' => 'choice', 'label' => $this->faker->sentence(), 'options' => ['yes', 'no', 'maybe']],
                ['id' => 'q3', 'type' => 'text', 'label' => $this->faker->sentence()],
            ],
            'started_at' => $this->faker->dateTimeBetween('-1 year', '-1 month'),
            'ended_at' => null,
        ];
    }

    public function active(): self
    {
        return $this->state(['status' => 'active']);
    }
}
