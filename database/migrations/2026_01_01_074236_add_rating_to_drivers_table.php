<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRatingToDriversTable extends Migration
{
    public function up()
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->decimal('rating_avg', 3, 2)->default(0)->after('is_verified');
            $table->unsignedInteger('rating_count')->default(0)->after('rating_avg');
        });
    }

    public function down()
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->dropColumn(['rating_avg', 'rating_count']);
        });
    }
}
