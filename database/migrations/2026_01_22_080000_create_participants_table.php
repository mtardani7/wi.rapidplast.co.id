<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('participants', function (Blueprint $table) {
            $table->id();

            $table->string('nik', 50)->unique('uq_part_nik');
            $table->string('name');
            $table->enum('plan', ['rx00','rx01','rx02','rx03','rx04','rx05','rx06']);

            $table->timestamps();

            $table->index('plan', 'idx_part_plan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};
