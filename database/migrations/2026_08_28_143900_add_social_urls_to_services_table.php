<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->string('instagram_url', 2048)->nullable()->after('quote');
            $table->string('tiktok_url', 2048)->nullable()->after('instagram_url');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['instagram_url', 'tiktok_url']);
        });
    }
};
