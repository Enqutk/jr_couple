<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('menu_id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('title', 255);
            $table->string('icon', 100)->nullable();
            $table->enum('link_type', ['internal', 'external'])->default('internal');
            $table->string('url', 500)->nullable();
            $table->enum('target', ['_self', '_blank'])->default('_self');
            $table->integer('order_number')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('menu_id')->references('id')->on('menu_locations')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('menu_items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
