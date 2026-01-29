<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('wi_videos', function (Blueprint $table) {
            $table->string('video_source_type')->default('upload')->after('video_url');            $table->text('embed_code')->nullable()->after('video_source_type');
            $table->index('video_source_type');
        });
    }

    public function down(): void
    {
        Schema::table('wi_videos', function (Blueprint $table) {
            $table->dropIndex(['video_source_type']);
            $table->dropColumn(['video_source_type', 'embed_code']);
        });
    }
};
