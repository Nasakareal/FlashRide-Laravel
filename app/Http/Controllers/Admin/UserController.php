<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * GET /flashride/admin/users
     * Lista con búsqueda y filtro por rol (Spatie).
     */
    public function index(Request $request)
    {
        $q    = trim((string) $request->input('q'));
        $role = trim((string) $request->input('role')); // admin|driver|passenger (Spatie)

        $users = User::query()
            ->when($q, function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    $qq->where('name', 'like', "%{$q}%")
                       ->orWhere('email', 'like', "%{$q}%")
                       ->orWhere('phone', 'like', "%{$q}%");
                });
            })
            ->when($role, function ($query) use ($role) {
                // Filtro por rol de Spatie
                $query->role($role);
            })
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        // Para dibujar filtros en la vista
        $roles = Role::query()->orderBy('name')->pluck('name')->all();

        return view('admin.users.index', compact('users', 'roles', 'q', 'role'));
    }

    /**
     * GET /flashride/admin/users/create
     */
    public function create()
    {
        $roles = Role::query()->orderBy('name')->pluck('name')->all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * POST /flashride/admin/users
     */
    public function store(Request $request)
    {
        $rolesValidos = Role::query()->pluck('name')->all();
        // Si aún no creaste los roles en DB, garantizamos el set por defecto:
        if (empty($rolesValidos)) {
            $rolesValidos = ['admin','driver','passenger'];
        }

        $data = $request->validate([
            'name'     => ['required','string','max:191'],
            'email'    => ['required','email','max:191','unique:users,email'],
            'phone'    => ['nullable','string','max:50'],
            'password' => ['required','string','min:6'],
            'role'     => ['required', Rule::in($rolesValidos)],
        ]);

        $user = new User();
        $user->name     = $data['name'];
        $user->email    = $data['email'];
        $user->phone    = $data['phone'] ?? null;
        $user->password = Hash::make($data['password']);

        // Mantén sincronizada tu columna legacy "role" (no estorba con Spatie)
        if (isset($user->role)) {
            $user->role = $data['role'];
        }

        $user->save();

        // Asignar rol real (Spatie)
        $user->syncRoles([$data['role']]);

        return redirect()
            ->route('admin.users.index')
            ->with('status', "Usuario {$user->name} creado correctamente.");
    }

    /**
     * GET /flashride/admin/users/{user}
     */
    public function show(User $user)
    {
        // Si quieres mostrar roles en la vista:
        $roleNames = $user->getRoleNames();
        return view('admin.users.show', compact('user','roleNames'));
    }

    /**
     * GET /flashride/admin/users/{user}/edit
     */
    public function edit(User $user)
    {
        $roles = Role::query()->orderBy('name')->pluck('name')->all();
        return view('admin.users.edit', compact('user','roles'));
    }

    /**
     * PUT/PATCH /flashride/admin/users/{user}
     */
    public function update(Request $request, User $user)
    {
        $rolesValidos = Role::query()->pluck('name')->all();
        if (empty($rolesValidos)) {
            $rolesValidos = ['admin','driver','passenger'];
        }

        $data = $request->validate([
            'name'     => ['required','string','max:191'],
            'email'    => ['required','email','max:191', Rule::unique('users','email')->ignore($user->id)],
            'phone'    => ['nullable','string','max:50'],
            'password' => ['nullable','string','min:6'],
            'role'     => ['required', Rule::in($rolesValidos)],
        ]);

        $user->name  = $data['name'];
        $user->email = $data['email'];
        $user->phone = $data['phone'] ?? null;

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        // Mantén sincronizada tu columna legacy "role"
        if (isset($user->role)) {
            $user->role = $data['role'];
        }

        $user->save();

        // Sincroniza roles Spatie
        $user->syncRoles([$data['role']]);

        return redirect()
            ->route('admin.users.edit', $user)
            ->with('status', "Usuario {$user->name} actualizado.");
    }

    /**
     * DELETE /flashride/admin/users/{user}
     */
    public function destroy(User $user)
    {
        // Evita que un admin se borre a sí mismo
        if (auth()->id() === $user->id) {
            return back()->withErrors(['user' => 'No puedes eliminar tu propio usuario.']);
        }

        $name = $user->name;

        // Limpia roles Spatie antes de borrar
        $user->syncRoles([]);

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('status', "Usuario {$name} eliminado.");
    }
}
