<?php

namespace App\Http\Controllers;

use App\Models\Dispositivo;
use App\Models\Mantenimiento;
use App\Models\Ubicacion;
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

        // Datos para los dropdowns de ubicación (todos los valores únicos)
        $ubicacionOpciones = [
            'sedes'    => Ubicacion::distinct()->orderBy('sede')->pluck('sede'),
            'bloques'  => Ubicacion::distinct()->orderBy('bloque')->whereNotNull('bloque')->pluck('bloque'),
            'ambientes'=> Ubicacion::distinct()->orderBy('ambiente')->pluck('ambiente'),
            // Relaciones completas para el cascading JS
            'todas'    => Ubicacion::select('sede', 'bloque', 'ambiente')->orderBy('sede')->orderBy('bloque')->orderBy('ambiente')->get(),
        ];

        return view('reportes.index', compact('stats', 'ubicacionOpciones'));
    }

    public function ubicacionStats(Request $request)
    {
        $sede     = $request->input('sede');
        $bloque   = $request->input('bloque');
        $ambiente = $request->input('ambiente');
        $intune   = $request->input('intune');

        $query = DB::table('ubicaciones')
            ->join('dispositivos', 'ubicaciones.id', '=', 'dispositivos.ubicacion_id');

        if ($sede)     $query->where('ubicaciones.sede', $sede);
        if ($bloque)   $query->where('ubicaciones.bloque', $bloque);
        if ($ambiente) $query->where('ubicaciones.ambiente', $ambiente);
        if ($intune)   $query->where('dispositivos.en_intune', $intune);

        // Sufijo para el título cuando hay filtro Intune activo
        $sufIntune = $intune ? (' · Intune: ' . ($intune === 'SI' ? 'Enrolados' : 'No enrolados')) : '';

        // Nivel de agrupación progresivo según filtros activos
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
            $datos = $query->select(
                    DB::raw('UPPER(TRIM(ubicaciones.sede)) as etiqueta'),
                    DB::raw('count(dispositivos.id) as total')
                )
                ->groupBy('ubicaciones.sede')
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
}