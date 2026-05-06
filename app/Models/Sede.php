<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sede extends Model
{
    protected $fillable = ['nombre', 'municipio'];

    public function ubicaciones(): HasMany
    {
        return $this->hasMany(Ubicacion::class);
    }
}
