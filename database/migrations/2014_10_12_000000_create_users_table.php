<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('name')->nullable();
            $table->string('lastname')->nullable();
            $table->string('age')->nullable();
            $table->string('nickname')->nullable();
            $table->string('player_team')->nullable()->default('Cagliari Calcio');
            $table->string('player_name')->nullable()->default(0);
            $table->string('player_shirt')->nullable()->default('cag-away');
            $table->integer('score')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
