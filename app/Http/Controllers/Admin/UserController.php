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
    private function authIsSuperAdmin(): bool
    {
        $me = auth()->user();
        return $me && method_exists($me, 'hasRole') && $me->hasRole('superadmin');
    }

    private function userIsSuperAdmin(User $user): bool
    {
        if (method_exists($user, 'hasRole')) {
            return $user->hasRole('superadmin');
        }
        if (Schema::hasColumn('users', 'role')) {
            return ($user->role === 'superadmin');
        }
        return false;
    }

    private function abortIfTouchingSuperAdmin(User $user): void
    {
        if ($this->userIsSuperAdmin($user) && !$this->authIsSuperAdmin()) {
            abort(403, 'No autorizado.');
        }
    }

    private function abortIfAssigningSuperAdmin(string $role): void
    {
        if ($role === 'superadmin' && !$this->authIsSuperAdmin()) {
            abort(403, 'No autorizado.');
        }
    }

    private function rolesValidos(): array
    {
        $rolesValidos = Role::query()->pluck('name')->all();
        if (empty($rolesValidos)) {
            $rolesValidos = ['superadmin','admin','capturista','analista','soporte','driver','passenger'];
        }
        return $rolesValidos;
    }

    private function rolesParaFormulario(): array
    {
        $roles = Role::query()->orderBy('name')->pluck('name')->all();
        if (empty($roles)) {
            $roles = ['superadmin','admin','capturista','analista','soporte','driver','passenger'];
        }

        if (!$this->authIsSuperAdmin()) {
            $roles = array_values(array_filter($roles, fn ($r) => $r !== 'superadmin'));
        }

        return $roles;
    }

    public function index(Request $request)
    {
        $q    = trim((string) $request->input('q'));
        $role = trim((string) $request->input('role'));

        $users = User::query()
            ->when(!$this->authIsSuperAdmin(), function ($query) {
                $rolesDb = Role::query()->pluck('name')->all();
                if (!empty($rolesDb)) {
                    $query->whereDoesntHave('roles', function ($r) {
                        $r->where('name', 'superadmin');
                    });
                } else {
                    if (Schema::hasColumn('users', 'role')) {
                        $query->where('role', '!=', 'superadmin');
                    }
                }
            })
            ->when($q, function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    $qq->where('name', 'like', "%{$q}%")
                       ->orWhere('email', 'like', "%{$q}%")
                       ->orWhere('phone', 'like', "%{$q}%");
                });
            })
            ->when($role, function ($query) use ($role) {
                if ($role === 'superadmin' && !(auth()->user() && method_exists(auth()->user(), 'hasRole') && auth()->user()->hasRole('superadmin'))) {
                    abort(403, 'No autorizado.');
                }

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

        $roles = $this->rolesParaFormulario();

        return view('admin.users.index', compact('users', 'roles', 'q', 'role'));
    }

    public function create()
    {
        $roles = $this->rolesParaFormulario();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $rolesValidos = $this->rolesValidos();

        $data = $request->validate([
            'name'     => ['required','string','max:191'],
            'email'    => ['required','email','max:191','unique:users,email'],
            'phone'    => ['required','string','max:50'],
            'password' => ['required','string', Password::min(8)->mixedCase()->numbers()->symbols()],
            'role'     => ['required', Rule::in($rolesValidos)],
        ], [
            'password.required' => 'La contraseña es obligatoria.',
            'password.*' => 'La contraseña debe tener mínimo 8 caracteres, una mayúscula, un número y un símbolo.',
        ]);

        $this->abortIfAssigningSuperAdmin($data['role']);

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
        if (in_array($data['role'], $rolesDb, true) && method_exists($user, 'syncRoles')) {
            $user->syncRoles([$data['role']]);
        }

        return redirect()
            ->route('admin.users.index')
            ->with('status', "Usuario {$user->name} creado correctamente.");
    }

    public function show(User $user)
    {
        $this->abortIfTouchingSuperAdmin($user);

        $roleNames = method_exists($user, 'getRoleNames') ? $user->getRoleNames() : collect();
        return view('admin.users.show', compact('user','roleNames'));
    }

    public function edit(User $user)
    {
        $this->abortIfTouchingSuperAdmin($user);

        $roles = $this->rolesParaFormulario();

        return view('admin.users.edit', compact('user','roles'));
    }

    public function update(Request $request, User $user)
    {
        $this->abortIfTouchingSuperAdmin($user);

        $rolesValidos = $this->rolesValidos();

        $data = $request->validate([
            'name'     => ['required','string','max:191'],
            'email'    => ['required','email','max:191', Rule::unique('users','email')->ignore($user->id)],
            'phone'    => ['required','string','max:50'],
            'password' => ['nullable','string', Password::min(8)->mixedCase()->numbers()->symbols()],
            'role'     => ['required', Rule::in($rolesValidos)],
        ], [
            'password.*' => 'La contraseña debe tener mínimo 8 caracteres, una mayúscula, un número y un símbolo.',
        ]);

        $this->abortIfAssigningSuperAdmin($data['role']);

        if ($user->id === auth()->id() && $this->userIsSuperAdmin($user) && $data['role'] !== 'superadmin') {
            return back()->withErrors(['role' => 'No puedes quitarte el rol superadmin a ti mismo.']);
        }

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
        if (in_array($data['role'], $rolesDb, true) && method_exists($user, 'syncRoles')) {
            $user->syncRoles([$data['role']]);
        }

        return redirect()
            ->route('admin.users.edit', $user)
            ->with('status', "Usuario {$user->name} actualizado.");
    }

    public function destroy(User $user)
    {
        $this->abortIfTouchingSuperAdmin($user);

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
        $this->abortIfTouchingSuperAdmin($user);

        if (Schema::hasColumn('users', 'is_active')) {
            $user->is_active = 1;
            $user->save();
        }

        return back()->with('status', "Usuario {$user->name} activado.");
    }

    public function deactivate(User $user)
    {
        $this->abortIfTouchingSuperAdmin($user);

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

        $users = User::query()
            ->whereIn('id', $ids)
            ->when(!$this->authIsSuperAdmin(), function ($query) {
                $rolesDb = Role::query()->pluck('name')->all();
                if (!empty($rolesDb)) {
                    $query->whereDoesntHave('roles', function ($r) {
                        $r->where('name', 'superadmin');
                    });
                } else {
                    if (Schema::hasColumn('users', 'role')) {
                        $query->where('role', '!=', 'superadmin');
                    }
                }
            })
            ->get();

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
                User::query()->whereIn('id', $users->pluck('id')->all())->update(['is_active' => 1]);
            }
            return back()->with('status', 'Usuarios activados.');
        }

        if ($data['action'] === 'deactivate') {
            if (Schema::hasColumn('users', 'is_active')) {
                User::query()->whereIn('id', $users->pluck('id')->all())->update(['is_active' => 0]);
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
            ->when(!$this->authIsSuperAdmin(), function ($qq) {
                $rolesDb = Role::query()->pluck('name')->all();
                if (!empty($rolesDb)) {
                    $qq->whereDoesntHave('roles', function ($r) {
                        $r->where('name', 'superadmin');
                    });
                } else {
                    if (Schema::hasColumn('users', 'role')) {
                        $qq->where('role', '!=', 'superadmin');
                    }
                }
            })
            ->when($q, function ($qq) use ($q) {
                $qq->where(function ($w) use ($q) {
                    $w->where('name','like',"%{$q}%")
                      ->orWhere('email','like',"%{$q}%")
                      ->orWhere('phone','like',"%{$q}%");
                });
            })
            ->when($role, function ($qq) use ($role) {
                if ($role === 'superadmin' && !(auth()->user() && method_exists(auth()->user(), 'hasRole') && auth()->user()->hasRole('superadmin'))) {
                    abort(403, 'No autorizado.');
                }

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
                    $roleCol = Schema::hasColumn('users', 'role') ? ($u->role ?? '') : '';

                    fputcsv($out, [
                        $u->id,
                        $u->name,
                        $u->email,
                        $u->phone,
                        $roleCol,
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
