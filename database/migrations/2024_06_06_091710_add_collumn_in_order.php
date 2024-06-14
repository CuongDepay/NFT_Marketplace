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
        Schema::table('orders', function (Blueprint $table) {
            $table->string("company_name")->nullable();
            $table->string("order_notes")->nullable();
            $table->uuid("coupon_id")->nullable()->change();
            $table->string("shipping")->nullable()->change();
            $table->string("tax")->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn("company_name");
            $table->dropColumn("order_notes");
        });
    }
};
