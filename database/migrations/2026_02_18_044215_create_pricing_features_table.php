<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pricing_features', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pricing_plan_id')->constrained()->onDelete('cascade');
            $table->string('feature');
            $table->boolean('included')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('pricing_features'); }
};