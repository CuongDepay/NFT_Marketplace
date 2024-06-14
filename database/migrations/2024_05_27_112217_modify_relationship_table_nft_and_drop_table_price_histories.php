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
        Schema::table("order_details", function (Blueprint $table) {
            $table->dropColumn('price_history_id');
            $table->foreignUuid('nft_id')->references('id')->on('nfts');
        });

        Schema::dropIfExists('price_histories');

        Schema::table("nfts", function (Blueprint $table) {
            $table->float('price')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::create('price_histories', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->string("price");
            $table->timestamps();
            $table->foreignUuid('nft_id')->references('id')->on('nfts');
            $table->softDeletes();
        });

        Schema::table("order_details", function (Blueprint $table) {
            $table->foreignUuid('price_history_id')->references('id')->on('price_histories');
            $table->dropColumn('nft_id');
        });


        Schema::table("nfts", function (Blueprint $table) {
            $table->dropColumn('price');
        });
    }
};
