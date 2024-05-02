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
            $table->unsignedBigInteger('UserID');
            $table->string('PackageName');
            $table->text('Description')->nullable();
            $table->string('preview');
            $table->string('Location');
            $table->decimal('Price', 10, 2)->nullable();
            $table->unsignedBigInteger('asset_type_id')->nullable(); // Foreign key column
            $table->timestamps();
            $table->foreign('UserID')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('asset_type_id')->references('id')->on('asset_types')->onDelete('set null');
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
        Schema::dropIfExists('packages');
    }
};
