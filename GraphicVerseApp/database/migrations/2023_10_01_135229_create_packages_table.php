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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('PackageName');
            $table->text('Description')->nullable();
            $table->string('preview');
            $table->string('Location');
            $table->decimal('Price', 10, 2)->nullable();
                
            $table->unsignedBigInteger('UserID');
            $table->timestamps();
            
   
        });
    }
    /**
     * Reverse the migrations.
     * 
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('packages');
    }
};
