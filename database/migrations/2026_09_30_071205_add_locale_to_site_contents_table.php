<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('site_contents', function (Blueprint $table): void {
            $table->string('locale', 5)->default('sq')->after('section');
        });

        // Existing rows predate localisation and hold the Albanian copy.
        DB::table('site_contents')->update(['locale' => 'sq']);

        Schema::table('site_contents', function (Blueprint $table): void {
            $table->dropUnique('site_contents_section_unique');
            $table->unique(['section', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_contents', function (Blueprint $table): void {
            $table->dropUnique('site_contents_section_locale_unique');
            $table->dropColumn('locale');
        });
    }
};
