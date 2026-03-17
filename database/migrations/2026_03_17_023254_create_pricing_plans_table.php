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
        Schema::create('pricing_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');                          // e.g. Starter, Pro, Enterprise
            $table->string('badge')->nullable();             // e.g. "Most Popular"
            $table->decimal('price', 10, 2)->nullable();     // null = custom/contact
            $table->string('price_suffix')->default('/mo');  // /mo, /yr, one-time
            $table->string('description')->nullable();
            $table->json('features');                        // ["Feature 1", "Feature 2", ...]
            $table->json('excluded_features')->nullable();   // features shown as crossed out
            $table->string('cta_label')->default('Get Started');
            $table->string('cta_url')->nullable();
            $table->boolean('is_featured')->default(false);  // highlighted card
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
        Schema::dropIfExists('pricing_plans');
    }
};
