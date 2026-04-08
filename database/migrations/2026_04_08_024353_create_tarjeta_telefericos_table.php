<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTarjetaTelefericosTable extends Migration
{
    public function up()
    {
        Schema::create('tarjeta_telefericos', function (Blueprint $table) {
            $table->id();
            $table->string('nombres');
            $table->string('apellidos');
            $table->string('curp', 18)->unique();
            $table->string('celular', 20)->nullable();
            $table->string('folio_tarjeta')->unique();
            $table->enum('estatus', ['ACTIVA', 'INACTIVA', 'CANCELADA', 'REPOSICION'])->default('ACTIVA');
            $table->date('fecha_entrega')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->index('apellidos');
            $table->index('estatus');
            $table->index('fecha_entrega');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tarjeta_telefericos');
    }
}
