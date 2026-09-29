<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Stock is tracked separately from is_available: is_available decides whether
     * a product is listed at all, stock_status describes whether it can be
     * supplied now or only on pre-order.
     */
    public function up(): void
    {
        Schema::table('batteries', function (Blueprint $table): void {
            $table->enum('stock_status', ['in_stock', 'low_stock', 'on_preorder', 'out_of_stock'])
                ->default('in_stock')
                ->after('is_available')
                ->index();
        });

        DB::table('batteries')
            ->where('is_available', false)
            ->update(['stock_status' => 'out_of_stock']);

        // Out-of-stock products stay in the catalogue so customers can find them
        // and place a pre-order, so they must no longer be hidden from the site.
        DB::table('batteries')->update(['is_available' => true]);
    }

    public function down(): void
    {
        Schema::table('batteries', function (Blueprint $table): void {
            $table->dropIndex(['stock_status']);
            $table->dropColumn('stock_status');
        });
    }
};
