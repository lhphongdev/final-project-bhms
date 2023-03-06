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
        Schema::create('tb_date', function (Blueprint $table) {
            $table->id('date_id');
            $table->unsignedBigInteger('rental_room_id');
            $table->string('date')->unique();
            $table->foreign('rental_room_id')->references('rental_room_id')->on('tb_rental_room')->onUpdate('cascade')->onDelete('cascade');
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_date');
    }
};
