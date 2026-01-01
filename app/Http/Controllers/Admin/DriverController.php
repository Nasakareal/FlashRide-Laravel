<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

class DriverController extends Controller
{
    public function index(Request $request)
    {
        $q        = trim((string) $request->input('q'));
        $verified = trim((string) $request->input('verified'));

        $drivers = User::query()
            ->where(function ($query) {
                $rolesDb = Role::query()->pluck('name')->all();

                if (!empty($rolesDb)) {
                    $query->role('driver');
                } else {
                    if (Schema::hasColumn('users', 'role')) {
                        $query->where('role', 'driver');
                    }
                }
            })
            ->with(['driverProfile', 'activeVehicleAssignment.vehicle'])
            ->when($q, function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    $qq->where('name', 'like', "%{$q}%")
                       ->orWhere('email', 'like', "%{$q}%")
                       ->orWhere('phone', 'like', "%{$q}%");
                })
                ->orWhereHas('driverProfile', function ($dp) use ($q) {
                    $dp->where(function ($w) use ($q) {
                        $w->where('license_number', 'like', "%{$q}%")
                          ->orWhere('curp', 'like', "%{$q}%")
                          ->orWhere('rfc', 'like', "%{$q}%");
                    });
                });
            })
            ->when($verified !== '', function ($query) use ($verified) {
                $v = in_array($verified, ['1', 1, true, 'true'], true) ? 1 : 0;

                $query->whereHas('driverProfile', function ($dp) use ($v) {
                    $dp->where('is_verified', $v);
                });
            })
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.drivers.index', compact('drivers', 'q', 'verified'));
    }

    public function create()
    {
        return view('admin.drivers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required','string','max:191'],
            'email'    => ['required','email','max:191','unique:users,email'],
            'phone'    => ['required','string','max:50'],
            'password' => ['required','string', Password::min(8)->mixedCase()->numbers()->symbols()],
            'license_number'      => ['nullable','string','max:100'],
            'license_expires_at'  => ['nullable','date'],
            'curp'                => ['nullable','string','max:18'],
            'rfc'                 => ['nullable','string','max:13'],
            'birthdate'           => ['nullable','date'],
            'address'             => ['nullable','string','max:500'],
            'notes'               => ['nullable','string','max:1000'],
            'is_verified'         => ['nullable', Rule::in([0,1,'0','1'])],
        ], [
            'password.*' => 'La contraseña debe tener mínimo 8 caracteres, una mayúscula, un número y un símbolo.',
        ]);

        $user = new User();
        $user->name     = $data['name'];
        $user->email    = $data['email'];
        $user->phone    = $data['phone'];
        $user->password = Hash::make($data['password']);

        if (Schema::hasColumn('users', 'role')) {
            $user->role = 'driver';
        }

        $user->save();

        $rolesDb = Role::query()->pluck('name')->all();
        if (in_array('driver', $rolesDb, true)) {
            $user->syncRoles(['driver']);
        }

        $profile = new Driver();
        $profile->user_id            = $user->id;
        $profile->license_number     = $data['license_number'] ?? null;
        $profile->license_expires_at = $data['license_expires_at'] ?? null;
        $profile->curp               = $data['curp'] ?? null;
        $profile->rfc                = $data['rfc'] ?? null;
        $profile->birthdate          = $data['birthdate'] ?? null;
        $profile->address            = $data['address'] ?? null;
        $profile->notes              = $data['notes'] ?? null;

        if (isset($data['is_verified'])) {
            $profile->is_verified = (int) $data['is_verified'];
            $profile->verified_at = ((int) $data['is_verified'] === 1) ? now() : null;
        }

        $profile->save();

        return redirect()
            ->route('admin.drivers.index')
            ->with('status', "Conductor {$user->name} creado correctamente.");
    }

    public function show(User $driver)
    {
        $this->ensureIsDriver($driver);

        $driver->load(['driverProfile', 'activeVehicleAssignment.vehicle']);

        return view('admin.drivers.show', compact('driver'));
    }

    public function edit(User $driver)
    {
        $this->ensureIsDriver($driver);

        $driver->load(['driverProfile', 'activeVehicleAssignment.vehicle']);

        return view('admin.drivers.edit', compact('driver'));
    }

    public function update(Request $request, User $driver)
    {
        $this->ensureIsDriver($driver);

        $data = $request->validate([
            'name'     => ['required','string','max:191'],
            'email'    => ['required','email','max:191', Rule::unique('users','email')->ignore($driver->id)],
            'phone'    => ['required','string','max:50'],
            'password' => ['nullable','string', Password::min(8)->mixedCase()->numbers()->symbols()],

            'license_number'      => ['nullable','string','max:100'],
            'license_expires_at'  => ['nullable','date'],
            'curp'                => ['nullable','string','max:18'],
            'rfc'                 => ['nullable','string','max:13'],
            'birthdate'           => ['nullable','date'],
            'address'             => ['nullable','string','max:500'],
            'notes'               => ['nullable','string','max:1000'],
            'is_verified'         => ['nullable', Rule::in([0,1,'0','1'])],
        ], [
            'password.*' => 'La contraseña debe tener mínimo 8 caracteres, una mayúscula, un número y un símbolo.',
        ]);

        $driver->name  = $data['name'];
        $driver->email = $data['email'];
        $driver->phone = $data['phone'];

        if (!empty($data['password'])) {
            $driver->password = Hash::make($data['password']);
        }

        if (Schema::hasColumn('users', 'role')) {
            $driver->role = 'driver';
        }

        $driver->save();

        $rolesDb = Role::query()->pluck('name')->all();
        if (in_array('driver', $rolesDb, true)) {
            $driver->syncRoles(['driver']);
        }

        $profile = $driver->driverProfile ?: new Driver(['user_id' => $driver->id]);
        $profile->user_id            = $driver->id;
        $profile->license_number     = $data['license_number'] ?? null;
        $profile->license_expires_at = $data['license_expires_at'] ?? null;
        $profile->curp               = $data['curp'] ?? null;
        $profile->rfc                = $data['rfc'] ?? null;
        $profile->birthdate          = $data['birthdate'] ?? null;
        $profile->address            = $data['address'] ?? null;
        $profile->notes              = $data['notes'] ?? null;

        if (isset($data['is_verified'])) {
            $v = (int) $data['is_verified'];
            $profile->is_verified = $v;
            $profile->verified_at = ($v === 1) ? ($profile->verified_at ?? now()) : null;
        }

        $profile->save();

        return redirect()
            ->route('admin.drivers.edit', $driver)
            ->with('status', "Conductor {$driver->name} actualizado.");
    }

    public function destroy(User $driver)
    {
        $this->ensureIsDriver($driver);

        if (auth()->id() === $driver->id) {
            return back()->withErrors(['driver' => 'No puedes eliminar tu propia cuenta.']);
        }

        $name = $driver->name;

        if (method_exists($driver, 'syncRoles')) {
            $driver->syncRoles([]);
        }

        $driver->delete();

        return redirect()
            ->route('admin.drivers.index')
            ->with('status', "Conductor {$name} eliminado.");
    }

    private function ensureIsDriver(User $user): void
    {
        $rolesDb = Role::query()->pluck('name')->all();

        $isDriver = false;

        if (!empty($rolesDb) && method_exists($user, 'hasRole')) {
            $isDriver = $user->hasRole('driver');
        } elseif (Schema::hasColumn('users', 'role')) {
            $isDriver = ((string) $user->role === 'driver');
        }

        if (!$isDriver) {
            abort(404);
        }
    }
}
