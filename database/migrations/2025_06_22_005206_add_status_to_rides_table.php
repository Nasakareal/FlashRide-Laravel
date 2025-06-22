<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToRidesTable extends Migration
{
    public function up()
    {
        Schema::table('rides', function (Blueprint $table) {
            $table->enum('fase', ['esperando', 'recogiendo', 'viajando', 'completado'])->default('esperando');
        });
    }

    public function down()
    {
        Schema::table('rides', function (Blueprint $table) {
            $table->dropColumn('fase');
        });
    }
}
