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
        Schema::create('model3_d_s', function (Blueprint $table) {
            $table->id();
            $table->string('threeD_name');
            $table->string('description');
            $table->string('cat_name');
            $table->string('filename');
            $table->string('creator_username');
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
        Schema::dropIfExists('model3_d_s');
    }
};
