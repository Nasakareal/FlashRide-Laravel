<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $q    = trim((string) $request->input('q'));
        $role = trim((string) $request->input('role'));

        $users = User::query()
            ->when($q, function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    $qq->where('name', 'like', "%{$q}%")
                       ->orWhere('email', 'like', "%{$q}%")
                       ->orWhere('phone', 'like', "%{$q}%");
                });
            })
            ->when($role, function ($query) use ($role) {
                $rolesDb = Role::query()->pluck('name')->all();

                if (!empty($rolesDb)) {
                    $query->role($role);
                } else {
                    if (Schema::hasColumn('users', 'role')) {
                        $query->where('role', $role);
                    }
                }
            })
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        $roles = Role::query()->orderBy('name')->pluck('name')->all();
        if (empty($roles)) {
            $roles = ['admin', 'driver', 'passenger'];
        }

        return view('admin.users.index', compact('users', 'roles', 'q', 'role'));
    }

    public function create()
    {
        $roles = Role::query()->orderBy('name')->pluck('name')->all();
        if (empty($roles)) {
            $roles = ['admin', 'driver', 'passenger'];
        }

        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $rolesValidos = Role::query()->pluck('name')->all();
        if (empty($rolesValidos)) {
            $rolesValidos = ['admin','driver','passenger'];
        }

        $data = $request->validate([
            'name'     => ['required','string','max:191'],
            'email'    => ['required','email','max:191','unique:users,email'],
            'phone' => ['required','string','max:50'],
            'password' => ['required','string',Password::min(8)->mixedCase()->numbers()->symbols(),],
            'role'     => ['required', Rule::in($rolesValidos)],
        ], [
            'password.required' => 'La contraseña es obligatoria.',
            'password.*' => 'La contraseña debe tener mínimo 8 caracteres, una mayúscula, un número y un símbolo.',
        ]);

        $user = new User();
        $user->name     = $data['name'];
        $user->email    = $data['email'];
        $user->phone    = $data['phone'] ?? null;
        $user->password = Hash::make($data['password']);

        if (Schema::hasColumn('users', 'role')) {
            $user->role = $data['role'];
        }

        $user->save();

        $rolesDb = Role::query()->pluck('name')->all();
        if (in_array($data['role'], $rolesDb, true)) {
            $user->syncRoles([$data['role']]);
        }

        return redirect()
            ->route('admin.users.index')
            ->with('status', "Usuario {$user->name} creado correctamente.");
    }

    public function show(User $user)
    {
        $roleNames = method_exists($user, 'getRoleNames') ? $user->getRoleNames() : collect();
        return view('admin.users.show', compact('user','roleNames'));
    }

    public function edit(User $user)
    {
        $roles = Role::query()->orderBy('name')->pluck('name')->all();
        if (empty($roles)) {
            $roles = ['admin', 'driver', 'passenger'];
        }

        return view('admin.users.edit', compact('user','roles'));
    }

    public function update(Request $request, User $user)
    {
        $rolesValidos = Role::query()->pluck('name')->all();
        if (empty($rolesValidos)) {
            $rolesValidos = ['admin','driver','passenger'];
        }

        $data = $request->validate([
            'name'     => ['required','string','max:191'],
            'email'    => ['required','email','max:191', Rule::unique('users','email')->ignore($user->id)],
            'phone'    => ['required','string','max:50'],
            'password' => ['nullable','string',Password::min(8)->mixedCase()->numbers()->symbols(),],
            'role'     => ['required', Rule::in($rolesValidos)],
        ], [
            'password.*' => 'La contraseña debe tener mínimo 8 caracteres, una mayúscula, un número y un símbolo.',
        ]);

        $user->name  = $data['name'];
        $user->email = $data['email'];
        $user->phone = $data['phone'] ?? null;

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        if (Schema::hasColumn('users', 'role')) {
            $user->role = $data['role'];
        }

        $user->save();

        $rolesDb = Role::query()->pluck('name')->all();
        if (in_array($data['role'], $rolesDb, true)) {
            $user->syncRoles([$data['role']]);
        }

        return redirect()
            ->route('admin.users.edit', $user)
            ->with('status', "Usuario {$user->name} actualizado.");
    }

    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->withErrors(['user' => 'No puedes eliminar tu propio usuario.']);
        }

        $name = $user->name;

        if (method_exists($user, 'syncRoles')) {
            $user->syncRoles([]);
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('status', "Usuario {$name} eliminado.");
    }

    public function activate(User $user)
    {
        if (Schema::hasColumn('users', 'is_active')) {
            $user->is_active = 1;
            $user->save();
        }

        return back()->with('status', "Usuario {$user->name} activado.");
    }

    public function deactivate(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->withErrors(['user' => 'No puedes desactivar tu propio usuario.']);
        }

        if (Schema::hasColumn('users', 'is_active')) {
            $user->is_active = 0;
            $user->save();
        }

        return back()->with('status', "Usuario {$user->name} desactivado.");
    }

    public function bulk(Request $request)
    {
        $data = $request->validate([
            'action' => ['required', Rule::in(['delete','activate','deactivate'])],
            'ids'    => ['required','array'],
            'ids.*'  => ['integer'],
        ]);

        $ids = array_values(array_unique($data['ids']));

        $ids = array_filter($ids, fn ($id) => (int)$id !== (int)auth()->id());

        $users = User::query()->whereIn('id', $ids)->get();

        if ($data['action'] === 'delete') {
            foreach ($users as $u) {
                if (method_exists($u, 'syncRoles')) {
                    $u->syncRoles([]);
                }
                $u->delete();
            }
            return back()->with('status', 'Usuarios eliminados.');
        }

        if ($data['action'] === 'activate') {
            if (Schema::hasColumn('users', 'is_active')) {
                User::query()->whereIn('id', $ids)->update(['is_active' => 1]);
            }
            return back()->with('status', 'Usuarios activados.');
        }

        if ($data['action'] === 'deactivate') {
            if (Schema::hasColumn('users', 'is_active')) {
                User::query()->whereIn('id', $ids)->update(['is_active' => 0]);
            }
            return back()->with('status', 'Usuarios desactivados.');
        }

        return back();
    }

    public function exportCsv(Request $request)
    {
        $q    = trim((string) $request->input('q'));
        $role = trim((string) $request->input('role'));

        $query = User::query()
            ->when($q, function ($qq) use ($q) {
                $qq->where(function ($w) use ($q) {
                    $w->where('name','like',"%{$q}%")
                      ->orWhere('email','like',"%{$q}%")
                      ->orWhere('phone','like',"%{$q}%");
                });
            })
            ->when($role, function ($qq) use ($role) {
                $rolesDb = Role::query()->pluck('name')->all();
                if (!empty($rolesDb)) {
                    $qq->role($role);
                } else {
                    if (Schema::hasColumn('users','role')) {
                        $qq->where('role', $role);
                    }
                }
            })
            ->latest('id');

        $filename = 'users_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');

            fputcsv($out, ['id','name','email','phone','role','created_at']);

            $query->chunk(500, function ($rows) use ($out) {
                foreach ($rows as $u) {
                    fputcsv($out, [
                        $u->id,
                        $u->name,
                        $u->email,
                        $u->phone,
                        $u->role ?? '',
                        optional($u->created_at)->toDateTimeString(),
                    ]);
                }
            });

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
