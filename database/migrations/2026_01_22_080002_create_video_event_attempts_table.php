<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('video_event_attempts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('participant_id')
                ->constrained('participants')
                ->cascadeOnDelete();

            $table->foreignId('wi_video_id')
                ->constrained('wi_videos')
                ->cascadeOnDelete();

            $table->foreignId('wi_video_event_id')
                ->constrained('wi_video_events')
                ->cascadeOnDelete();

            $table->unsignedTinyInteger('selected_index')->nullable();
            $table->boolean('is_correct')->default(false);
            $table->unsignedTinyInteger('attempt_no')->default(1);

            $table->timestamps();

            $table->index(
                ['participant_id', 'wi_video_id', 'wi_video_event_id'],
                'idx_att_pve'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('video_event_attempts');
    }
};
