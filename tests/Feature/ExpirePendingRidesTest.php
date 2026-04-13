<?php

namespace Tests\Feature;

use App\Models\Ride;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class ExpirePendingRidesTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_cancels_pending_rides_that_are_older_than_the_timeout()
    {
        config(['rides.pending_timeout_minutes' => 10]);

        $passenger = User::factory()->create();
        $recentRide = Ride::create([
            'passenger_id' => $passenger->id,
            'start_lat' => 19.4326080,
            'start_lng' => -99.1332090,
            'end_lat' => 19.4270250,
            'end_lng' => -99.1676650,
            'estimated_cost' => 100,
            'status' => 'pending',
            'fase' => 'esperando',
            'created_at' => Carbon::now()->subMinutes(9),
            'updated_at' => Carbon::now()->subMinutes(9),
        ]);

        $expiredRide = Ride::create([
            'passenger_id' => $passenger->id,
            'start_lat' => 19.4326080,
            'start_lng' => -99.1332090,
            'end_lat' => 19.4270250,
            'end_lng' => -99.1676650,
            'estimated_cost' => 100,
            'status' => 'pending',
            'fase' => 'esperando',
            'created_at' => Carbon::now()->subMinutes(11),
            'updated_at' => Carbon::now()->subMinutes(11),
        ]);

        $this->artisan('rides:expire-pending')
            ->expectsOutput('Solicitudes vencidas canceladas: 1')
            ->assertExitCode(0);

        $this->assertDatabaseHas('rides', [
            'id' => $recentRide->id,
            'status' => 'pending',
            'fase' => 'esperando',
        ]);

        $this->assertDatabaseHas('rides', [
            'id' => $expiredRide->id,
            'status' => 'cancelled',
            'fase' => 'cancelado',
        ]);
    }
}
