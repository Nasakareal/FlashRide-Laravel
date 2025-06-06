<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('driver_id');
            $table->string('origin');
            $table->string('destination');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            // otros campos según tu modelo de viajes...
            $table->timestamps();

            $table->foreign('driver_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('trips');
    }
};
