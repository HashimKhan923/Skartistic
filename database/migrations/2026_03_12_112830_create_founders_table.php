<?php
// ============================================================
// MIGRATION 4: create_founders_table.php
// Location: database/migrations/xxxx_xx_xx_create_founders_table.php
// ============================================================
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('founders', function (Blueprint $table) {
            $table->id();
            $table->string('name');                   // e.g. "Shihab Sarar Ahmed"
            $table->text('bio');                      // paragraph shown on about page
            $table->string('company')->nullable();    // e.g. "SK Artistic"
            $table->string('photo')->nullable();      // storage path
            $table->string('website')->nullable();    // personal website URL
            $table->string('linkedin')->nullable();   // LinkedIn profile URL
            $table->string('twitter')->nullable();    // Twitter/X URL
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('founders');
    }
};