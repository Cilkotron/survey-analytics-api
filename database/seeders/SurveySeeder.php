<?php

namespace Database\Seeders;

use App\Models\Survey;
use Illuminate\Database\Seeder;

class SurveySeeder extends Seeder
{
    public function run(): void
    {
        $surveys = [
            ['title' => 'Consumer Habits 2026', 'questions' => $this->habitsQuestions()],
            ['title' => 'Tech Adoption Survey', 'questions' => $this->techQuestions()],
            ['title' => 'Brand Awareness Study', 'questions' => $this->brandQuestions()],
            ['title' => 'Customer Satisfaction Q1', 'questions' => $this->satisfactionQuestions()],
            ['title' => 'Workplace Wellbeing 2026', 'questions' => $this->wellbeingQuestions()],
        ];

        foreach ($surveys as $data) {
            Survey::create([
                'title' => $data['title'],
                'description' => "Market research panel survey on " . $data['title'],
                'status' => 'active',
                'target_responses' => rand(5000, 50000),
                'questions' => $data['questions'],
                'started_at' => now()->subMonths(rand(1, 12)),
            ]);
        }
    }

    private function habitsQuestions(): array
    {
        return [
            ['id' => 'q1', 'type' => 'rating', 'label' => 'How often do you shop online?', 'scale' => 5],
            ['id' => 'q2', 'type' => 'choice', 'label' => 'Primary device?', 'options' => ['mobile', 'desktop', 'tablet']],
            ['id' => 'q3', 'type' => 'text', 'label' => 'What influences your purchase decisions?'],
        ];
    }

    private function techQuestions(): array
    {
        return [
            ['id' => 'q1', 'type' => 'choice', 'label' => 'Do you use AI tools?', 'options' => ['yes', 'no', 'sometimes']],
            ['id' => 'q2', 'type' => 'rating', 'label' => 'Comfort with new technology', 'scale' => 5],
        ];
    }

    private function brandQuestions(): array
    {
        return [
            ['id' => 'q1', 'type' => 'rating', 'label' => 'Brand recognition score', 'scale' => 10],
            ['id' => 'q2', 'type' => 'choice', 'label' => 'Where did you hear about us?', 'options' => ['social', 'tv', 'friend', 'search']],
        ];
    }

    private function satisfactionQuestions(): array
    {
        return [
            ['id' => 'q1', 'type' => 'rating', 'label' => 'Overall satisfaction', 'scale' => 5],
            ['id' => 'q2', 'type' => 'text', 'label' => 'What can we improve?'],
        ];
    }

    private function wellbeingQuestions(): array
    {
        return [
            ['id' => 'q1', 'type' => 'rating', 'label' => 'Work-life balance', 'scale' => 5],
            ['id' => 'q2', 'type' => 'choice', 'label' => 'Remote vs office?', 'options' => ['remote', 'office', 'hybrid']],
        ];
    }
}
