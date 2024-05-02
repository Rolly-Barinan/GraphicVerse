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
        Schema::create('image_assets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('userID');
            $table->unsignedBigInteger('assetTypeID');
            $table->string('ImageName');
            $table->text('ImageDescription')->nullable();
            $table->string('Location');
            $table->decimal('Price', 10, 2);
            $table->string('ImageSize');
            $table->string('watermarkedImage')->nullable();
            $table->timestamps();

            $table->foreign('userID')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('assetTypeID')->references('id')->on('asset_types')->onDelete('cascade');
            $table->foreignId('team_id')->nullable()->constrained('teams')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('image_assets');
    }
};
