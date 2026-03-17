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
Schema::create('careers', function (Blueprint $table) {
            $table->id();
            $table->string('title');                         // Job title
            $table->string('department')->nullable();        // Engineering, Design, etc.
            $table->string('location')->nullable();          // Remote, Karachi, etc.
            $table->string('type')->default('Full-time');    // Full-time, Part-time, Contract
            $table->string('experience')->nullable();        // 2-4 years
            $table->text('summary');                         // short card description
            $table->longText('description')->nullable();     // full job detail
            $table->json('responsibilities')->nullable();    // ["...","..."]
            $table->json('requirements')->nullable();        // ["...","..."]
            $table->json('benefits')->nullable();            // ["...","..."]
            $table->string('apply_email')->nullable();
            $table->string('apply_url')->nullable();
            $table->date('deadline')->nullable();
            $table->boolean('is_published')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('careers');
    }
};
