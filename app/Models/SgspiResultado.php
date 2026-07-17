<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SgspiResultado extends Model
{
    protected $table = 'sgspi_resultados';
    protected $fillable = ['participante_id', 'puntaje', 'correctas', 'total'];

    public function participante()
    {
        return $this->belongsTo(SgspiParticipante::class, 'participante_id');
    }
}
