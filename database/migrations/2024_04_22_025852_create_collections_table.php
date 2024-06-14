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
        Schema::create('collections', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->longText("url");
            $table->timestamp("starting_date");
            $table->timestamp("expiration_date");
            $table->longText("description");
            $table->string("price");
            $table->longText("logo_image_url");
            $table->longText("featured_image_url");
            $table->longText("cover_image_url");
            $table->boolean("is_active")->nullable();
            $table->foreignUuid('category_id')->references('id')->on('collection_categories');
            $table->foreignUuid('user_id')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collections');
    }
};
