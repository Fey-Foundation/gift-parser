<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('tg_users', function (Blueprint $table) {
            $table->id();
            $table->string('telegram_username')->nullable();
            $table->string('name')->nullable();
            $table->bigInteger('telegram_id')->unique();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tg_user');
    }
};
