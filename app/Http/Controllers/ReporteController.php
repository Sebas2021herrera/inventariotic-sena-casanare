<?php

namespace App\Http\Controllers;

use App\Models\Dispositivo;
use App\Models\Mantenimiento;
use Illuminate\Support\Facades\DB;
use App\Exports\InventarioGeneralExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class ReporteController extends Controller
{
    public function index()
    {
        $ahora = Carbon::now();

        $totalGeneral = Dispositivo::count();

        $intuneData = Dispositivo::select('en_intune', DB::raw('count(*) as total'))
            ->groupBy('en_intune')
            ->get();
        $intuneEnrolados = $intuneData->where('en_intune', 'SI')->first()->total ?? 0;

        $stats = [
            'total_general'       => $totalGeneral,
            'intune_enrolados'    => $intuneEnrolados,
            'intune_pendientes'   => $totalGeneral - $intuneEnrolados,
            'en_reparacion'       => Dispositivo::where('estado_fisico', 'EN REPARACIÓN')->count(),
            'mantenimientos_mes'  => Mantenimiento::whereYear('fecha', $ahora->year)
                                        ->whereMonth('fecha', $ahora->month)
                                        ->count(),

            'por_sede' => DB::table('ubicaciones')
                ->join('dispositivos', 'ubicaciones.id', '=', 'dispositivos.ubicacion_id')
                ->select(
                    DB::raw('UPPER(TRIM(sede)) as sede_nombre'),
                    DB::raw('count(dispositivos.id) as total')
                )
                ->groupBy(DB::raw('UPPER(TRIM(sede))'))
                ->orderBy('total', 'desc')
                ->get(),

            'estado_fisico' => Dispositivo::select('estado_fisico', DB::raw('count(*) as total'))
                ->whereNotNull('estado_fisico')
                ->groupBy('estado_fisico')
                ->orderBy('total', 'desc')
                ->get(),

            'por_categoria' => Dispositivo::select('categoria', DB::raw('count(*) as total'))
                ->whereNotNull('categoria')
                ->groupBy('categoria')
                ->orderBy('total', 'desc')
                ->get(),

            'funcion' => Dispositivo::select('funcion', DB::raw('count(*) as total'))
                ->whereNotNull('funcion')
                ->groupBy('funcion')
                ->orderBy('total', 'desc')
                ->get(),

            'propietario' => Dispositivo::select('propietario', DB::raw('count(*) as total'))
                ->whereNotNull('propietario')
                ->groupBy('propietario')
                ->get(),

            'ram' => DB::table('especificaciones')
                ->select('ram', DB::raw('count(*) as total'))
                ->whereNotNull('ram')
                ->groupBy('ram')
                ->orderBy('total', 'desc')
                ->get(),

            'discos' => DB::table('especificaciones')
                ->select('tipo_disco', DB::raw('count(*) as total'))
                ->whereNotNull('tipo_disco')
                ->groupBy('tipo_disco')
                ->orderBy('total', 'desc')
                ->get(),

            'ultimos_mantenimientos' => Mantenimiento::with('dispositivo')
                ->orderBy('fecha', 'desc')
                ->limit(8)
                ->get(),
        ];

        return view('reportes.index', compact('stats'));
    }

    public function exportar()
    {
        return Excel::download(new InventarioGeneralExport, 'Inventario_GITIC_Casanare.xlsx');
    }
}