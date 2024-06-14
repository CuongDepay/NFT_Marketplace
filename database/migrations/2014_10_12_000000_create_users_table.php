<?php

use App\Enums\User\Gender;
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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->string('name', 60);
            $table->enum("gender", Gender::getValues());
            $table->string('email')->unique();
            $table->text("custom_url")->nullable();
            $table->string("phone_number", 20)->unique()->nullable();
            $table->text("address")->nullable();
            $table->longText("introduce")->nullable();
            $table->string("password")->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
