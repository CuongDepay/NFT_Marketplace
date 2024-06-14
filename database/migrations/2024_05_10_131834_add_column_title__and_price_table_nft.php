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
        Schema::table('nfts', function (Blueprint $table) {
            $table->string("price")->nullable()->after('view');
            $table->string("title")->nullable()->after('price');
            $table->bigInteger("quantity")->nullable();
            $table->dropColumn('user_owner_id');
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
