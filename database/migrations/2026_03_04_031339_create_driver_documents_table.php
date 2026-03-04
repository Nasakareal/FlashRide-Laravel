<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('driver_documents', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('driver_id');

            $table->string('type', 60);

            $table->string('file_path');
            $table->string('original_name')->nullable();
            $table->string('mime', 120)->nullable();
            $table->unsignedBigInteger('size')->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamp('uploaded_at')->nullable();

            $table->timestamp('verified_at')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->foreign('driver_id')->references('id')->on('drivers')->onDelete('cascade');

            $table->unique(['driver_id', 'type', 'is_active'], 'driver_docs_unique_active');
            $table->index(['driver_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('driver_documents');
    }
};
