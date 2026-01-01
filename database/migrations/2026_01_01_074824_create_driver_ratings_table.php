<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriverRatingsTable extends Migration
{
    public function up()
    {
        Schema::create('driver_ratings', function (Blueprint $table) {
            $table->id();

            // conductor (users.id)
            $table->unsignedBigInteger('driver_id');

            // pasajero que califica (users.id)
            $table->unsignedBigInteger('user_id');

            // viaje relacionado
            $table->unsignedBigInteger('trip_id')->nullable();

            $table->unsignedTinyInteger('rating'); // 1–5
            $table->text('comment')->nullable();

            $table->timestamps();

            $table->foreign('driver_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('trip_id')->references('id')->on('trips')->onDelete('set null');

            // evita doble calificación del mismo viaje
            $table->unique(['driver_id', 'user_id', 'trip_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('driver_ratings');
    }
}
