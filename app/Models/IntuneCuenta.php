<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IntuneCuenta extends Model
{
    protected $table = 'intune_cuentas';

    protected $fillable = [
        'cuenta',
        'id_sede',
        'placa',
        'estado',
        'tipo',
        'fecha_reporte',
        'notas',
    ];

    protected $casts = [
        'fecha_reporte' => 'date',
    ];

    public function dispositivo()
    {
        return $this->belongsTo(Dispositivo::class, 'placa', 'placa');
    }
}
