<?php

namespace App\Http\Controllers;

use App\Models\Dispositivo;
use App\Models\Responsable;
use App\Models\Ubicacion;
use App\Models\Especificacion;
use App\Models\Periferico; // Asegúrate de importar el modelo
use Illuminate\Http\Request;
use App\Imports\InventarioSenaImport; // Esto le dice a Laravel dónde buscar la clase
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use App\Exports\PlantillaInventarioExport;


class DispositivoController extends Controller
{
    

    public function index(Request $request)
    {
        // 1. Capturamos el término de búsqueda
        $search = $request->input('search');
    
        // 2. Iniciamos la consulta base con sus relaciones
        $query = \App\Models\Dispositivo::with(['responsable', 'ubicacion']);
    
        // 3. Aplicamos el filtro solo si hay algo escrito en el buscador
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('placa', 'ILIKE', "%{$search}%")
                  ->orWhere('serial', 'ILIKE', "%{$search}%");
            });
        }
    
        // 4. Ejecutamos la paginación incluyendo los parámetros de búsqueda en los links
                $dispositivos = $query->orderBy('updated_at', 'desc') 
                ->paginate(15)
                ->withQueryString();
    
        // 5. Mantenemos tus estadísticas robustas (estas se calculan sobre el total)
        $stats = [
            'total' => \App\Models\Dispositivo::count(),
            
            'buenos' => \App\Models\Dispositivo::where('estado_fisico', 'ILIKE', 'Bueno%')->count(),
            
            'criticos' => \App\Models\Dispositivo::where('estado_fisico', 'NOT ILIKE', 'Bueno%')->count(),
            
            'sedes' => \App\Models\Ubicacion::distinct('sede')->count(),
        ];
    
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
    // Cargamos todas las relaciones necesarias para que la vista tenga la data de hardware, 
    // responsables, periféricos, mantenimientos y los nuevos conceptos GTI-F-132.
    $dispositivo->load([
        'responsable', 
        'ubicacion', 
        'especificaciones', 
        'perifericos', 
        'mantenimientos', 
        'conceptos' // 👈 Esta es la que causaba el error
    ]);

    return view('dispositivos.show', compact('dispositivo'));
}

public function create()
{
    // Obtenemos responsables y ubicaciones existentes para sugerir en el formulario
    $responsables = \App\Models\Responsable::all();
    $ubicaciones = \App\Models\Ubicacion::all();
    
    return view('dispositivos.create', compact('responsables', 'ubicaciones'));
}

