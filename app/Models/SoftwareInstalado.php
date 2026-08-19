<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SoftwareInstalado extends Model
{
    protected $table = 'software_instalado';

    protected $fillable = [
        'dispositivo_id',
        'software_catalogo_id',
        'fecha_instalacion',
        'version_notas',
        'instalado_por',
    ];

    protected $casts = [
        'fecha_instalacion' => 'date',
    ];

    public function dispositivo()
    {
        return $this->belongsTo(Dispositivo::class);
    }

    public function software()
    {
        return $this->belongsTo(SoftwareCatalogo::class, 'software_catalogo_id');
    }

    public function tecnico()
    {
        return $this->belongsTo(User::class, 'instalado_por');
    }
}
