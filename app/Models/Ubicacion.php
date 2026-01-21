<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ubicacion extends Model
{
    protected $table = 'ubicaciones';
    protected $fillable = ['sede', 'bloque', 'ambiente'];

    public function dispositivos(): HasMany
    {
        return $this->hasMany(Dispositivo::class);
    }
}