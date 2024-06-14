<?php

use App\Enums\Notification\Type;
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
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->longText("content");
            $table->boolean("is_read")->default(false);
            $table->text("title");
            $table->longText("message");
            $table->foreignUuid('sender_id')->references('id')->on('users');
            $table->foreignUuid("notification_category_id")->references("id")->on("notification_categories");
            $table->foreignUuid('receiver_id')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
