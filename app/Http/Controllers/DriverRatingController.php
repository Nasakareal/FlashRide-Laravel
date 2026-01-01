<?php

namespace App\Http\Controllers;

use App\Models\DriverRating;
use App\Models\User;
use Illuminate\Http\Request;

class DriverRatingController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'driver_id' => ['required','exists:users,id'],
            'trip_id'   => ['required','exists:trips,id'],
            'rating'    => ['required','integer','min:1','max:5'],
            'comment'   => ['nullable','string','max:500'],
        ]);

        $rating = DriverRating::create([
            'driver_id' => $data['driver_id'],
            'user_id'   => auth()->id(),
            'trip_id'   => $data['trip_id'],
            'rating'    => $data['rating'],
            'comment'   => $data['comment'] ?? null,
        ]);

        $driver = User::findOrFail($data['driver_id']);
        $profile = $driver->driverProfile;

        if ($profile) {
            $newCount = $profile->rating_count + 1;

            $profile->rating_avg = (
                ($profile->rating_avg * $profile->rating_count) + $data['rating']
            ) / $newCount;

            $profile->rating_count = $newCount;
            $profile->save();
        }

        return back()->with('status', 'Gracias por calificar al conductor.');
    }
}
