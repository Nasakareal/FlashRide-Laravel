<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehicles', function (Blueprint $t) {
            if (!Schema::hasColumn('vehicles', 'vehicle_type')) {
                $t->string('vehicle_type')->default('combi')->after('user_id');
            }
            if (!Schema::hasColumn('vehicles', 'transit_route_id')) {
                $t->unsignedBigInteger('transit_route_id')->nullable()->after('vehicle_type');
                $t->index('transit_route_id');
                // Si conviertes a InnoDB y quieres FK:
                // $t->foreign('transit_route_id')->references('id')->on('transit_routes')->nullOnDelete();
            }
            if (!Schema::hasColumn('vehicles', 'last_lat')) {
                $t->decimal('last_lat', 10, 7)->nullable()->after('transit_route_id');
            }
            if (!Schema::hasColumn('vehicles', 'last_lng')) {
                $t->decimal('last_lng', 10, 7)->nullable()->after('last_lat');
            }
            if (!Schema::hasColumn('vehicles', 'last_bearing')) {
                $t->unsignedSmallInteger('last_bearing')->nullable()->after('last_lng');
            }
            if (!Schema::hasColumn('vehicles', 'last_speed_kph')) {
                $t->unsignedSmallInteger('last_speed_kph')->nullable()->after('last_bearing');
            }
            if (!Schema::hasColumn('vehicles', 'last_located_at')) {
                $t->timestamp('last_located_at')->nullable()->after('last_speed_kph');
            }
        });
    }

    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $t) {
            // Si creaste FK, primero quítala:
            // $t->dropForeign(['transit_route_id']);
            foreach ([
                'vehicle_type',
                'transit_route_id',
                'last_lat',
                'last_lng',
                'last_bearing',
                'last_speed_kph',
                'last_located_at'
            ] as $c) {
                if (Schema::hasColumn('vehicles', $c)) {
                    $t->dropColumn($c);
                }
            }
        });
    }
};