public function store(Request $request)


    {
        // 1. Validación con los nuevos campos institucionales
        $request->validate([
            'placa' => 'required|unique:dispositivos,placa',
            'serial' => 'required',
            'cedula' => 'required',
            'nombre_responsable' => 'required',
            'sede' => 'required',
            'ambiente' => 'required',
        ]);

        try {
            DB::beginTransaction();

            // 2. RESPONSABLE: Actualizar o crear
            $responsable = Responsable::updateOrCreate(
                ['cedula' => $request->cedula],
                [
                    'nombre' => $request->nombre_responsable,
                    'correo_institucional' => $request->correo_institucional ?? 'sin_correo@sena.edu.co',
                    'dependencia' => $request->dependencia ?? 'General', 
                    'cargo' => $request->cargo ?? 'N/A',
                    'tipo_funcionario' => $request->tipo_funcionario ?? 'Contratista',
                    'numero_de_celular' => $request->numero_de_celular,
                ]
            );

            // 3. UBICACIÓN
            $ubicacion = Ubicacion::firstOrCreate([
                'sede' => $request->sede,
                'bloque' => $request->bloque ?? 'N/A',
                'ambiente' => $request->ambiente,
            ]);

            // 4. DISPOSITIVO: Incluyendo Propietario, Función e Intune
            $dispositivo = Dispositivo::create([
                'placa' => $request->placa,
                'serial' => $request->serial,
                'marca' => $request->marca ?? 'Genérico',
                'modelo' => $request->modelo ?? 'Genérico',
                'categoria' => $request->categoria ?? 'computo',
                'estado_fisico' => $request->estado_fisico ?? 'BUENO',
                'estado_logico' => $request->estado_logico ?? 'BUENO',
                'propietario' => $request->propietario ?? 'SENA',
                'funcion' => $request->funcion ?? 'FORMACION',
                'en_intune' => $request->en_intune ?? 'NO',
                'observaciones' => $request->observaciones,
                'responsable_id' => $responsable->id,
                'ubicacion_id' => $ubicacion->id,
            ]);

            // 5. ESPECIFICACIONES (Tabla relacionada)
            Especificacion::create([
                'dispositivo_id' => $dispositivo->id,
                'procesador' => $request->procesador ?? 'N/A',
                'ram' => $request->ram ?? 'N/A',
                'tipo_disco' => $request->tipo_disco ?? 'N/A',
                'capacidad_disco' => $request->capacidad_disco ?? 'N/A',
                'so' => $request->so ?? 'N/A',
                'mac_address' => $request->mac_address ?? 'N/A',
            ]);

            // 6. PERIFÉRICOS: Guardado dinámico del array enviado desde el formulario
            if ($request->has('perifericos')) {
                foreach ($request->perifericos as $tipo => $datos) {
                    // Solo creamos el registro si el usuario escribió al menos la Placa o el Serial
                    if (!empty($datos['placa']) || !empty($datos['serial'])) {
                        Periferico::create([
                            'dispositivo_id' => $dispositivo->id,
                            'tipo' => $tipo, // Monitor, Teclado, Mouse, Cargador
                            'placa' => $datos['placa'] ?? 'N/A',
                            'serial' => $datos['serial'] ?? 'N/A',
                            'marca' => $datos['marca'] ?? 'N/A',
                            'modelo' => $datos['modelo'] ?? 'N/A',
                            'estado' => 'BUENO'
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('dispositivos.index')
                             ->with('success', "Equipo con placa {$dispositivo->placa} registrado exitosamente.");

        } catch (\Exception $e) {
            DB::rollback();
            // Retornamos el error específico para debug, pero podrías personalizarlo
            return back()->withErrors(['error' => 'Error al guardar en base de datos: ' . $e->getMessage()])->withInput();
        }
    }



public function descargarPlantilla()
{
    return Excel::download(new PlantillaInventarioExport, 'plantilla_inventario_sena.xlsx');
}
public function destroy(Dispositivo $dispositivo)
{
    // 1. Bloque de seguridad: Solo el rol 'admin' puede proceder
    if (auth()->user()?->role !== 'admin') {
        return redirect()->back()->with('error', 'Acceso denegado: No tienes permisos de administrador para eliminar activos.');
    }

    try {
        DB::beginTransaction(); // Recomendado para asegurar que se borre todo o nada

        // 2. Eliminamos las especificaciones asociadas
        if ($dispositivo->especificaciones) {
            $dispositivo->especificaciones()->delete();
        }
        
        // 3. Eliminamos periféricos asociados (si existen) para evitar errores de integridad
        if ($dispositivo->perifericos()->count() > 0) {
            $dispositivo->perifericos()->delete();
        }

        // 4. Eliminamos el dispositivo
        $dispositivo->delete();

        DB::commit();

        return redirect()->route('dispositivos.index')
            ->with('success', 'El dispositivo y todos sus registros asociados han sido eliminados correctamente.');
            
    } catch (\Exception $e) {
        DB::rollback();
        return back()->withErrors(['error' => 'No se pudo completar la eliminación: ' . $e->getMessage()]);
    }
}

public function edit(Dispositivo $dispositivo)
{
    // Cargamos las relaciones para que el formulario tenga la data
    $dispositivo->load(['responsable', 'ubicacion', 'especificaciones', 'perifericos']);
    return view('dispositivos.edit', compact('dispositivo'));
}

public function update(Request $request, Dispositivo $dispositivo)
{
    // 1. VALIDACIÓN
    // Importante: En 'placa' permitimos que sea la misma del dispositivo actual ($dispositivo->id)
    $request->validate([
        'placa' => 'required|unique:dispositivos,placa,' . $dispositivo->id,
        'serial' => 'required',
        'cedula' => 'required',
        'nombre_responsable' => 'required',
        'sede' => 'required',
        'ambiente' => 'required',
    ]);

    try {
        DB::beginTransaction();

        // 2. ACTUALIZAR O ASIGNAR NUEVO RESPONSABLE
        $responsable = Responsable::updateOrCreate(
            ['cedula' => $request->cedula],
            [
                'nombre' => $request->nombre_responsable,
                'correo_institucional' => $request->correo_institucional ?? 'sin_correo@sena.edu.co',
                'dependencia' => $request->dependencia ?? 'General',
                'cargo' => $request->cargo ?? 'N/A',
                'tipo_funcionario' => $request->tipo_funcionario ?? 'Contratista',
                'numero_de_celular' => $request->numero_de_celular,
            ]
        );

        // 3. ACTUALIZAR UBICACIÓN
        $ubicacion = Ubicacion::firstOrCreate([
            'sede' => $request->sede,
            'bloque' => $request->bloque ?? 'N/A',
            'ambiente' => $request->ambiente,
        ]);

        // 4. ACTUALIZAR DATOS BÁSICOS DEL DISPOSITIVO
        $dispositivo->update([
            'placa' => $request->placa,
            'serial' => $request->serial,
            'marca' => $request->marca,
            'modelo' => $request->modelo,
            'categoria' => $request->categoria,
            'estado_fisico' => $request->estado_fisico,
            'propietario' => $request->propietario,
            'funcion' => $request->funcion,
            'en_intune' => $request->en_intune,
            'observaciones' => $request->observaciones,
            'responsable_id' => $responsable->id,
            'ubicacion_id' => $ubicacion->id,
        ]);

        // 5. ACTUALIZAR ESPECIFICACIONES (Relación hasOne)
        // Usamos updateOrCreate por si el equipo no tenía especificaciones previas
        $dispositivo->especificaciones()->updateOrCreate(
            ['dispositivo_id' => $dispositivo->id],
            [
                'procesador' => $request->procesador ?? 'N/A',
                'ram' => $request->ram ?? 'N/A',
                'tipo_disco' => $request->tipo_disco ?? 'N/A',
                'capacidad_disco' => $request->capacidad_disco ?? 'N/A',
                'so' => $request->so ?? 'N/A',
                'mac_address' => $request->mac_address ?? 'N/A',
            ]
        );

        // 6. ACTUALIZAR PERIFÉRICOS (Relación hasMany)
        if ($request->has('perifericos')) {
            foreach ($request->perifericos as $tipo => $datos) {
                if (!empty($datos['placa']) || !empty($datos['serial'])) {
                    $dispositivo->perifericos()->updateOrCreate(
                        ['tipo' => $tipo], // Busca por tipo (Monitor, Teclado, etc.) para este equipo
                        [
                            'placa' => $datos['placa'] ?? 'N/A',
                            'serial' => $datos['serial'] ?? 'N/A',
                            'estado' => 'BUENO'
                        ]
                    );
                }
            }
        }

        DB::commit();
        
        return redirect()->route('dispositivos.show', $dispositivo)
                         ->with('success', "Equipo de la Regional Casanare actualizado con éxito.");

    } catch (\Exception $e) {
        DB::rollback();
        return back()->withErrors(['error' => 'Error al actualizar: ' . $e->getMessage()])->withInput();
    }
}

public function verificarPlaca($placa)
{
    // Buscamos si la placa ya existe en la tabla dispositivos
    $existe = \App\Models\Dispositivo::where('placa', $placa)->exists();
    
    return response()->json(['exists' => $existe]);

}




}