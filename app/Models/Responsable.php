<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Responsable extends Model
{
     protected $fillable = ['cedula', 'nombre', 'correo_institucional', 'dependencia', 'cargo', 'tipo_funcionario', 'numero_de_celular'];

    public function dispositivos(): HasMany
    {
        return $this->hasMany(Dispositivo::class);
    }
}