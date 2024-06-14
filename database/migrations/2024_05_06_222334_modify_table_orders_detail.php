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
        //
        Schema::table('order_details', function (Blueprint $table) {
            if (Schema::hasColumn('order_details', 'nft_id')) {
                $table->dropColumn('nft_id');
            }

            if (Schema::hasColumn('order_details', 'price')) {
                $table->dropColumn('price');
            }

            if (!Schema::hasColumn('order_details', 'price_history_id')) {
                $table->foreignUuid('price_history_id')->references('id')->on('price_histories');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('order_details', function (Blueprint $table) {
            $table->dropColumn('price_history_id');
        });
    }
};
