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
        Schema::create('user3_d_s', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('threeD_id');
            $table->unsignedBigInteger('user_id');

            $table->foreign('threeD_id')->references('id')->on('model3_d_s')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('user3_d_s');
    }
};
