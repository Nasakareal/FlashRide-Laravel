<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Emergency;
use Illuminate\Support\Facades\Auth;

class EmergencyController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'description' => 'nullable|string|max:255',
        ]);

        $emergency = Emergency::create([
            'user_id'    => Auth::id(),
            'lat'        => $request->lat,
            'lng'        => $request->lng,
            'description'=> $request->description,
        ]);

        return response()->json([
            'message'   => 'Emergency registered',
            'data'      => $emergency
        ], 201);
    }
}
