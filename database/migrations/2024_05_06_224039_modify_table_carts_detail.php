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
        Schema::table('cart_details', function (Blueprint $table) {
            if (Schema::hasColumn('cart_details', 'cart_id')) {
                $table->dropColumn(["cart_id"]);
            }
            if (!Schema::hasColumn('cart_details', 'user_id')) {
                $table->foreignUuid('user_id')->references('id')->on('users');
            }
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
