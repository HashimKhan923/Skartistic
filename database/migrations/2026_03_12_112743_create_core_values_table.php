<?php
// ============================================================
// MIGRATION 3: create_core_values_table.php
// Location: database/migrations/xxxx_xx_xx_create_core_values_table.php
// ============================================================
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('core_values', function (Blueprint $table) {
            $table->id();
            $table->string('icon')->default('🎯');   // emoji or SVG string
            $table->string('title');                  // e.g. "Be world-class"
            $table->text('description');              // paragraph text
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('core_values');
    }
};