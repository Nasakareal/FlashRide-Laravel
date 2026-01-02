<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();

            $table->foreignId('created_by_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->foreignId('assigned_to_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->string('context_type')->nullable();
            $table->unsignedBigInteger('context_id')->nullable();

            $table->string('subject')->nullable();

            $table->enum('status', [
                'open',
                'assigned',
                'pending_user',
                'resolved',
                'closed',
            ])->default('open');

            $table->enum('priority', [
                'low',
                'normal',
                'high',
            ])->default('normal');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tickets');
    }
};
