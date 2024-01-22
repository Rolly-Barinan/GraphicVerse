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
        Schema::create('image_category', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('imageAsset_id');
            $table->unsignedBigInteger('category_id');
            $table->timestamps();

            $table->foreign('imageAsset_id')->references('id')->on('image_assets')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('image_category');
    }
};
