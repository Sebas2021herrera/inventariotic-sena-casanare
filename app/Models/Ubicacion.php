<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ubicacion extends Model
{
    protected $table = 'ubicaciones';
    protected $fillable = ['sede_id', 'bloque', 'ambiente'];

    /**
     * Normaliza bloque y ambiente a MAYÚSCULAS al guardar.
     * "cuarto" | "CUARTO" | "Cuarto" → "CUARTO"
     */
    protected function bloque(): Attribute
    {
        return Attribute::make(
            set: fn(?string $value) => mb_strtoupper(trim($value ?? '')),
        );
    }

    protected function ambiente(): Attribute
    {
        return Attribute::make(
            set: fn(?string $value) => mb_strtoupper(trim($value ?? '')),
        );
    }

    public function sede(): BelongsTo
    {
        return $this->belongsTo(Sede::class);
    }

    public function dispositivos(): HasMany
    {
        return $this->hasMany(Dispositivo::class);
    }
}
