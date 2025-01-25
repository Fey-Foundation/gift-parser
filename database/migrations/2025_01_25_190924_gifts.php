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
        Schema::create('gifts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('image');
            $table->json('attributes');
            $table->string('lottie');
            $table->json('original_details');

            $table->foreignId('sender_user_id')->nullable()->constrained('tg_users')->cascadeOnDelete();
            $table->foreignId('recipient_user_id')->nullable()->constrained('tg_users')->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('gifts');
    }
};
