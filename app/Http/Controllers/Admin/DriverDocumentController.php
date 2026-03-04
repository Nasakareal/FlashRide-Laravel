<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\DriverDocument;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response;

class DriverDocumentController extends Controller
{
    // Tipos permitidos (los mismos que te recomendé)
    private const TYPES = [
        'TITULO_CONCESION',
        'TARJETA_CIRCULACION',
        'POLIZA_SEGURO',
        'DICTAMEN_FISICO_MECANICO',
        'MANIFESTACION_PROTESTA',
        'TARJETA_CONTROL',
        'LICENCIA_CONDUCIR',
        'INE_FRENTE',
        'INE_REVERSO',
        'COMPROBANTE_DOMICILIO',
    ];

    private function rolesEnabled(): bool
    {
        try {
            return Role::query()->exists();
        } catch (\Throwable $e) {
            return false;
        }
    }

    private function ensureIsDriver(User $user): void
    {
        $rolesEnabled = $this->rolesEnabled();

        $isDriver = false;

        if ($rolesEnabled && method_exists($user, 'hasRole')) {
            $isDriver = $user->hasRole('driver');
        } elseif (Schema::hasColumn('users', 'role')) {
            $isDriver = ((string) $user->role === 'driver');
        }

        if (!$isDriver) {
            abort(404);
        }
    }

    private function driverFromUser(User $driverUser): Driver
    {
        $this->ensureIsDriver($driverUser);

        $profile = $driverUser->driverProfile;
        if (!$profile) {
            // Si no existe expediente aún, lo creamos vacío (para permitir subir docs sin esperar edit)
            $profile = new Driver();
            $profile->user_id = $driverUser->id;
            $profile->is_verified = 0;
            $profile->save();
        }

        return $profile;
    }

    /**
     * POST /flashride/admin/drivers/{driver}/documents
     */
    public function store(Request $request, User $driver)
    {
        $profile = $this->driverFromUser($driver);

        $data = $request->validate([
            'type' => ['required', 'string', Rule::in(self::TYPES)],
            'file' => ['required', 'file', 'mimes:pdf', 'max:20480'], // 20MB
            'notes' => ['nullable', 'string', 'max:1000'],
        ], [
            'file.mimes' => 'El archivo debe ser PDF.',
            'file.max' => 'El PDF no puede pesar más de 20MB.',
        ]);

        $file = $request->file('file');

        // Desactivar el documento activo anterior de ese tipo (si existe)
        DriverDocument::query()
            ->where('driver_id', $profile->id)
            ->where('type', $data['type'])
            ->where('is_active', 1)
            ->update(['is_active' => 0]);

        $dir = "drivers/{$profile->id}";
        $filename = $data['type'] . '_' . now()->format('Ymd_His') . '.pdf';

        $path = $file->storeAs($dir, $filename, 'public');

        $doc = new DriverDocument();
        $doc->driver_id      = $profile->id;
        $doc->type           = $data['type'];
        $doc->file_path      = $path;
        $doc->original_name  = $file->getClientOriginalName();
        $doc->mime           = $file->getClientMimeType();
        $doc->size           = $file->getSize();
        $doc->is_active      = 1;
        $doc->uploaded_at    = now();
        $doc->notes          = $data['notes'] ?? null;
        $doc->save();

        return redirect()
            ->route('admin.drivers.show', $driver)
            ->with('status', 'Documento subido correctamente.');
    }

    /**
     * GET /flashride/admin/drivers/{driver}/documents/{document}/download
     */
    public function download(User $driver, DriverDocument $document)
    {
        $profile = $this->driverFromUser($driver);

        if ((int) $document->driver_id !== (int) $profile->id) {
            abort(404);
        }

        if (!Storage::disk('public')->exists($document->file_path)) {
            abort(404);
        }

        $downloadName = ($document->type ?: 'documento') . '.pdf';

        return Storage::disk('public')->download($document->file_path, $downloadName);
    }

    /**
     * DELETE /flashride/admin/drivers/{driver}/documents/{document}
     */
    public function destroy(User $driver, DriverDocument $document)
    {
        $profile = $this->driverFromUser($driver);

        if ((int) $document->driver_id !== (int) $profile->id) {
            abort(404);
        }

        // Borra el archivo físico si existe (y luego el registro)
        try {
            if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }
        } catch (\Throwable $e) {
            // Si falla el delete físico, no tumbamos el flujo
        }

        $document->delete();

        return redirect()
            ->route('admin.drivers.show', $driver)
            ->with('status', 'Documento eliminado.');
    }

    // Si luego quieres ver en el navegador (inline) en vez de descargar:
    // public function view(User $driver, DriverDocument $document) { ... return response()->file(...); }
}
