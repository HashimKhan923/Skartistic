<?php
// ============================================================
// MIGRATION 2: create_awards_table.php
// Location: database/migrations/xxxx_xx_xx_create_awards_table.php
// ============================================================
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('awards', function (Blueprint $table) {
            $table->id();
            $table->string('platform');           // e.g. "Clutch", "GoodFirms"
            $table->string('achievement');         // e.g. "Making Waves on the World Stage"
            $table->string('year', 10);            // e.g. "2024"
            $table->string('logo_path')->nullable(); // optional uploaded logo image
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('awards');
    }
};