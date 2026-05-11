<?php

namespace App\Http\Controllers;

use App\Models\Dispositivo;
use App\Models\Mantenimiento;
use App\Models\Responsable;
use App\Models\Ubicacion;
use App\Models\Sede;
use Illuminate\Http\Request;
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
            'total_general'      => $totalGeneral,
            'intune_enrolados'   => $intuneEnrolados,
            'intune_pendientes'  => $totalGeneral - $intuneEnrolados,
            'en_reparacion'      => Dispositivo::where('estado_fisico', 'En Reparación')->count(),
            'mantenimientos_mes' => Mantenimiento::whereYear('fecha', $ahora->year)
                                       ->whereMonth('fecha', $ahora->month)
                                       ->count(),

            'por_sede' => DB::table('sedes')
                ->join('ubicaciones', 'sedes.id', '=', 'ubicaciones.sede_id')
                ->join('dispositivos', 'ubicaciones.id', '=', 'dispositivos.ubicacion_id')
                ->select('sedes.nombre as sede_nombre', DB::raw('count(dispositivos.id) as total'))
                ->groupBy('sedes.id', 'sedes.nombre')
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

        // Datos para los dropdowns de ubicación (cascading JS)
        $ubicacionOpciones = [
            'sedes'    => Sede::orderBy('nombre')->pluck('nombre'),
            'todas'    => Ubicacion::with('sede')
                            ->orderBy('bloque')
                            ->orderBy('ambiente')
                            ->get()
                            ->map(fn($u) => [
                                'sede'     => $u->sede->nombre ?? '',
                                'bloque'   => $u->bloque,
                                'ambiente' => $u->ambiente,
                            ]),
        ];

        return view('reportes.index', compact('stats', 'ubicacionOpciones'));
    }

    public function ubicacionStats(Request $request)
    {
        $sede     = $request->input('sede');
        $bloque   = $request->input('bloque');
        $ambiente = $request->input('ambiente');
        $intune   = $request->input('intune');

        $query = DB::table('sedes')
            ->join('ubicaciones', 'sedes.id', '=', 'ubicaciones.sede_id')
            ->join('dispositivos', 'ubicaciones.id', '=', 'dispositivos.ubicacion_id');

        if ($sede)     $query->where('sedes.nombre', $sede);
        if ($bloque)   $query->where('ubicaciones.bloque', $bloque);
        if ($ambiente) $query->where('ubicaciones.ambiente', $ambiente);
        if ($intune)   $query->where('dispositivos.en_intune', $intune);

        $sufIntune = $intune ? (' · Intune: ' . ($intune === 'SI' ? 'Enrolados' : 'No enrolados')) : '';

        if ($ambiente) {
            $datos = $query->select('dispositivos.estado_fisico as etiqueta', DB::raw('count(dispositivos.id) as total'))
                ->groupBy('dispositivos.estado_fisico')
                ->orderBy('total', 'desc')
                ->get();
            $titulo = "Estado físico · {$sede} › {$bloque} › {$ambiente}{$sufIntune}";
        } elseif ($bloque) {
            $datos = $query->select('ubicaciones.ambiente as etiqueta', DB::raw('count(dispositivos.id) as total'))
                ->groupBy('ubicaciones.ambiente')
                ->orderBy('total', 'desc')
                ->get();
            $titulo = "Por ambiente · {$sede} › {$bloque}{$sufIntune}";
        } elseif ($sede) {
            $datos = $query->select(
                    DB::raw("COALESCE(NULLIF(TRIM(ubicaciones.bloque),''), 'Sin bloque') as etiqueta"),
                    DB::raw('count(dispositivos.id) as total')
                )
                ->groupBy('ubicaciones.bloque')
                ->orderBy('total', 'desc')
                ->get();
            $titulo = "Por bloque · {$sede}{$sufIntune}";
        } else {
            $datos = $query->select('sedes.nombre as etiqueta', DB::raw('count(dispositivos.id) as total'))
                ->groupBy('sedes.id', 'sedes.nombre')
                ->orderBy('total', 'desc')
                ->get();
            $titulo = 'Equipos por sede' . $sufIntune;
        }

        return response()->json([
            'labels' => $datos->pluck('etiqueta'),
            'values' => $datos->pluck('total'),
            'titulo' => $titulo,
        ]);
    }

    public function exportar()
    {
        return Excel::download(new InventarioGeneralExport, 'Inventario_GITIC_Casanare.xlsx');
    }

    public function responsablesPDF()
    {
        ini_set('max_execution_time', 120);

        $totalDispositivos  = Dispositivo::count();
        $totalResponsables  = Responsable::whereHas('dispositivos')->count();
        $totalSedes         = Sede::count();
        $totalUbicaciones   = Ubicacion::count();

        $porSede = DB::table('sedes')
            ->join('ubicaciones', 'sedes.id', '=', 'ubicaciones.sede_id')
            ->join('dispositivos', 'ubicaciones.id', '=', 'dispositivos.ubicacion_id')
            ->select('sedes.nombre', DB::raw('count(dispositivos.id) as total'))
            ->groupBy('sedes.id', 'sedes.nombre')
            ->orderByDesc('total')
            ->get();

        $porEstado = Dispositivo::select('estado_fisico', DB::raw('count(*) as total'))
            ->whereNotNull('estado_fisico')
            ->groupBy('estado_fisico')
            ->orderByDesc('total')
            ->get();

        $porCategoria = Dispositivo::select('categoria', DB::raw('count(*) as total'))
            ->whereNotNull('categoria')
            ->groupBy('categoria')
            ->orderByDesc('total')
            ->get();

        $responsables = Responsable::whereHas('dispositivos')
            ->with(['dispositivos' => function ($q) {
                $q->with('ubicacion.sede')
                  ->select('id', 'responsable_id', 'placa', 'hostname', 'marca', 'modelo',
                            'categoria', 'estado_fisico', 'en_intune', 'ubicacion_id')
                  ->orderBy('placa');
            }])
            ->orderBy('nombre')
            ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reportes.responsables_pdf', compact(
            'totalDispositivos', 'totalResponsables', 'totalSedes', 'totalUbicaciones',
            'porSede', 'porEstado', 'porCategoria', 'responsables'
        ));
        $pdf->setPaper('letter', 'landscape');

        return $pdf->download('Reporte_Equipos_por_Responsable_' . now()->format('Ymd_His') . '.pdf');
    }
}
