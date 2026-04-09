<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TarjetaTeleferico;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TarjetaTelefericoController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->input('q'));
        $estatus = trim((string) $request->input('estatus'));

        $tarjetas = TarjetaTeleferico::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    $qq->where('nombres', 'like', "%{$q}%")
                        ->orWhere('apellidos', 'like', "%{$q}%")
                        ->orWhere('curp', 'like', "%{$q}%")
                        ->orWhere('celular', 'like', "%{$q}%")
                        ->orWhere('folio_tarjeta', 'like', "%{$q}%");
                });
            })
            ->when($estatus !== '', function ($query) use ($estatus) {
                $query->where('estatus', $estatus);
            })
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.tarjetas_teleferico.index', compact('tarjetas', 'q', 'estatus'));
    }

    public function create()
    {
        return view('admin.tarjetas_teleferico.create');
    }

    public function store(Request $request)
    {
        $input = $request->all();

        $normalize = function ($value, $removeSpaces = false) {
            $value = mb_strtoupper($value, 'UTF-8');
            $value = strtr($value, [
                'Á'=>'A','É'=>'E','Í'=>'I','Ó'=>'O','Ú'=>'U',
                'á'=>'A','é'=>'E','í'=>'I','ó'=>'O','ú'=>'U',
                'Ñ'=>'N','ñ'=>'N'
            ]);
            if ($removeSpaces) {
                $value = str_replace(' ', '', $value);
            }
            return trim($value);
        };

        if (isset($input['nombres'])) {
            $input['nombres'] = $normalize($input['nombres']);
        }

        if (isset($input['apellidos'])) {
            $input['apellidos'] = $normalize($input['apellidos']);
        }

        if (isset($input['curp'])) {
            $input['curp'] = $normalize($input['curp'], true);
        }

        if (isset($input['folio_tarjeta'])) {
            $input['folio_tarjeta'] = $normalize($input['folio_tarjeta'], true);
        }

        $request->merge($input);

        $data = $request->validate([
            'nombres' => ['required', 'string', 'max:255'],
            'apellidos' => ['required', 'string', 'max:255'],
            'curp' => ['required', 'string', 'size:18', 'unique:tarjeta_telefericos,curp'],
            'celular' => ['nullable', 'string', 'max:20'],
            'folio_tarjeta' => ['required', 'string', 'max:255', 'unique:tarjeta_telefericos,folio_tarjeta'],
            'estatus' => ['required', Rule::in(['ACTIVA', 'INACTIVA', 'CANCELADA', 'REPOSICION'])],
            'fecha_entrega' => ['nullable', 'date'],
            'observaciones' => ['nullable', 'string'],
        ]);

        TarjetaTeleferico::create($data);

        return redirect()
            ->route('admin.tarjetas-teleferico.index')
            ->with('status', 'Tarjeta registrada correctamente.');
    }

    public function show(TarjetaTeleferico $tarjetaTeleferico)
    {
        return view('admin.tarjetas_teleferico.show', compact('tarjetaTeleferico'));
    }

    public function edit(TarjetaTeleferico $tarjetaTeleferico)
    {
        return view('admin.tarjetas_teleferico.edit', compact('tarjetaTeleferico'));
    }

    public function update(Request $request, TarjetaTeleferico $tarjetaTeleferico)
    {
        $data = $request->validate([
            'nombres' => ['required', 'string', 'max:255'],
            'apellidos' => ['required', 'string', 'max:255'],
            'curp' => ['required', 'string', 'size:18', Rule::unique('tarjeta_telefericos', 'curp')->ignore($tarjetaTeleferico->id)],
            'celular' => ['nullable', 'string', 'max:20'],
            'folio_tarjeta' => ['required', 'string', 'max:255', Rule::unique('tarjeta_telefericos', 'folio_tarjeta')->ignore($tarjetaTeleferico->id)],
            'estatus' => ['required', Rule::in(['ACTIVA', 'INACTIVA', 'CANCELADA', 'REPOSICION'])],
            'fecha_entrega' => ['nullable', 'date'],
            'observaciones' => ['nullable', 'string'],
        ]);

        $tarjetaTeleferico->update($data);

        return redirect()
            ->route('admin.tarjetas-teleferico.edit', $tarjetaTeleferico)
            ->with('status', 'Tarjeta actualizada correctamente.');
    }

    public function destroy(TarjetaTeleferico $tarjetaTeleferico)
    {
        $tarjetaTeleferico->delete();

        return redirect()
            ->route('admin.tarjetas-teleferico.index')
            ->with('status', 'Tarjeta eliminada correctamente.');
    }

    public function activar(TarjetaTeleferico $tarjetaTeleferico)
    {
        $tarjetaTeleferico->update([
            'estatus' => 'ACTIVA',
        ]);

        return redirect()
            ->route('admin.tarjetas-teleferico.index')
            ->with('status', 'Tarjeta activada correctamente.');
    }

    public function inactivar(TarjetaTeleferico $tarjetaTeleferico)
    {
        $tarjetaTeleferico->update([
            'estatus' => 'INACTIVA',
        ]);

        return redirect()
            ->route('admin.tarjetas-teleferico.index')
            ->with('status', 'Tarjeta inactivada correctamente.');
    }

    public function cancelar(TarjetaTeleferico $tarjetaTeleferico)
    {
        $tarjetaTeleferico->update([
            'estatus' => 'CANCELADA',
        ]);

        return redirect()
            ->route('admin.tarjetas-teleferico.index')
            ->with('status', 'Tarjeta cancelada correctamente.');
    }

    public function reposicion(TarjetaTeleferico $tarjetaTeleferico)
    {
        $tarjetaTeleferico->update([
            'estatus' => 'REPOSICION',
        ]);

        return redirect()
            ->route('admin.tarjetas-teleferico.index')
            ->with('status', 'Tarjeta marcada como reposición correctamente.');
    }

    public function bulk(Request $request)
    {
        $data = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:tarjeta_telefericos,id'],
            'action' => ['required', Rule::in(['activar', 'inactivar', 'cancelar', 'eliminar'])],
        ]);

        $query = TarjetaTeleferico::whereIn('id', $data['ids']);

        if ($data['action'] === 'activar') {
            $query->update(['estatus' => 'ACTIVA']);
            return redirect()->route('admin.tarjetas-teleferico.index')->with('status', 'Tarjetas activadas correctamente.');
        }

        if ($data['action'] === 'inactivar') {
            $query->update(['estatus' => 'INACTIVA']);
            return redirect()->route('admin.tarjetas-teleferico.index')->with('status', 'Tarjetas inactivadas correctamente.');
        }

        if ($data['action'] === 'cancelar') {
            $query->update(['estatus' => 'CANCELADA']);
            return redirect()->route('admin.tarjetas-teleferico.index')->with('status', 'Tarjetas canceladas correctamente.');
        }

        $query->delete();

        return redirect()
            ->route('admin.tarjetas-teleferico.index')
            ->with('status', 'Tarjetas eliminadas correctamente.');
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $q = trim((string) $request->input('q'));
        $estatus = trim((string) $request->input('estatus'));

        $filename = 'tarjetas_teleferico_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($q, $estatus) {
            $handle = fopen('php://output', 'w');

            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($handle, [
                'ID',
                'NOMBRES',
                'APELLIDOS',
                'CURP',
                'CELULAR',
                'FOLIO_TARJETA',
                'ESTATUS',
                'FECHA_ENTREGA',
                'OBSERVACIONES',
                'CREADO',
                'ACTUALIZADO',
            ]);

            TarjetaTeleferico::query()
                ->when($q !== '', function ($query) use ($q) {
                    $query->where(function ($qq) use ($q) {
                        $qq->where('nombres', 'like', "%{$q}%")
                            ->orWhere('apellidos', 'like', "%{$q}%")
                            ->orWhere('curp', 'like', "%{$q}%")
                            ->orWhere('celular', 'like', "%{$q}%")
                            ->orWhere('folio_tarjeta', 'like', "%{$q}%");
                    });
                })
                ->when($estatus !== '', function ($query) use ($estatus) {
                    $query->where('estatus', $estatus);
                })
                ->orderBy('id', 'desc')
                ->chunk(500, function ($rows) use ($handle) {
                    foreach ($rows as $row) {
                        fputcsv($handle, [
                            $row->id,
                            $row->nombres,
                            $row->apellidos,
                            $row->curp,
                            $row->celular,
                            $row->folio_tarjeta,
                            $row->estatus,
                            $row->fecha_entrega,
                            $row->observaciones,
                            optional($row->created_at)->format('Y-m-d H:i:s'),
                            optional($row->updated_at)->format('Y-m-d H:i:s'),
                        ]);
                    }
                });

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
