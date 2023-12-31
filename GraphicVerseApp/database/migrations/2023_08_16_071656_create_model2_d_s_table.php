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
        Schema::create('model2_d_s', function (Blueprint $table) {
            $table->id();
            $table->string('twoD_name');
            $table->string('description');
            $table->string('cat_name');
            $table->string('creator_username');
            $table->string('filename');
            $table->string('image_type');
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
        Schema::dropIfExists('model2_d_s');
    }
};
