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
        Schema::create('nfts', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->timestamp("starting_date");
            $table->timestamp("expiration_date");
            $table->longText("description");
            $table->longText("image_url");
            $table->boolean("is_active")->nullable();
            $table->bigInteger("view")->default(0);
            $table->foreignUuid('collection_id')->references('id')->on('collections');
            $table->foreignUuid('price_history_id')->references('id')->on('price_histories');
            $table->foreignUuid("user_owner_id")->references("id")->on("users");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nfts');
    }
};
