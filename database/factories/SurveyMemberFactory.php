<?php

namespace Database\Factories;

use App\Models\SurveyMember;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SurveyMember>
 */
class SurveyMemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email' => $this->faker->unique()->safeEmail(),
            'name' => $this->faker->name(),
            'country' => $this->faker->randomElement(['US', 'GB', 'DE', 'FR', 'RS', 'BR', 'IN']),
            'age_group' => $this->faker->randomElement(['18-24', '25-34', '35-44', '45-54', '55-64', '65+']),
            'gender' => $this->faker->randomElement(['male', 'female', 'other', null]),
            'total_responses' => 0,
            'total_earnings' => 0,
            'last_active_at' => $this->faker->dateTimeBetween('-90 days', 'now'),
        ];
    }
}
