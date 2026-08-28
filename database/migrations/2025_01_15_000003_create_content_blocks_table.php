<?php

use App\Enums\ContentTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('content_blocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained('page_sections')->cascadeOnDelete();
            $table->string('type')->default(ContentTypeEnum::Text->value);
            $table->string('title')->nullable();
            $table->string('slug', 120)->unique();
            $table->string('icon', 100)->nullable();
            $table->string('subtitle')->nullable();
            $table->text('short_description')->nullable();
            $table->longText('content')->nullable();
            $table->json('list_items')->nullable();
            $table->string('video_url')->nullable();
            $table->json('metadata')->nullable();
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_blocks');
    }
};
