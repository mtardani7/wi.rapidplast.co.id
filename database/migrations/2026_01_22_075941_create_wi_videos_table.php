<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('wi_videos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('work_instruction_id')
                ->constrained('work_instructions')
                ->cascadeOnDelete();

            $table->string('title');
            $table->text('description')->nullable();

            $table->string('video_url');
            $table->unsignedInteger('duration_seconds')->nullable();

            $table->unsignedInteger('sort_order')->default(1);
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index(['work_instruction_id', 'sort_order'], 'idx_wiv_sort');
            $table->index(['work_instruction_id', 'is_active'], 'idx_wiv_act');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wi_videos');
    }
};
