<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected function redirectTo()
    {
        return route('dashboard');
    }

    /**
     * Hook que se ejecuta APENAS se autentica el usuario.
     * Si no tiene rol admin, cerrar sesión y regresar al login con error.
     */
    protected function authenticated(Request $request, $user)
    {
        if (!$user->hasRole('admin')) {
            $this->guard()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->withErrors(['email' => 'Solo administradores pueden iniciar sesión.']);
        }

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
