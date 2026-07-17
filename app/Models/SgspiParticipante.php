<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SgspiParticipante extends Model
{
    protected $table = 'sgspi_participantes';
    protected $fillable = ['nombre', 'documento', 'area'];

    public function resultados()
    {
        return $this->hasMany(SgspiResultado::class, 'participante_id');
    }
}
