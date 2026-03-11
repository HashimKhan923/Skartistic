<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pricing_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('tagline')->nullable();
            $table->string('badge')->nullable();
            $table->string('price')->nullable();
            $table->string('period')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('pricing_plans'); }
};