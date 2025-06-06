<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DriverController extends Controller
{
    /**
     * Mostrar detalles adicionales de un chofer:
     * total de viajes (trips_count), rating y ever_pressed_panic.
     */
    public function details($id)
    {
        // Busca el usuario con role = 'driver'
        $driver = User::where('role', 'driver')
                      ->withCount('trips') // trips_count
                      ->findOrFail($id);

        return response()->json([
            'total_trips'        => $driver->trips_count,
            'rating'             => $driver->rating,
            'ever_pressed_panic' => (bool) $driver->ever_pressed_panic,
        ], 200);
    }

    /**
     * Actualizar datos de un chofer existente.
     * Campos permitidos: name, email, phone.
     */
    public function update(Request $request, $id)
    {
        // Validación de los datos entrantes
        $request->validate([
            'name'  => 'required|string|max:191',
            'email' => 'required|email|unique:users,email,'.$id,
            'phone' => 'required|string|max:191',
        ]);

        // Asegurarse de que el usuario existe y es un driver
        $driver = User::where('role', 'driver')->findOrFail($id);

        $driver->name  = $request->input('name');
        $driver->email = $request->input('email');
        $driver->phone = $request->input('phone');
        $driver->save();

        return response()->json($driver, 200);
    }

    /**
     * Eliminar un chofer (user con role = 'driver').
     */
    public function destroy($id)
    {
        $driver = User::where('role', 'driver')->findOrFail($id);
        $driver->delete();

        return response()->json([
            'message' => 'Chofer eliminado correctamente.'
        ], 200);
    }
}
