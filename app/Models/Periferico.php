<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Periferico extends Model
{
    use HasFactory;

    protected $fillable = [
        'dispositivo_id',
        'tipo',
        'placa',
        'serial',
        'marca',
        'modelo',
        'estado'
    ];

    // Un periférico pertenece a un dispositivo principal
    public function dispositivo()
    {
        return $this->belongsTo(Dispositivo::class);
    }
}