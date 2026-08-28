<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\StatusEnum;
use App\Enums\EntityTypeEnum;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('entities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type')->default(EntityTypeEnum::client->value);
            $table->string('link', 2048)->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('order')->default(1);
            $table->string('status')->default(StatusEnum::active->value);
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('entities');
    }
};
