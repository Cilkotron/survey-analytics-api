<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Survey;

class EnsureSurveyIsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $survay = Survey::find($request->route('survey'));
        if(!$survay) {
            return response()->json(['error' => 'Survey not found'], 404);
        }
        if($survay->status !== 'active') {
            return response()->json(['error' => 'Movie status not active'], 403);
        }
        return $next($request);
    }
}
