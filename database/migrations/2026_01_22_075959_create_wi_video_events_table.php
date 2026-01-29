<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('wi_video_events', function (Blueprint $table) {
            $table->id();

            $table->foreignId('wi_video_id')
                ->constrained('wi_videos')
                ->cascadeOnDelete();

            $table->unsignedInteger('time_seconds');

            $table->string('type')->default('quiz');

            $table->string('question');
            $table->json('options');
            $table->unsignedTinyInteger('correct_index');
            $table->text('explanation')->nullable();

            $table->boolean('is_required')->default(true);
            $table->unsignedInteger('rewind_to_seconds')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index(['wi_video_id', 'time_seconds'], 'idx_evt_time');
            $table->index(['wi_video_id', 'is_active'], 'idx_evt_act');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wi_video_events');
    }
};
