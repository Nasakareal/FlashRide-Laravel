<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->string('full_name_ine')->nullable()->after('user_id');
            $table->string('birth_place')->nullable()->after('birthdate');
            $table->string('mother_full_name')->nullable()->after('birth_place');
            $table->string('father_full_name')->nullable()->after('mother_full_name');
            $table->string('reference')->nullable()->after('notes');
        });
    }

    public function down(): void
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->dropColumn([
                'full_name_ine',
                'birth_place',
                'mother_full_name',
                'father_full_name',
                'reference',
            ]);
        });
    }
};
