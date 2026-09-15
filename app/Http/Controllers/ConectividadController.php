<?php

namespace App\Http\Controllers;

use App\Models\Dispositivo;
use App\Models\Sede;
use Illuminate\Http\Request;

class ConectividadController extends Controller
{
    public function index(Request $request)
    {
        $query = Dispositivo::where('categoria', 'conectividad')
            ->with(['ubicacion.sede', 'responsable'])
            ->orderByRaw("ubicaciones.sede_id, dispositivos.placa")
            ->join('ubicaciones', 'ubicaciones.id', '=', 'dispositivos.ubicacion_id')
            ->select('dispositivos.*');

        if ($buscar = $request->input('buscar')) {
            $b = "%{$buscar}%";
            $query->where(function ($q) use ($b) {
                $q->where('dispositivos.placa',       'ILIKE', $b)
                  ->orWhere('dispositivos.serial',    'ILIKE', $b)
                  ->orWhere('dispositivos.mac_address','ILIKE', $b)
                  ->orWhere('dispositivos.modelo',    'ILIKE', $b);
            });
        }

        if ($sedeFilter = $request->input('sede')) {
            $query->whereHas('ubicacion.sede', fn($q) => $q->where('nombre', $sedeFilter));
        }

        $dispositivos = $query->get();

        $sedes = Sede::orderBy('nombre')
            ->whereHas('ubicaciones.dispositivos', fn($q) => $q->where('categoria', 'conectividad'))
            ->pluck('nombre');

        $stats = [
            'total'    => $dispositivos->count(),
            'switches' => $dispositivos->filter(fn($d) => !str_contains(strtolower($d->modelo ?? ''), 'airengine'))->count(),
            'aps'      => $dispositivos->filter(fn($d) =>  str_contains(strtolower($d->modelo ?? ''), 'airengine'))->count(),
            'sedes'    => $dispositivos->map(fn($d) => $d->ubicacion?->sede?->nombre)->filter()->unique()->count(),
        ];

        return view('conectividad.index', compact('dispositivos', 'stats', 'sedes'));
    }
}
