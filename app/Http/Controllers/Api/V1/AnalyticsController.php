<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Response;
use App\Models\Survey;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function survey(Survey $survey): JsonResponse
    {
        $cacheKey = "analytics:survey:{$survey->id}";

        $data = Cache::remember($cacheKey, 300, function () use ($survey) {
            $base = Response::where('survey_id', $survey->id);

            //$base->ddRawSql();

            return [
                'survey_id' => $survey->id,
                'survey_title' => $survey->title,
                'total_responses' => (clone $base)->count(),
                'completed_responses' => (clone $base)->where('completion_status', 'completed')->count(),
                'completion_rate' => round(
                    (clone $base)->where('completion_status', 'completed')->count() /
                    max((clone $base)->count(), 1) * 100,
                    2
                ),
                'avg_duration_seconds' => round((clone $base)->avg('duration_seconds'), 0),
                'total_incentive_paid' => (clone $base)->sum('incentive_paid'),
                'responses_by_country' => $this->responsesByCountry($survey->id),
                'responses_by_age_group' => $this->responsesByAgeGroup($survey->id),
            ];
        });

        return response()->json($data);
    }

    public function dashboard(): JsonResponse
    {
        $data = Cache::remember('analytics:dashboard', 600, function () {
            return [
                'total_surveys' => Survey::count(),
                'active_surveys' => Survey::where('status', 'active')->count(),
                'total_responses' => Response::count(),
                'responses_last_30_days' => Response::where('completed_at', '>=', now()->subDays(30))->count(),
                'top_surveys' => Survey::withCount('responses')->orderByDesc('responses_count')->limit(5)->get(),
            ];
        });

        return response()->json($data);
    }

    private function responsesByCountry(int $surveyId): array
    {
        return DB::table('responses')
            ->join('survey_members', 'responses.survey_member_id', '=', 'survey_members.id')
            ->where('responses.survey_id', $surveyId)
            ->selectRaw('survey_members.country, COUNT(*) as count')
            ->groupBy('survey_members.country')
            ->orderByDesc('count')
            ->get()
            ->toArray();
    }

    private function responsesByAgeGroup(int $surveyId): array
    {
        return DB::table('responses')
            ->join('survey_members', 'responses.survey_member_id', '=', 'survey_members.id')
            ->where('responses.survey_id', $surveyId)
            ->selectRaw('survey_members.age_group, COUNT(*) as count')
            ->groupBy('survey_members.age_group')
            ->orderBy('survey_members.age_group')
            ->get()
            ->toArray();
    }
}
