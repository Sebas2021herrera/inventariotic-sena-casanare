<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhishingResultado extends Model
{
    protected $table = 'phishing_resultados';
    protected $fillable = ['participante_id','puntaje','correctas','total','bonus','nivel_alcanzado'];

    public function participante()
    {
        return $this->belongsTo(PhishingParticipante::class, 'participante_id');
    }
}
