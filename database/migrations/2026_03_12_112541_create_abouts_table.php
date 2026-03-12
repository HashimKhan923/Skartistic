<?php
// ============================================================
// MIGRATION 1: create_abouts_table.php
// Location: database/migrations/xxxx_xx_xx_create_abouts_table.php
// ============================================================
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('abouts', function (Blueprint $table) {
            $table->id();

            // Hero Section
            $table->string('hero_tag')->default('About us');
            $table->string('hero_title')->default("Building Tomorrow's Digital Frontier");
            $table->text('hero_subtitle')->nullable();

            // Mission Section
            $table->string('mission_title')->default('Our mission');
            $table->text('mission_text_1')->nullable();
            $table->text('mission_text_2')->nullable();

            // Stats / Numbers
            $table->string('stats_label')->default('THE NUMBERS');
            $table->string('stat_clients_num')->default('70+');
            $table->string('stat_clients_label')->default('Satisfied Clients');
            $table->string('stat_projects_num')->default('65+');
            $table->string('stat_projects_label')->default('Projects');
            $table->string('stat_satisfaction_num')->default('99.5%');
            $table->string('stat_satisfaction_label')->default('Satisfaction Rate');
            $table->string('stat_experience_num')->default('5+');
            $table->string('stat_experience_label')->default('Years of Experience');

            // Milestones / Awards Section headings
            $table->string('milestones_tag')->default('Milestones That Matter');
            $table->string('milestones_title')->default('Our Journey of Impact');
            $table->text('milestones_subtitle')->nullable();

            // Core Values Section headings
            $table->string('values_tag')->default('What Drives Us');
            $table->string('values_title')->default('Our Core Values');
            $table->text('values_subtitle')->nullable();

            // FAQ Section headings
            $table->string('faq_tag')->default('FAQ');
            $table->string('faq_title')->default('Frequently Asked Questions');
            $table->text('faq_subtitle')->nullable();

            // Team Photos (right-column collage)
            $table->string('photo_1')->nullable(); // wide top photo
            $table->string('photo_2')->nullable(); // bottom-left photo
            $table->string('photo_3')->nullable(); // bottom-right photo

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('abouts');
    }
};