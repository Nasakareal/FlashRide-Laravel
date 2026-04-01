<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->merge([
            'name' => $request->filled('name') ? trim($request->name) : null,
            'email' => $request->filled('email') ? strtolower(trim($request->email)) : null,
            'phone' => $request->filled('phone') ? trim($request->phone) : null,
        ]);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20|unique:users,phone',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validación fallida',
                'errors' => $validator->errors(),
                'input' => $request->all(),
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'passenger',
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    public function login(Request $request)
    {
        $request->merge([
            'email' => $request->filled('email') ? strtolower(trim($request->email)) : null,
            'phone' => $request->filled('phone') ? trim($request->phone) : null,
            'password' => $request->filled('password') ? trim($request->password) : null,
        ]);

        $request->validate([
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'password' => 'required|string',
        ]);

        $user = null;

        if ($request->filled('email')) {
            $user = User::whereRaw('LOWER(email) = ?', [$request->email])->first();
        } elseif ($request->filled('phone')) {
            $user = User::where('phone', $request->phone)->first();
        }

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Credenciales inválidas'], 401);
        }

        if ($user->role === 'driver') {
            $user->update([
                'is_online' => true,
                'updated_at' => now(),
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function profile(Request $request)
    {
        return response()->json($request->user());
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'La contraseña actual no coincide'
            ], 403);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'message' => 'Contraseña actualizada con éxito'
        ]);
    }

    public function registerDriver(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $request->merge([
            'name' => $request->filled('name') ? trim($request->name) : null,
            'email' => $request->filled('email') ? strtolower(trim($request->email)) : null,
            'phone' => $request->filled('phone') ? trim($request->phone) : null,
        ]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|unique:users,phone',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => bcrypt($validated['password']),
            'role' => 'driver',
        ]);

        return response()->json([
            'message' => 'Chofer registrado correctamente.',
            'data' => $user,
        ], 201);
    }

    public function listDrivers()
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $drivers = User::where('role', 'driver')->get();
        return response()->json($drivers);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $request->merge([
            'name' => $request->filled('name') ? trim($request->name) : null,
            'phone' => $request->filled('phone') ? trim($request->phone) : null,
        ]);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20|unique:users,phone,' . $user->id,
        ]);

        $user->fill($data)->save();

        return response()->json([
            'message' => 'Perfil actualizado.',
            'user' => $user->fresh(),
        ]);
    }

    public function updateEmail(Request $request)
    {
        $user = $request->user();

        $request->merge([
            'email' => $request->filled('email') ? strtolower(trim($request->email)) : null,
        ]);

        $data = $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->id,
            'current_password' => 'required|string',
        ]);

        if (!Hash::check($data['current_password'], $user->password)) {
            return response()->json(['message' => 'La contraseña actual no coincide'], 403);
        }

        $user->email = $data['email'];
        $user->save();

        return response()->json([
            'message' => 'Correo actualizado.',
            'user' => $user->fresh(),
        ]);
    }
}
