<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriversTable extends Migration
{
    public function up()
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();

            $table->string('license_number', 100)->nullable();
            $table->date('license_expires_at')->nullable();
            $table->string('curp', 18)->nullable();
            $table->string('rfc', 13)->nullable();
            $table->date('birthdate')->nullable();

            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();

            $table->text('address')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('drivers');
    }
}
