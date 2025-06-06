<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRatingAndPanicToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('rating', 3, 2)->default(0)->after('phone');
            $table->boolean('ever_pressed_panic')->default(false)->after('rating');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['rating', 'ever_pressed_panic']);
        });
    }

}
