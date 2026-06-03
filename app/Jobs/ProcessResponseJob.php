<?php

namespace App\Jobs;

use App\Models\Response;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessResponseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(public Response $response) {}

    public function handle(): void
    {
        $member = $this->response->member;

        if (!$member) {
            Log::warning("Member not found for response {$this->response->id}");
            return;
        }

        // Update member stats
        $member->increment('total_responses');
        $member->increment('total_earnings', $this->response->incentive_paid);
        $member->update(['last_active_at' => now()]);

        // Invalidate cache so analytics refresh on next request
        cache()->forget("analytics:survey:{$this->response->survey_id}");
        cache()->forget('analytics:dashboard');

        Log::info("Processed response {$this->response->id} for member {$member->id}");
    }

    public function failed(\Throwable $exception): void
    {
        Log::error("ProcessResponseJob failed for response {$this->response->id}", [
            'error' => $exception->getMessage(),
        ]);
    }
}
