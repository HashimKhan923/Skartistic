<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('careers', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('type')->default('Full-time');
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->longText('requirements')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('careers'); }
};