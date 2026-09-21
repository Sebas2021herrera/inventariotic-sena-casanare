<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhishingConfig extends Model
{
    protected $table    = 'phishing_config';
    protected $fillable = ['escenarios'];

    /** Devuelve siempre la fila única de configuración. */
    public static function get(): self
    {
        return static::firstOrCreate([], [
            'escenarios' => 10,
        ]);
    }

    /** Puntaje máximo posible: cada escenario vale 10 pts + 5 bonus. */
    public function puntajeMaximo(): int
    {
        return $this->escenarios * 15;
    }
}
