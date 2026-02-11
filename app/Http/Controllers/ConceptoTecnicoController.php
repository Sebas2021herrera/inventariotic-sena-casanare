<?php

namespace App\Http\Controllers;
use App\Models\Dispositivo;
use App\Models\ConceptoTecnico;
use Barryvdh\DomPDF\Facade\Pdf; // Importar la fachada al principio

use Illuminate\Http\Request;

class ConceptoTecnicoController extends Controller
{
    public function create(Dispositivo $dispositivo)
{
    // Cargamos todas las relaciones necesarias
    $dispositivo->load(['responsable', 'especificaciones', 'perifericos', 'ubicacion']);
    
    // El estándar de Hostname del SENA suele ser SENA-PLACA
    $sugerenciaHostname = "SENA-" . $dispositivo->placa;

    return view('conceptos.create', compact('dispositivo', 'sugerenciaHostname'));
}

public function store(Request $request)
{
    // 1. Agrega este dd para ver si los datos están llegando al servidor
    // dd($request->all()); 

    $validated = $request->validate([
        'dispositivo_id' => 'required',
        'tipo_equipo' => 'required',
        'hostname' => 'required',
        'fecha_reporte' => 'required|date',
        'descripcion_solicitud' => 'required',
        'diagnostico_tecnico' => 'required', // Bajamos la restricción para probar
        'tecnico_nombre' => 'required',
        'flujo_solicitud' => 'required',
        'concepto_tipo' => 'required',
    ]);

    try {
        $datos = $request->all();
        
        // Convertimos el checkbox a booleano real
        $datos['requiere_contingencia'] = $request->has('requiere_contingencia');

        // Creamos el registro
        \App\Models\ConceptoTecnico::create($datos);

        return redirect()->route('dispositivos.show', $request->dispositivo_id)
                         ->with('success', 'Reporte GTI-F-132 guardado correctamente.');

    } catch (\Exception $e) {
        // Si hay un error de base de datos, lo veremos aquí
        return back()->withErrors(['error' => 'Error al guardar: ' . $e->getMessage()])->withInput();
    }
}

// app/Http/Controllers/ConceptoTecnicoController.php

public function exportarPDF($id)
{
    // 1. Obtener el reporte con todas sus relaciones
    $concepto = ConceptoTecnico::with([
        'dispositivo.responsable', 
        'dispositivo.especificaciones', 
        'dispositivo.perifericos',
        'dispositivo.ubicacion'
    ])->findOrFail($id);

    // 2. Preparar los datos para la vista
    $pdf = Pdf::loadView('conceptos.pdf', compact('concepto'));

    // 3. Configurar papel horizontal o vertical según el formato SENA (GTI-F-132 suele ser vertical)
    $pdf->setPaper('letter', 'portrait');

    // 4. Retornar el flujo del archivo
    return $pdf->download('GTI-F-132_Placa_' . $concepto->dispositivo->placa . '.pdf');
}

}
