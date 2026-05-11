<?php

namespace App\Http\Controllers;

use App\Models\AreaSegura;
use App\Models\AreaSeguraVerificacion;
use App\Models\Sede;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AreaSeguraController extends Controller
{
    public function index()
    {
        $areas = AreaSegura::with(['ultimaVerificacion', 'sede'])
            ->orderByRaw("CASE nivel_sena
                WHEN 'Nivel 1 - Crítico'   THEN 1
                WHEN 'Nivel 2 - Sensible'  THEN 2
                WHEN 'Nivel 3 - Operativo' THEN 3
                ELSE 4 END")
            ->orderBy('codigo')
            ->get();

        $stats = [
            'total'         => $areas->count(),
            'nivel1'        => $areas->where('nivel_sena', 'Nivel 1 - Crítico')->count(),
            'nivel2'        => $areas->where('nivel_sena', 'Nivel 2 - Sensible')->count(),
            'nivel3'        => $areas->where('nivel_sena', 'Nivel 3 - Operativo')->count(),
            'con_checklist' => $areas->filter(fn($a) => $a->ultimaVerificacion)->count(),
        ];

        $nivelesSena = AreaSegura::NIVELES_SENA;

        return view('areas_seguras.index', compact('areas', 'stats', 'nivelesSena'));
    }

    public function create()
    {
        $sedes = Sede::orderBy('nombre')->pluck('nombre', 'id');
        $nivelesSena = AreaSegura::NIVELES_SENA;
        return view('areas_seguras.create', compact('sedes', 'nivelesSena'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'codigo'             => ['required','string','max:30','unique:areas_seguras,codigo'],
            'nombre_dependencia' => ['required','string','max:200'],
            'sede_id'            => ['nullable','exists:sedes,id'],
            'nivel_criticidad'   => ['required','in:Bajo,Medio,Alto'],
            'nivel_sena'         => ['required','in:Nivel 1 - Crítico,Nivel 2 - Sensible,Nivel 3 - Operativo'],
            'responsable_cargo'  => ['required','string','max:150'],
            'perimetro_seguridad'=> ['required','string','max:200'],
            'controles_acceso'   => ['required','array','min:1'],
            'horario_acceso'     => ['required','in:Jornada laboral,24/7,Restringido'],
        ], [
            'codigo.unique'        => 'Ya existe un área con ese código.',
            'controles_acceso.min' => 'Selecciona al menos un control de acceso.',
        ]);

        AreaSegura::create($request->merge([
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ])->all());

        return redirect()->route('areas-seguras.index')
            ->with('success', "Área segura {$request->codigo} registrada.");
    }

    public function show(AreaSegura $areasSegura)
    {
        $areasSegura->load(['verificaciones.verificador', 'creador', 'editor', 'sede']);
        return view('areas_seguras.show', ['area' => $areasSegura]);
    }

    public function edit(AreaSegura $areasSegura)
    {
        $sedes = Sede::orderBy('nombre')->pluck('nombre', 'id');
        $nivelesSena = AreaSegura::NIVELES_SENA;
        return view('areas_seguras.edit', ['area' => $areasSegura, 'sedes' => $sedes, 'nivelesSena' => $nivelesSena]);
    }

    public function update(Request $request, AreaSegura $areasSegura)
    {
        $request->validate([
            'codigo'             => ['required','string','max:30','unique:areas_seguras,codigo,'.$areasSegura->id],
            'nombre_dependencia' => ['required','string','max:200'],
            'sede_id'            => ['nullable','exists:sedes,id'],
            'nivel_criticidad'   => ['required','in:Bajo,Medio,Alto'],
            'nivel_sena'         => ['required','in:Nivel 1 - Crítico,Nivel 2 - Sensible,Nivel 3 - Operativo'],
            'responsable_cargo'  => ['required','string','max:150'],
            'perimetro_seguridad'=> ['required','string','max:200'],
            'controles_acceso'   => ['required','array','min:1'],
            'horario_acceso'     => ['required','in:Jornada laboral,24/7,Restringido'],
        ]);

        $areasSegura->update($request->merge(['updated_by' => Auth::id()])->all());

        return redirect()->route('areas-seguras.show', $areasSegura)
            ->with('success', 'Área segura actualizada.');
    }

    public function destroy(AreaSegura $areasSegura)
    {
        $areasSegura->delete();
        return redirect()->route('areas-seguras.index')
            ->with('success', "Área {$areasSegura->codigo} eliminada.");
    }

    // ── Checklist ──────────────────────────────────────────────────────────────

    public function crearVerificacion(AreaSegura $areasSegura)
    {
        $items = AreaSegura::CHECKLIST_ITEMS;
        return view('areas_seguras.verificacion', ['area' => $areasSegura, 'items' => $items]);
    }

    public function guardarVerificacion(Request $request, AreaSegura $areasSegura)
    {
        $request->validate([
            'fecha_verificacion'    => ['required','date'],
            'corte'                 => ['required','string','max:50'],
            'items'                 => ['required','array'],
            'observaciones_generales' => ['nullable','string'],
        ]);

        $itemsBase = AreaSegura::CHECKLIST_ITEMS;
        $itemsResult = [];
        $cumpleCount = 0;

        foreach ($itemsBase as $i => $base) {
            $cumple = $request->input("items.{$i}.cumple", 'N');
            if ($cumple === 'S') $cumpleCount++;
            $itemsResult[] = array_merge($base, [
                'cumple'        => $cumple,
                'observaciones' => $request->input("items.{$i}.observaciones", ''),
            ]);
        }

        $total = count($itemsBase);
        $resultado = match(true) {
            $cumpleCount === $total                         => 'Conforme',
            $cumpleCount === 0                              => 'No Conforme',
            default                                         => 'Conforme con Observaciones',
        };

        AreaSeguraVerificacion::create([
            'area_segura_id'         => $areasSegura->id,
            'fecha_verificacion'     => $request->fecha_verificacion,
            'corte'                  => $request->corte,
            'items'                  => $itemsResult,
            'total_cumple'           => $cumpleCount,
            'total_items'            => $total,
            'resultado'              => $resultado,
            'observaciones_generales'=> $request->observaciones_generales,
            'verificado_por'         => Auth::id(),
        ]);

        return redirect()->route('areas-seguras.show', $areasSegura)
            ->with('success', "Verificación guardada — {$cumpleCount}/{$total} ítems conformes.");
    }
}
