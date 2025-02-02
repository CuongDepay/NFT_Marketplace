<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //
        Schema::table('nfts', function (Blueprint $table) {
            $table->dropColumn('price');
        });

        Schema::table('price_histories', function (Blueprint $table) {
            $table->foreignUuid('nft_id')->references('id')->on('nfts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
