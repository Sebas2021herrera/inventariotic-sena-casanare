<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sede extends Model
{
    protected $fillable = ['nombre', 'municipio'];

    /**
     * Normaliza el nombre a Title Case al guardar.
     * "YOPAL" | "yopal" | "Yopal" → "Yopal"
     */
    protected function nombre(): Attribute
    {
        return Attribute::make(
            set: fn(string $value) => ucwords(mb_strtolower(trim($value))),
        );
    }

    public function ubicaciones(): HasMany
    {
        return $this->hasMany(Ubicacion::class);
    }
}
