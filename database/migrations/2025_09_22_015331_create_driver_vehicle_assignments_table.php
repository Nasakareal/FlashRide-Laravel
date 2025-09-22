<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('driver_vehicle_assignments', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->foreignId('driver_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('vehicle_id')->constrained('vehicles')->cascadeOnDelete();
            $table->dateTime('started_at')->useCurrent();
            $table->dateTime('ended_at')->nullable();
            $table->boolean('active')->default(true);
            $table->string('notes', 255)->nullable();
            $table->timestamps();
            $table->index(['driver_id', 'active']);
            $table->index(['vehicle_id', 'active']);
        });

        DB::unprepared("
            CREATE TRIGGER trg_dva_no_dupe_vehicle_ins
            BEFORE INSERT ON driver_vehicle_assignments
            FOR EACH ROW
            BEGIN
                IF NEW.active = 1 AND NEW.ended_at IS NULL THEN
                    IF EXISTS (
                        SELECT 1 FROM driver_vehicle_assignments
                        WHERE vehicle_id = NEW.vehicle_id
                          AND active = 1
                          AND ended_at IS NULL
                    ) THEN
                        SIGNAL SQLSTATE '45000'
                            SET MESSAGE_TEXT = 'El vehículo ya tiene una asignación activa';
                    END IF;
                END IF;
            END
        ");

        DB::unprepared("
            CREATE TRIGGER trg_dva_no_dupe_vehicle_upd
            BEFORE UPDATE ON driver_vehicle_assignments
            FOR EACH ROW
            BEGIN
                IF NEW.active = 1 AND NEW.ended_at IS NULL THEN
                    IF EXISTS (
                        SELECT 1 FROM driver_vehicle_assignments
                        WHERE vehicle_id = NEW.vehicle_id
                          AND active = 1
                          AND ended_at IS NULL
                          AND id <> OLD.id
                    ) THEN
                        SIGNAL SQLSTATE '45000'
                            SET MESSAGE_TEXT = 'El vehículo ya tiene una asignación activa';
                    END IF;
                END IF;
            END
        ");

        DB::unprepared("
            CREATE TRIGGER trg_dva_no_dupe_driver_ins
            BEFORE INSERT ON driver_vehicle_assignments
            FOR EACH ROW
            BEGIN
                IF NEW.active = 1 AND NEW.ended_at IS NULL THEN
                    IF EXISTS (
                        SELECT 1 FROM driver_vehicle_assignments
                        WHERE driver_id = NEW.driver_id
                          AND active = 1
                          AND ended_at IS NULL
                    ) THEN
                        SIGNAL SQLSTATE '45000'
                            SET MESSAGE_TEXT = 'El conductor ya tiene una asignación activa';
                    END IF;
                END IF;
            END
        ");

        DB::unprepared("
            CREATE TRIGGER trg_dva_no_dupe_driver_upd
            BEFORE UPDATE ON driver_vehicle_assignments
            FOR EACH ROW
            BEGIN
                IF NEW.active = 1 AND NEW.ended_at IS NULL THEN
                    IF EXISTS (
                        SELECT 1 FROM driver_vehicle_assignments
                        WHERE driver_id = NEW.driver_id
                          AND active = 1
                          AND ended_at IS NULL
                          AND id <> OLD.id
                    ) THEN
                        SIGNAL SQLSTATE '45000'
                            SET MESSAGE_TEXT = 'El conductor ya tiene una asignación activa';
                    END IF;
                END IF;
            END
        ");
    }

    public function down(): void
    {
        try { DB::unprepared('DROP TRIGGER trg_dva_no_dupe_vehicle_ins'); } catch (\Throwable $e) {}
        try { DB::unprepared('DROP TRIGGER trg_dva_no_dupe_vehicle_upd'); } catch (\Throwable $e) {}
        try { DB::unprepared('DROP TRIGGER trg_dva_no_dupe_driver_ins'); } catch (\Throwable $e) {}
        try { DB::unprepared('DROP TRIGGER trg_dva_no_dupe_driver_upd'); } catch (\Throwable $e) {}

        Schema::dropIfExists('driver_vehicle_assignments');
    }
};
