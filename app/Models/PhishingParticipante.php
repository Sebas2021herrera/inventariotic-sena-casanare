<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhishingParticipante extends Model
{
    protected $table = 'phishing_participantes';
    protected $fillable = ['nombre', 'documento', 'area'];

    public function resultados()
    {
        return $this->hasMany(PhishingResultado::class, 'participante_id');
    }
}
