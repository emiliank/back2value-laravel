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
        Schema::create('diagnostic_reports', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('battery_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->float('initial_voltage');
            $table->float('internal_resistance');
            $table->unsignedInteger('expected_capacity');
            $table->unsignedInteger('actual_capacity');
            $table->boolean('is_reactivation_eligible')->default(false);
            $table->text('notes')->nullable();
            $table->timestamp('tested_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diagnostic_reports');
    }
};
