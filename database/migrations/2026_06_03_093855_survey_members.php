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
        Schema::create('survey_members', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('name');
            $table->string('country', 2); // ISO code: US, RS, DE
            $table->string('age_group'); // 18-24, 25-34, etc
            $table->string('gender')->nullable();
            $table->integer('total_responses')->default(0);
            $table->decimal('total_earnings', 10, 2)->default(0);
            $table->timestamp('last_active_at')->nullable();
            $table->timestamps();

            $table->index('country');
            $table->index('age_group');
            $table->index(['country', 'age_group']); // composite
            $table->index('last_active_at');
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
