<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\TarjetaTeleferico;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PreregistroTarjetaPublicController extends Controller
{
    public function create()
    {
        return view('teleferico.preregistro');
    }

    public function store(Request $request)
    {
        $input = $request->all();

        $normalize = function ($value, $removeSpaces = false) {
            $value = mb_strtoupper((string) $value, 'UTF-8');
            $value = strtr($value, [
                'Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U',
                'À' => 'A', 'È' => 'E', 'Ì' => 'I', 'Ò' => 'O', 'Ù' => 'U',
                'Ä' => 'A', 'Ë' => 'E', 'Ï' => 'I', 'Ö' => 'O', 'Ü' => 'U',
                'Â' => 'A', 'Ê' => 'E', 'Î' => 'I', 'Ô' => 'O', 'Û' => 'U',
                'á' => 'A', 'é' => 'E', 'í' => 'I', 'ó' => 'O', 'ú' => 'U',
                'à' => 'A', 'è' => 'E', 'ì' => 'I', 'ò' => 'O', 'ù' => 'U',
                'ä' => 'A', 'ë' => 'E', 'ï' => 'I', 'ö' => 'O', 'ü' => 'U',
                'â' => 'A', 'ê' => 'E', 'î' => 'I', 'ô' => 'O', 'û' => 'U',
                'Ñ' => 'N', 'ñ' => 'N',
            ]);
            if ($removeSpaces) {
                $value = preg_replace('/\s+/', '', $value);
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

        if (isset($input['celular'])) {
            $input['celular'] = trim((string) $input['celular']);
        }

        unset($input['estatus'], $input['folio_tarjeta'], $input['fecha_entrega'], $input['observaciones']);

        $request->merge($input);

        $data = $request->validate([
            'nombres' => ['required', 'string', 'max:255'],
            'apellidos' => ['required', 'string', 'max:255'],
            'curp' => ['required', 'string', 'size:18', 'unique:tarjeta_telefericos,curp'],
            'celular' => ['nullable', 'string', 'max:20'],
        ], [
            'curp.unique' => 'LA CURP YA FUE REGISTRADA PREVIAMENTE.',
            'curp.size' => 'LA CURP DEBE TENER 18 CARACTERES.',
        ]);

        try {
            TarjetaTeleferico::create([
                'nombres' => $data['nombres'],
                'apellidos' => $data['apellidos'],
                'curp' => $data['curp'],
                'celular' => $data['celular'] ?? null,
                'folio_tarjeta' => null,
                'estatus' => 'INACTIVA',
                'fecha_entrega' => null,
                'observaciones' => 'PREREGISTRO WEB',
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            throw ValidationException::withMessages([
                'curp' => 'LA CURP YA FUE REGISTRADA PREVIAMENTE.',
            ]);
        }

        return redirect()
            ->route('teleferico.preregistro.success')
            ->with('status', 'PREREGISTRO REALIZADO CORRECTAMENTE.');
    }

    public function success()
    {
        return view('teleferico.preregistro_success');
    }
}
