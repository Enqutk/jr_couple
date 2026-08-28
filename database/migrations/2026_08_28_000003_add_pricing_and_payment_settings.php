<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('entities', function (Blueprint $table) {
            $table->decimal('price', 12, 2)->nullable()->after('description');
            $table->string('price_label', 60)->nullable()->after('price');
            $table->boolean('is_negotiable')->default(false)->after('price_label');
        });

        Schema::table('organizations', function (Blueprint $table) {
            $table->json('payment')->nullable()->after('theme');
        });
    }

    public function down(): void
    {
        Schema::table('entities', function (Blueprint $table) {
            $table->dropColumn(['price', 'price_label', 'is_negotiable']);
        });

        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn('payment');
        });
    }
};
