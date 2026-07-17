<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SgspiPregunta extends Model
{
    protected $table = 'sgspi_preguntas';
    protected $fillable = ['tema', 'pregunta', 'opciones', 'respuesta', 'explicacion'];
    protected $casts = ['opciones' => 'array'];
}
