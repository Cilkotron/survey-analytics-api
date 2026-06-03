<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survey_id')->constrained()->cascadeOnDelete();
            $table->foreignId('survey_member_id')->constrained()->cascadeOnDelete();
            $table->json('answers'); // {q1: 5, q2: 'yes', q3: 'text...'}
            $table->integer('duration_seconds');
            $table->string('completion_status'); // completed, partial, abandoned
            $table->decimal('incentive_paid', 8, 2)->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            // Composite indexes for high-traffic queries
            $table->index(['survey_id', 'completed_at']); // for analytics per survey
            $table->index(['survey_id', 'completion_status']); // filter by status
            $table->index(['survey_member_id', 'completed_at']); // user history
            $table->index('completed_at'); // global time-based queries
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
