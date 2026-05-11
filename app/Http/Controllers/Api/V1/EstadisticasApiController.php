<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Dispositivo;
use App\Models\Responsable;
use Illuminate\Support\Facades\DB;

/**
 * GET /api/v1/estadisticas
 * Resumen ejecutivo del inventario para dashboards externos.
 */
class EstadisticasApiController extends Controller
{
    public function index()
    {
        $total = Dispositivo::count();

        return response()->json([
            'success' => true,
            'data'    => [
                'resumen' => [
                    'total_dispositivos'  => $total,
                    'total_responsables'  => Responsable::whereHas('dispositivos')->count(),
                    'total_sedes'         => DB::table('sedes')->count(),
                    'en_intune'           => Dispositivo::where('en_intune', 'SI')->count(),
                    'en_reparacion'       => Dispositivo::where('estado_fisico', 'En Reparación')->count(),
                ],
                'por_estado' => Dispositivo::select('estado_fisico', DB::raw('count(*) as total'))
                    ->whereNotNull('estado_fisico')
                    ->groupBy('estado_fisico')
                    ->orderByDesc('total')
                    ->get()
                    ->map(fn($r) => [
                        'estado'      => $r->estado_fisico,
                        'total'       => $r->total,
                        'porcentaje'  => $total > 0 ? round($r->total / $total * 100, 1) : 0,
                    ]),
                'por_sede' => DB::table('sedes')
                    ->join('ubicaciones', 'sedes.id', '=', 'ubicaciones.sede_id')
                    ->join('dispositivos', 'ubicaciones.id', '=', 'dispositivos.ubicacion_id')
                    ->select('sedes.nombre as sede', DB::raw('count(dispositivos.id) as total'))
                    ->groupBy('sedes.id', 'sedes.nombre')
                    ->orderByDesc('total')
                    ->get(),
                'por_categoria' => Dispositivo::select('categoria', DB::raw('count(*) as total'))
                    ->whereNotNull('categoria')
                    ->groupBy('categoria')
                    ->orderByDesc('total')
                    ->get(),
            ],
        ]);
    }
}
