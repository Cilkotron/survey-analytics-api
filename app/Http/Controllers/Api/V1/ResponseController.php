<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Jobs\ProcessResponseJob;
use App\Http\Requests\SurveyResponsePostRequest;

class ResponseController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $responses = Response::query()
            ->when($request->survey_id, fn($q, $id) => $q->where('survey_id', $id))
            ->when($request->status, fn($q, $s) => $q->where('completion_status', $s))
            ->with(['survey:id,title', 'member:id,country,age_group'])
            ->orderByDesc('completed_at')
            ->paginate($request->per_page ?? 50);

        return response()->json($responses);
    }

    public function store(SurveyResponsePostRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $response = Response::create($validated);
        ProcessResponseJob::dispatch($response);

        return response()->json($response, 201);
    }
}
