<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SgspiConfig extends Model
{
    protected $table    = 'sgspi_config';
    protected $fillable = ['total_celdas', 'preguntas', 'columnas'];

    /** Devuelve siempre la fila única de configuración. */
    public static function get(): self
    {
        return static::firstOrCreate([], [
            'total_celdas' => 25,
            'preguntas'    => 20,
            'columnas'     => 5,
        ]);
    }

    /** Puntaje máximo posible con la configuración actual. */
    public function puntajeMaximo(): int
    {
        return $this->preguntas * 10;
    }
}
