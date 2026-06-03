<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'survey_id' => 'required|exists:surveys,id',
            'survey_member_id' => 'required|exists:survey_members,id',
            'answers' => 'required|array',
            'duration_seconds' => 'required|integer|min:1',
            'completion_status' => 'required|in:completed,partial,abandoned',
        ]);

        $validated['completed_at'] = now();
        $response = Response::create($validated);

        return response()->json($response, 201);
    }
}
