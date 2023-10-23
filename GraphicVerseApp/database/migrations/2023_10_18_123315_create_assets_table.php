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
        Schema::create('assets', function (Blueprint $table) {

            $table->id('AssetID');
            $table->unsignedBigInteger('PackageID'); 
            $table->unsignedBigInteger('UserID');
            $table->string('AssetName');
            $table->string('FileType');
            $table->unsignedBigInteger('FileSize');
            $table->string('Location');

            $table->timestamps();
    
            $table->foreign('PackageID')->references('id')->on('packages')->onDelete('cascade');
            $table->foreign('UserID')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assets');
    }
};
