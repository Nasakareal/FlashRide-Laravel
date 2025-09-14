<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transit_routes', function (Blueprint $t) {
            $t->bigIncrements('id');
            $t->string('short_name');
            $t->string('long_name')->nullable();
            $t->string('vehicle_type')->default('combi');
            $t->string('color', 6)->nullable();
            $t->string('text_color', 6)->nullable();
            $t->longText('polyline')->nullable();
            $t->longText('stops_json')->nullable();
            $t->tinyInteger('is_active')->default(1);
            $t->timestamps();

            $t->index(['short_name', 'vehicle_type', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transit_routes');
    }
};
