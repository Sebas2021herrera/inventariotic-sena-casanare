<?php

namespace App\Http\Controllers;

use App\Models\AreaSegura;
use App\Models\AreaSeguraVerificacion;
use App\Models\Sede;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\AreaSeguraExport;
use Maatwebsite\Excel\Facades\Excel;

class AreaSeguraController extends Controller
{
    public function index()
    {
        $areas = AreaSegura::with(['ultimaVerificacion', 'sede'])
            ->orderByRaw("CASE nivel_sena
                WHEN 'Nivel 3' THEN 1
                WHEN 'Nivel 2' THEN 2
                WHEN 'Nivel 1' THEN 3
                ELSE 4 END")
            ->orderBy('codigo')
            ->get();

        $stats = [
            'total'         => $areas->count(),
            'nivel1'        => $areas->where('nivel_sena', 'Nivel 1')->count(),
            'nivel2'        => $areas->where('nivel_sena', 'Nivel 2')->count(),
            'nivel3'        => $areas->where('nivel_sena', 'Nivel 3')->count(),
            'con_checklist' => $areas->filter(fn($a) => $a->ultimaVerificacion)->count(),
        ];

        $nivelesSena = AreaSegura::NIVELES_SENA;

        return view('areas_seguras.index', compact('areas', 'stats', 'nivelesSena'));
    }

    public function create()
    {
        $sedes      = Sede::orderBy('nombre')->pluck('nombre', 'id');
        $nivelesSena = AreaSegura::NIVELES_SENA;
        return view('areas_seguras.create', compact('sedes', 'nivelesSena'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'codigo'                 => ['required','string','max:30','unique:areas_seguras,codigo'],
            'nombre_dependencia'     => ['required','string','max:200'],
            'sede_id'                => ['nullable','exists:sedes,id'],
            'nivel_sena'             => ['required','in:Nivel 1,Nivel 2,Nivel 3'],
            'nivel_criticidad'       => ['required','in:Bajo,Medio,Alto'],
            'responsable_cargo'      => ['required','string','max:150'],
            'responsable_nombre'     => ['nullable','string','max:150'],
            'responsable_contacto'   => ['nullable','string','max:100'],
            'perimetro_seguridad'    => ['required','string','max:200'],
            'controles_acceso'       => ['required','array','min:1'],
            'controles_monitoreo'    => ['nullable','array'],
            'horario_acceso'         => ['required','in:Jornada laboral,24/7,Restringido'],
            'zonificacion'           => ['nullable','string','max:40'],
            'clasificacion_informacion' => ['nullable','in:Pública,Pública Clasificada,Pública Reservada'],
        ], [
            'codigo.unique'        => 'Ya existe un área con ese código.',
            'controles_acceso.min' => 'Selecciona al menos un control de acceso.',
        ]);

        AreaSegura::create(array_merge($request->except('_token'), [
            'created_by'      => Auth::id(),
            'updated_by'      => Auth::id(),
            'fecha_inventario' => $request->fecha_inventario ?: now()->toDateString(),
        ]));

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
        $sedes      = Sede::orderBy('nombre')->pluck('nombre', 'id');
        $nivelesSena = AreaSegura::NIVELES_SENA;
        return view('areas_seguras.edit', ['area' => $areasSegura, 'sedes' => $sedes, 'nivelesSena' => $nivelesSena]);
    }

    public function update(Request $request, AreaSegura $areasSegura)
    {
        $request->validate([
            'codigo'                 => ['required','string','max:30','unique:areas_seguras,codigo,'.$areasSegura->id],
            'nombre_dependencia'     => ['required','string','max:200'],
            'sede_id'                => ['nullable','exists:sedes,id'],
            'nivel_sena'             => ['required','in:Nivel 1,Nivel 2,Nivel 3'],
            'nivel_criticidad'       => ['required','in:Bajo,Medio,Alto'],
            'responsable_cargo'      => ['required','string','max:150'],
            'responsable_nombre'     => ['nullable','string','max:150'],
            'responsable_contacto'   => ['nullable','string','max:100'],
            'perimetro_seguridad'    => ['required','string','max:200'],
            'controles_acceso'       => ['required','array','min:1'],
            'controles_monitoreo'    => ['nullable','array'],
            'horario_acceso'         => ['required','in:Jornada laboral,24/7,Restringido'],
            'zonificacion'           => ['nullable','string','max:40'],
            'clasificacion_informacion' => ['nullable','in:Pública,Pública Clasificada,Pública Reservada'],
        ]);

        // Registro histórico de cambios significativos
        $cambios = [];
        if ($areasSegura->nivel_sena !== $request->nivel_sena) {
            $cambios[] = "Nivel cambiado de {$areasSegura->nivel_sena} a {$request->nivel_sena}";
        }
        if ($areasSegura->responsable_cargo !== $request->responsable_cargo) {
            $cambios[] = "Responsable actualizado a: {$request->responsable_cargo}";
        }
        if ($request->nota_cambio) {
            $cambios[] = $request->nota_cambio;
        }

        if (!empty($cambios)) {
            $hist   = $areasSegura->historico_cambios ?? [];
            $hist[] = [
                'fecha'   => now()->format('d/m/Y H:i'),
                'cambio'  => implode(' | ', $cambios),
                'usuario' => Auth::user()->name ?? 'Sistema',
            ];
            $request->merge(['historico_cambios' => $hist]);
        }

        $areasSegura->update(array_merge($request->except(['_token','_method','nota_cambio']), [
            'updated_by' => Auth::id(),
        ]));

        return redirect()->route('areas-seguras.show', $areasSegura)
            ->with('success', 'Área segura actualizada.');
    }

    public function destroy(AreaSegura $areasSegura)
    {
        $areasSegura->delete();
        return redirect()->route('areas-seguras.index')
            ->with('success', "Área {$areasSegura->codigo} eliminada.");
    }

    // ── Exportar consolidado PDF (GIL-F-101) ─────────────────────────────────

    public function exportarConsolidado()
    {
        $areas = AreaSegura::with(['ultimaVerificacion', 'sede'])
            ->orderByRaw("CASE nivel_sena WHEN 'Nivel 3' THEN 1 WHEN 'Nivel 2' THEN 2 WHEN 'Nivel 1' THEN 3 ELSE 4 END")
            ->orderBy('codigo')
            ->get();

        $stats = [
            'total'         => $areas->count(),
            'nivel1'        => $areas->where('nivel_sena', 'Nivel 1')->count(),
            'nivel2'        => $areas->where('nivel_sena', 'Nivel 2')->count(),
            'nivel3'        => $areas->where('nivel_sena', 'Nivel 3')->count(),
            'con_checklist' => $areas->filter(fn($a) => $a->ultimaVerificacion)->count(),
            'conformes'     => $areas->filter(fn($a) => $a->ultimaVerificacion?->resultado === 'Conforme')->count(),
        ];

        $fecha = now()->format('d/m/Y');
        $pdf   = Pdf::loadView('areas_seguras.consolidado_pdf', compact('areas', 'stats', 'fecha'));
        $pdf->setPaper('letter', 'landscape');

        return $pdf->download('GIL-F-101_Inventario_Areas_Seguras_' . now()->format('Y-m-d') . '.pdf');
    }

    // ── Exportar verificación individual PDF (GIL-F-102) ─────────────────────

    public function exportarVerificacionPdf(AreaSegura $areasSegura)
    {
        $verificacion = $areasSegura->ultimaVerificacion;

        if (!$verificacion) {
            return redirect()->route('areas-seguras.show', $areasSegura)
                ->with('error', 'Esta área no tiene verificaciones registradas. Realice una verificación GIL-F-102 primero.');
        }

        $area = $areasSegura;

        // ── Controles de acceso: merge modelo + datos almacenados ──
        $itemsStored = collect($verificacion->items ?? [])->values();
        $controlesAcceso = collect(AreaSegura::CONTROLES_ACCESO_GIL)
            ->map(function ($base, $i) use ($itemsStored) {
                $stored = $itemsStored->get($i, []);
                return [
                    'codigo'        => $base['codigo'],
                    'item'          => $base['item'],
                    'descripcion'   => $base['descripcion'] ?? '',
                    'estado'        => $stored['estado'] ?? '',
                    'observaciones' => $stored['observaciones'] ?? '',
                ];
            })->values()->toArray();

        // ── Controles medioambientales: muestra todos los bloques,
        //    rellena estado/obs del bloque que fue evaluado ──
        $tipoMedio   = $verificacion->tipo_medioambiental;
        $medioStored = collect($verificacion->items_medioambientales ?? [])->values();

        $controlesMedioambientales = [];
        foreach (AreaSegura::CONTROLES_MEDIOAMBIENTALES as $bloque => $items) {
            $esBloqueEvaluado = ($tipoMedio === $bloque);
            $bloqueItems = [];
            foreach ($items as $j => $base) {
                $stored = $esBloqueEvaluado ? ($medioStored->get($j, []) ?? []) : [];
                $bloqueItems[] = [
                    'codigo'        => $base['codigo'],
                    'categoria'     => $base['categoria'] ?? '',
                    'nombre'        => $base['nombre'] ?? '',
                    'descripcion'   => $base['descripcion'] ?? '',
                    'estado'        => $stored['estado'] ?? '',
                    'observaciones' => $stored['observaciones'] ?? '',
                ];
            }
            $controlesMedioambientales[$bloque] = $bloqueItems;
        }

        $fecha = now()->format('d/m/Y H:i');
        $pdf   = Pdf::loadView('areas_seguras.verificacion_pdf', compact(
            'area', 'verificacion', 'controlesAcceso', 'controlesMedioambientales', 'fecha'
        ));
        $pdf->setPaper('A4', 'landscape');

        $nombre = "GIL-F-102_{$areasSegura->codigo}_{$verificacion->fecha_verificacion->format('Y-m-d')}.pdf";
        return $pdf->download($nombre);
    }

    // ── Exportar consolidado Excel (GIL-F-101) ───────────────────────────────

    public function exportarConsolidadoExcel()
    {
        return Excel::download(
            new AreaSeguraExport(),
            'GIL-F-101_Inventario_Areas_Seguras_' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    // ── Verificación GIL-F-102 ────────────────────────────────────────────────

    public function crearVerificacion(AreaSegura $areasSegura)
    {
        $controlesAcceso         = AreaSegura::CONTROLES_ACCESO_GIL;
        $controlesMedioambientales = AreaSegura::CONTROLES_MEDIOAMBIENTALES;
        return view('areas_seguras.verificacion', [
            'area'                     => $areasSegura,
            'controlesAcceso'          => $controlesAcceso,
            'controlesMedioambientales'=> $controlesMedioambientales,
        ]);
    }

    public function guardarVerificacion(Request $request, AreaSegura $areasSegura)
    {
        $request->validate([
            'fecha_verificacion'      => ['required','date'],
            'corte'                   => ['required','string','max:50'],
            'inspectores'             => ['nullable','string','max:500'],
            'items'                   => ['required','array'],
            'tipo_medioambiental'     => ['nullable','string','max:50'],
            'observaciones_generales' => ['nullable','string'],
        ]);

        // ── Bloque 2: Controles de acceso (15 controles, 4 estados) ──────────
        $controlesBase  = AreaSegura::CONTROLES_ACCESO_GIL;
        $itemsResult    = [];
        $cumpleCount    = 0;
        $parcialCount   = 0;
        $noAplicaCount  = 0;

        foreach ($controlesBase as $i => $base) {
            $estado = strtoupper(trim($request->input("items.{$i}.estado", 'NO APLICA')));
            if (!in_array($estado, ['CUMPLE', 'CUMPLE PARCIALMENTE', 'NO CUMPLE', 'NO APLICA'])) {
                $estado = 'NO APLICA';
            }
            if ($estado === 'CUMPLE') $cumpleCount++;
            elseif ($estado === 'CUMPLE PARCIALMENTE') $parcialCount++;
            elseif ($estado === 'NO APLICA') $noAplicaCount++;

            $itemsResult[] = array_merge($base, [
                'estado'        => $estado,
                'observaciones' => $request->input("items.{$i}.observaciones", ''),
            ]);
        }

        // ── Bloque 3: Controles medioambientales ──────────────────────────────
        $tipoMedio          = $request->tipo_medioambiental;
        $itemsMedioResult   = [];
        $medioAmbientales   = AreaSegura::CONTROLES_MEDIOAMBIENTALES;

        if ($tipoMedio && isset($medioAmbientales[$tipoMedio])) {
            foreach ($medioAmbientales[$tipoMedio] as $j => $base) {
                $estado = strtoupper(trim($request->input("medioambiental.{$j}.estado", 'NO APLICA')));
                if (!in_array($estado, ['CUMPLE', 'CUMPLE PARCIALMENTE', 'NO CUMPLE', 'NO APLICA'])) {
                    $estado = 'NO APLICA';
                }
                $itemsMedioResult[] = array_merge($base, [
                    'estado'        => $estado,
                    'observaciones' => $request->input("medioambiental.{$j}.observaciones", ''),
                ]);
            }
        }

        // ── Resultado global ──────────────────────────────────────────────────
        $totalItems  = count($controlesBase);
        $aplicables  = $totalItems - $noAplicaCount;
        $noCumple    = $aplicables - $cumpleCount - $parcialCount;

        $resultado = match(true) {
            $aplicables === 0                   => 'Conforme',
            $noCumple === 0 && $parcialCount === 0 => 'Conforme',
            $noCumple === 0                     => 'Conforme con Observaciones',
            $cumpleCount === 0 && $parcialCount === 0 => 'No Conforme',
            default                             => 'Conforme con Observaciones',
        };

        AreaSeguraVerificacion::create([
            'area_segura_id'          => $areasSegura->id,
            'fecha_verificacion'      => $request->fecha_verificacion,
            'corte'                   => $request->corte,
            'inspectores'             => $request->inspectores,
            'items'                   => $itemsResult,
            'total_cumple'            => $cumpleCount,
            'cumple_parcial_count'    => $parcialCount,
            'no_aplica_count'         => $noAplicaCount,
            'total_items'             => $totalItems,
            'tipo_medioambiental'     => $tipoMedio,
            'items_medioambientales'  => $itemsMedioResult ?: null,
            'resultado'               => $resultado,
            'observaciones_generales' => $request->observaciones_generales,
            'verificado_por'          => Auth::id(),
        ]);

        return redirect()->route('areas-seguras.show', $areasSegura)
            ->with('success', "Verificación GIL-F-102 guardada — {$cumpleCount} cumplen, {$parcialCount} cumplen parcialmente / {$totalItems} controles.");
    }
}
