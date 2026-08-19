<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SoftwareCatalogo extends Model
{
    protected $table = 'software_catalogo';

    protected $fillable = ['nombre', 'subproducto', 'tipo', 'vigencia_hasta', 'activo'];

    protected $casts = [
        'vigencia_hasta' => 'date',
        'activo'         => 'boolean',
    ];

    public function instalaciones()
    {
        return $this->hasMany(SoftwareInstalado::class);
    }

    public function getNombreCompletoAttribute(): string
    {
        return $this->subproducto
            ? $this->nombre . ' — ' . $this->subproducto
            : $this->nombre;
    }
}
