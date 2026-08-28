<?php

use App\Enums\EntityTypeEnum;
use App\Enums\PostSourceEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('entities', function (Blueprint $table) {
            $table->string('source', 32)->nullable()->after('category');
        });

        DB::table('entities')
            ->where('type', EntityTypeEnum::post->value)
            ->whereNull('source')
            ->update(['source' => PostSourceEnum::media->value]);
    }

    public function down(): void
    {
        Schema::table('entities', function (Blueprint $table) {
            $table->dropColumn('source');
        });
    }
};
