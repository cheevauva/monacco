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
        Schema::create('telegram_message', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('update_id');
            $table->string('message_id');
            $table->string('chat_id');
            $table->string('reply_message_id')->nullable();
            $table->json('cheque');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('telegram_message');
    }
};
