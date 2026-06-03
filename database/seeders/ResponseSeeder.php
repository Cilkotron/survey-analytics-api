<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Survey;
use App\Models\SurveyMember;
use Illuminate\Support\Facades\DB;

class ResponseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        $surveyIds = Survey::pluck('id')->toArray();
        $surveyMemberIds = SurveyMember::pluck('id')->toArray();
        $statuses = ['completed', 'partial', 'abandoned'];

        $totalRecords = 50_000;
        $chunkSize = 5000;
        $chunks = $totalRecords / $chunkSize;

        for ($i = 0; $i < $chunks; $i++) {
            $batch = [];
            for ($j = 0; $j < $chunkSize; $j++) {
                $batch[] = [
                    'survey_id' => $surveyIds[array_rand($surveyIds)],
                    'survey_member_id' => $surveyMemberIds[array_rand($surveyMemberIds)],
                    'answers' => json_encode([
                        'q1' => rand(1, 5),
                        'q2' => ['yes', 'no', 'maybe'][rand(0, 2)],
                    ]),
                    'duration_seconds' => rand(30, 1800),
                    'completion_status' => $statuses[array_rand($statuses)],
                    'incentive_paid' => rand(50, 500) / 100,
                    'completed_at' => now()->subDays(rand(1, 365)),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            DB::table('responses')->insert($batch);
            $this->command->info("Inserted " . (($i + 1) * $chunkSize) . " records");
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
