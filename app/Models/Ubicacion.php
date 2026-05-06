<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ubicacion extends Model
{
    protected $table = 'ubicaciones';
    protected $fillable = ['sede_id', 'bloque', 'ambiente'];

    public function sede(): BelongsTo
    {
        return $this->belongsTo(Sede::class);
    }

    public function dispositivos(): HasMany
    {
        return $this->hasMany(Dispositivo::class);
    }
}
