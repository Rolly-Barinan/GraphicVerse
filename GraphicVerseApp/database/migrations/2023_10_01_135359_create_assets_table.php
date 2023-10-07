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
            $table->string('AssetName');
            $table->string('FileType');
            $table->unsignedBigInteger('FileSize');
            $table->string('Location'); 
            $table->unsignedBigInteger('PackageID'); 
            $table->unsignedBigInteger('UserID');
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
        Schema::dropIfExists('assets');
    }
};
