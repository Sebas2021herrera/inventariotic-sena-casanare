<?php

namespace App\Http\Controllers;

use App\Models\Dispositivo;
use App\Models\Ubicacion;
use Illuminate\Support\Facades\DB;
use App\Exports\InventarioGeneralExport;
use Maatwebsite\Excel\Facades\Excel;

class ReporteController extends Controller
{
    public function index()
    {
        $stats = [
            'total_general' => Dispositivo::count(),
            'intune' => Dispositivo::select('en_intune', DB::raw('count(*) as total'))->groupBy('en_intune')->get(),
            'por_sede' => DB::table('ubicaciones')
            ->join('dispositivos', 'ubicaciones.id', '=', 'dispositivos.ubicacion_id')
            ->select(
                DB::raw('UPPER(TRIM(sede)) as sede_nombre'), 
                DB::raw('count(dispositivos.id) as total')
            )
            ->groupBy(DB::raw('UPPER(TRIM(sede))')) // Agrupamos por el nombre normalizado
            ->orderBy('total', 'desc')
            ->get(),
            'estado_fisico' => Dispositivo::select('estado_fisico', DB::raw('count(*) as total'))->groupBy('estado_fisico')->get(),
            'propietario' => Dispositivo::select('propietario', DB::raw('count(*) as total'))->groupBy('propietario')->get(),
            'ram' => DB::table('especificaciones')->select('ram', DB::raw('count(*) as total'))->groupBy('ram')->get(),
            'discos' => DB::table('especificaciones')->select('tipo_disco', DB::raw('count(*) as total'))->groupBy('tipo_disco')->get(),
            'funcion' => Dispositivo::select('funcion', DB::raw('count(*) as total'))->groupBy('funcion')->get(),
        ];

        return view('reportes.index', compact('stats'));
    }

    public function exportar()
    {
        return Excel::download(new InventarioGeneralExport, 'Inventario_GITIC_Casanare.xlsx');
    }
}