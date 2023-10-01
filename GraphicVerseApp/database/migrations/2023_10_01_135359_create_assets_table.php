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
            $table->id();
            $table->string('AssetName');
            $table->text('Description')->nullable();
            $table->string('FileType');
            $table->unsignedBigInteger('FileSize');
            $table->string('Location'); // Path to the stored asset file
            $table->unsignedBigInteger('PackageID'); // Foreign key to link to the Package model
            $table->unsignedBigInteger('UserID'); // Foreign key to link to the User model
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
