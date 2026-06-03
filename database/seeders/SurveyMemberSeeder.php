<?php

namespace Database\Seeders;

use App\Models\SurveyMember;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SurveyMemberSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        $countries = ['US', 'GB', 'DE', 'FR', 'RS', 'BR', 'IN', 'CA', 'AU', 'JP'];
        $ageGroups = ['18-24', '25-34', '35-44', '45-54', '55-64', '65+'];
        $genders = ['male', 'female', 'other', null];

        $totalMembers = 20_000;
        $chunkSize = 5000;
        $chunks = $totalMembers / $chunkSize;

        for ($i = 0; $i < $chunks; $i++) {
            $batch = [];
            for ($j = 0; $j < $chunkSize; $j++) {
                $memberId = $i * $chunkSize + $j + 1;
                $batch[] = [
                    'email' => "member{$memberId}@example.com",
                    'name' => "Member {$memberId}",
                    'country' => $countries[array_rand($countries)],
                    'age_group' => $ageGroups[array_rand($ageGroups)],
                    'gender' => $genders[array_rand($genders)],
                    'total_responses' => 0,
                    'total_earnings' => 0,
                    'last_active_at' => now()->subDays(rand(1, 90)),
                    'created_at' => now()->subDays(rand(30, 365)),
                    'updated_at' => now(),
                ];
            }
            DB::table('survey_members')->insert($batch);
            $this->command->info("Inserted " . (($i + 1) * $chunkSize) . " members");
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
