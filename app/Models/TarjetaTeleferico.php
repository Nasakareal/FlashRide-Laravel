<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TarjetaTeleferico extends Model
{
    use HasFactory;

    protected $table = 'tarjeta_telefericos';

    protected $fillable = [
        'nombres',
        'apellidos',
        'curp',
        'celular',
        'folio_tarjeta',
        'estatus',
        'fecha_entrega',
        'observaciones',
    ];
}
