<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Survey;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $surveys = Survey::query()
            ->when($request->status, fn($q, $status) => $q->where('status', $status))
            ->orderByDesc('started_at')
            ->paginate($request->per_page ?? 20);

        return response()->json($surveys);
    }

    public function show(Survey $survey): JsonResponse
    {
        return response()->json([
            'survey' => $survey,
            'responses_count' => $survey->responses()->count(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_responses' => 'integer|min:1',
            'questions' => 'required|array',
        ]);

        $survey = Survey::create($validated);

        return response()->json($survey, 201);
    }

    public function update(Request $request, Survey $survey): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'status' => 'sometimes|in:active,paused,completed',
        ]);

        $survey->update($validated);
        return response()->json($survey);
    }

    public function destroy(Survey $survey): JsonResponse
    {
        $survey->delete();
        return response()->json(null, 204);
    }
}
