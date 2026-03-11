<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('portfolios', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->string('client')->nullable();
            $table->string('category')->nullable(); // Web, Mobile, UI/UX, Graphic
            $table->string('tags')->nullable(); // comma-separated
            $table->string('thumbnail')->nullable();
            $table->json('images')->nullable(); // gallery
            $table->string('project_url')->nullable();
            $table->date('completed_at')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('portfolios'); }
};