<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('participant_video_progress', function (Blueprint $table) {
            $table->id();

            $table->foreignId('participant_id')
                ->constrained('participants')
                ->cascadeOnDelete();

            $table->foreignId('wi_video_id')
                ->constrained('wi_videos')
                ->cascadeOnDelete();

            $table->unsignedInteger('last_time_seconds')->default(0);
            $table->unsignedInteger('score')->default(0);

            $table->timestamp('completed_at')->nullable();

            $table->timestamps();

            $table->unique(['participant_id', 'wi_video_id'], 'uq_prog_pv');
            $table->index(['wi_video_id', 'completed_at'], 'idx_prog_comp');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('participant_video_progress');
    }
};
