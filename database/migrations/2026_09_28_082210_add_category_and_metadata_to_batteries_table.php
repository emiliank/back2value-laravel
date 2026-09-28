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
        Schema::table('batteries', function (Blueprint $table): void {
            $table->string('category')->nullable()->index()->after('brand');
            $table->string('model')->nullable()->after('category');
            $table->string('voltage')->nullable()->after('capacity_ah');
            $table->string('technology')->nullable()->after('voltage');
            $table->text('description')->nullable()->after('technology');
            $table->json('specs')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('batteries', function (Blueprint $table): void {
            $table->dropColumn(['category', 'model', 'voltage', 'technology', 'description', 'specs']);
        });
    }
};
