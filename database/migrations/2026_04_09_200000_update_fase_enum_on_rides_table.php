<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateFaseEnumOnRidesTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('rides') && Schema::hasColumn('rides', 'fase')) {
            DB::statement("ALTER TABLE rides MODIFY fase ENUM('esperando','recogiendo','viajando','completado','cancelado') NOT NULL DEFAULT 'esperando'");
        }
    }

    public function down()
    {
        if (Schema::hasTable('rides') && Schema::hasColumn('rides', 'fase')) {
            DB::statement("ALTER TABLE rides MODIFY fase ENUM('esperando','recogiendo','viajando','completado') NOT NULL DEFAULT 'esperando'");
        }
    }
}
