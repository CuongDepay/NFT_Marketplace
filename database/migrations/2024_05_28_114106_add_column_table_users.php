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
        Schema::table('users', function (Blueprint $table) {
            $table->text("avatar")->nullable();
            $table->text("background")->nullable();
            $table->string("country")->nullable();
            $table->string("state")->nullable();
            $table->string("city")->nullable();
            $table->string("zip_code")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn("avatar");
            $table->dropColumn("background");
            $table->dropColumn("country");
            $table->dropColumn("state");
            $table->dropColumn("city");
            $table->dropColumn("zip_code");
        });
    }
};
