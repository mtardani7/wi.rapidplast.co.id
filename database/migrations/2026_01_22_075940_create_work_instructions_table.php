<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('work_instructions', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->text('description')->nullable();

            $table->boolean('is_published')->default(true);

            $table->timestamps();

            $table->index('is_published', 'idx_wi_pub');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_instructions');
    }
};
