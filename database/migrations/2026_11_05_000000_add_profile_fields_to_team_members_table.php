<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('team_members', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('name');
            $table->json('expertise')->nullable()->after('bio');
            $table->json('education')->nullable()->after('expertise');
            $table->json('achievements')->nullable()->after('education');
            $table->json('gallery')->nullable()->after('achievements');
            $table->string('facebook')->nullable()->after('instagram');
            $table->string('github')->nullable()->after('facebook');
            $table->string('youtube')->nullable()->after('github');
            $table->string('whatsapp')->nullable()->after('youtube');
            $table->string('website')->nullable()->after('whatsapp');
        });

        $used = [];
        foreach (DB::table('team_members')->orderBy('id')->get(['id', 'name']) as $member) {
            $base = Str::slug($member->name) ?: 'member';
            $slug = $base;
            for ($i = 2; in_array($slug, $used, true); $i++) {
                $slug = $base.'-'.$i;
            }
            $used[] = $slug;
            DB::table('team_members')->where('id', $member->id)->update(['slug' => $slug]);
        }
    }

    public function down(): void
    {
        Schema::table('team_members', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn(['slug', 'expertise', 'education', 'achievements', 'gallery', 'facebook', 'github', 'youtube', 'whatsapp', 'website']);
        });
    }
};
