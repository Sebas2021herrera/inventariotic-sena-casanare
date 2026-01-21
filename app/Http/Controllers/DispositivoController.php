<?php

namespace App\Http\Controllers;

use App\Models\Dispositivo;
use Illuminate\Http\Request;
use App\Imports\InventarioSenaImport; // Esto le dice a Laravel dónde buscar la clase
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use App\Exports\PlantillaInventarioExport;

class DispositivoController extends Controller
{
  public function index()
{
    // Consultas robustas que ignoran mayúsculas/minúsculas y espacios
    $stats = [
        'total' => \App\Models\Dispositivo::count(),
        
        // Usamos ILIKE para que ignore si es BUENO, bueno o Bueno
        'buenos' => \App\Models\Dispositivo::where('estado_fisico', 'ILIKE', 'Bueno%')->count(),
        
        // Contamos como novedades todo lo que NO sea "Bueno"
        'criticos' => \App\Models\Dispositivo::where('estado_fisico', 'NOT ILIKE', 'Bueno%')->count(),
        
        'sedes' => \App\Models\Ubicacion::distinct('sede')->count(),
    ];

    $dispositivos = \App\Models\Dispositivo::with(['responsable', 'ubicacion'])
                    ->latest()
                    ->paginate(15);

    return view('dispositivos.index', compact('dispositivos', 'stats'));
}


    public function importar(Request $request)
    {
        $request->validate(['archivo' => 'required|mimes:xlsx,xls,csv']);

        try {
            \Maatwebsite\Excel\Facades\Excel::import(new InventarioSenaImport, $request->file('archivo'));
            return back()->with('success', '¡Inventario cargado exitosamente!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al importar: ' . $e->getMessage()]);
        }
    }
    /**
 * Muestra el detalle de un dispositivo específico
 */
public function show(Dispositivo $dispositivo)
{
    // Cargamos las relaciones para que la vista tenga toda la información
    $dispositivo->load(['responsable', 'ubicacion', 'especificaciones']);

    return view('dispositivos.show', compact('dispositivo'));
}
// ... dentro de la clase DispositivoController

public function create()
{
    // Obtenemos responsables y ubicaciones existentes para sugerir en el formulario
    $responsables = \App\Models\Responsable::all();
    $ubicaciones = \App\Models\Ubicacion::all();
    
    return view('dispositivos.create', compact('responsables', 'ubicaciones'));
}

public function store(Request $request)
{
    // 1. Validación estricta para campos críticos
    $request->validate([
        'placa' => 'required|unique:dispositivos,placa',
        'cedula' => 'required',
        'nombre_responsable' => 'required',
        'sede' => 'required',
        'ambiente' => 'required',
    ]);

    try {
        DB::beginTransaction();

        // 2. RESPONSABLE: Blindamos contra nulos en dependencia y cargo
        $responsable = \App\Models\Responsable::updateOrCreate(
            ['cedula' => $request->cedula],
            [
                'nombre' => $request->nombre_responsable,
                'correo_institucional' => $request->correo_institucional ?? 'sin_correo@sena.edu.co',
                'dependencia' => $request->dependencia ?? 'General', 
                'cargo' => $request->cargo ?? 'N/A',
                'tipo_funcionario' => $request->tipo_funcionario ?? 'Contratista',
                'numero_de_celular' => $request->numero_de_celular ?? null,
            ]
        );

        // 3. UBICACIÓN
        $ubicacion = \App\Models\Ubicacion::firstOrCreate([
            'sede' => $request->sede,
            'bloque' => $request->bloque ?? 'N/A',
            'ambiente' => $request->ambiente,
        ]);

        // 4. DISPOSITIVO
        $dispositivo = \App\Models\Dispositivo::create([
            'placa' => $request->placa,
            'serial' => $request->serial,
            'marca' => $request->marca ?? 'Genérico',
            'modelo' => $request->modelo ?? 'Genérico',
            'categoria' => $request->categoria ?? 'computo',
            'estado_fisico' => $request->estado_fisico ?? 'Bueno',
            'estado_logico' => $request->estado_logico ?? 'Bueno',
            'observaciones' => $request->observaciones,
            'responsable_id' => $responsable->id,
            'ubicacion_id' => $ubicacion->id,
        ]);

        // 5. ESPECIFICACIONES: Todos estos pueden ser null o N/A
        \App\Models\Especificacion::create([
            'dispositivo_id' => $dispositivo->id,
            'procesador' => $request->procesador ?? 'N/A',
            'ram' => $request->ram ?? 'N/A',
            'tipo_disco' => $request->tipo_disco ?? 'N/A',
            'capacidad_disco' => $request->capacidad_disco ?? 'N/A',
            'so' => $request->so ?? 'N/A',
            'mac_address' => $request->mac_address ?? 'N/A',
            'placa_monitor' => $request->placa_monitor ?? null,
            'serial_cargador' => $request->serial_cargador ?? null,
        ]);

        DB::commit();
        return redirect()->route('dispositivos.index')->with('success', 'Equipo registrado correctamente.');

    } catch (\Exception $e) {
        DB::rollback();
        return back()->withErrors(['error' => 'Error crítico: ' . $e->getMessage()])->withInput();
    }
}
public function descargarPlantilla()
{
    return Excel::download(new PlantillaInventarioExport, 'plantilla_inventario_sena.xlsx');
}
public function destroy(Dispositivo $dispositivo)
{
    try {
        // 1. Eliminamos primero las especificaciones asociadas (si existen)
        if ($dispositivo->especificaciones) {
            $dispositivo->especificaciones()->delete();
        }

        // 2. Eliminamos el dispositivo
        $dispositivo->delete();

        return redirect()->route('dispositivos.index')
            ->with('success', 'El dispositivo y sus especificaciones han sido eliminados correctamente.');
            
    } catch (\Exception $e) {
        return back()->withErrors(['error' => 'No se pudo eliminar el equipo: ' . $e->getMessage()]);
    }
}

}