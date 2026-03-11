<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // Media library
        Schema::create('media_files', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->string('original_name');
            $table->string('path');
            $table->string('disk')->default('public');
            $table->string('mime_type');
            $table->unsignedBigInteger('size'); // bytes
            $table->string('alt_text')->nullable();
            $table->string('folder')->default('uploads');
            $table->timestamps();
        });

        // Page analytics
        Schema::create('page_views', function (Blueprint $table) {
            $table->id();
            $table->string('url');
            $table->string('page_title')->nullable();
            $table->string('ip')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('referer')->nullable();
            $table->string('country')->nullable();
            $table->timestamps();
        });

        // Audit / Lead capture
        Schema::create('audit_leads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('website_url')->nullable();
            $table->string('business_type')->nullable();
            $table->string('budget_range')->nullable();
            $table->text('goals')->nullable();
            $table->json('services_needed')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('media_files');
        Schema::dropIfExists('page_views');
        Schema::dropIfExists('audit_leads');
    }
};