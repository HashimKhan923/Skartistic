<?php
// FILE: database/migrations/2024_01_10_000001_create_services_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('icon')->nullable();             // emoji or SVG name
            $table->string('tag_label')->nullable();        // e.g. "Web Development" shown in purple above hero title
            $table->text('hero_headline')->nullable();      // big hero H1
            $table->text('hero_subtitle')->nullable();      // hero paragraph
            $table->string('hero_cta_primary')->default('Get in touch');
            $table->string('hero_cta_secondary')->default('Learn more');

            // What we offer section
            $table->string('offer_tag')->nullable();        // "What we offer"
            $table->string('offer_title')->nullable();      // "Tailored Web Solutions..."
            $table->text('offer_subtitle')->nullable();
            // Features stored as JSON: [{title, description, visual_type}]
            $table->json('offer_features')->nullable();

            // Tech Stack section
            $table->string('techstack_tag')->nullable();    // "Tech Stack"
            $table->string('techstack_title')->nullable();
            $table->text('techstack_subtitle')->nullable();
            // Categories: [{name, items:[{name, icon_url}]}]
            $table->json('tech_categories')->nullable();

            // Work Process section
            $table->string('process_tag')->nullable();      // "Work Process"
            $table->string('process_title')->nullable();
            $table->text('process_subtitle')->nullable();
            // Steps: [{title, description, features:[{icon, label}]}]
            $table->json('process_steps')->nullable();

            // Featured Work section
            $table->string('work_tag')->nullable();         // "Featured Work"
            $table->string('work_title')->nullable();
            $table->text('work_subtitle')->nullable();
            // Projects: [{title, description, image, client, role, year, duration, team, status, features:[], live_url, case_study_url}]
            $table->json('featured_projects')->nullable();

            // CTA band at bottom
            $table->string('cta_title')->nullable();
            $table->string('cta_subtitle')->nullable();

            // Meta / admin
            $table->string('short_description')->nullable();
            $table->string('banner_image')->nullable();
            $table->boolean('is_published')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};