<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        return view('profile.show', [
            'user' => $request->user(),
        ]);
    }

    public function editPassword(Request $request)
    {
        return view('profile.password', [
            'user' => $request->user(),
        ]);
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate(
            [
                'current_password' => ['required', 'string'],
                'password' => ['required', 'string', 'min:8', 'confirmed', 'different:current_password'],
            ],
            [
                'current_password.required' => 'Escribe tu contraseña actual.',
                'password.required' => 'Escribe la nueva contraseña.',
                'password.min' => 'La nueva contraseña debe tener al menos 8 caracteres.',
                'password.confirmed' => 'La confirmación de la contraseña no coincide.',
                'password.different' => 'La nueva contraseña debe ser diferente a la actual.',
            ]
        );

        $user = $request->user();

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()
                ->withErrors(['current_password' => 'La contraseña actual no es correcta.'])
                ->withInput();
        }

        $user->forceFill([
            'password' => Hash::make($validated['password']),
        ])->save();

        return redirect()
            ->route('profile.password.edit')
            ->with('status', 'Tu contraseña se actualizó correctamente.');
    }
}
