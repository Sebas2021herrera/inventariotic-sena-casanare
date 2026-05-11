<?php

namespace App\Http\Controllers;

use App\Models\Responsable;
use Illuminate\Http\Request;

class ResponsableController extends Controller
{
    public function index()
    {
        $responsables = Responsable::orderBy('nombre')->get();
        return view('responsables.index', compact('responsables'));
    }

    public function create()
    {
        return view('responsables.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cedula' => 'required|string|max:20|unique:responsables,cedula',
            'nombre' => 'required|string|max:150',
            'correo_institucional' => 'nullable|email|max:150',
            'dependencia' => 'required|string|max:150',
            'cargo' => 'required|string|max:150',
            'tipo_funcionario' => 'required|string|max:50',
        ]);

        Responsable::create($validated);

        return redirect()
            ->route('responsables.index')
            ->with('success', 'Responsable registrado correctamente');
    }

    public function show(Responsable $responsable)
    {
        return view('responsables.show', compact('responsable'));
    }

    public function reportePDF(Responsable $responsable)
    {
        ini_set('memory_limit', '256M');

        $responsable->load([
            'dispositivos' => fn($q) => $q
                ->with('ubicacion.sede')
                ->select('id','responsable_id','placa','hostname','serial',
                         'marca','modelo','categoria','estado_fisico','en_intune','ubicacion_id')
                ->orderBy('placa'),
        ]);

        $dispositivos = $responsable->dispositivos;
        $stats = [
            'total'       => $dispositivos->count(),
            'buenos'      => $dispositivos->where('estado_fisico', 'Bueno')->count(),
            'en_intune'   => $dispositivos->where('en_intune', 'SI')->count(),
            'por_estado'  => $dispositivos->groupBy('estado_fisico')
                                ->map(fn($g) => $g->count()),
            'por_sede'    => $dispositivos->groupBy(fn($d) => $d->ubicacion?->sede?->nombre ?? 'Sin sede')
                                ->map(fn($g) => $g->count())
                                ->sortDesc(),
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
            'responsables.reporte_pdf',
            compact('responsable', 'dispositivos', 'stats')
        );
        $pdf->setPaper('letter', 'landscape');

        $nombre = \Illuminate\Support\Str::slug($responsable->nombre);
        return $pdf->download("Equipos_{$nombre}.pdf");
    }

    public function edit(Responsable $responsable)
    {
        return view('responsables.edit', compact('responsable'));
    }

    public function update(Request $request, Responsable $responsable)
    {
        $validated = $request->validate([
            'cedula' => 'required|string|max:20|unique:responsables,cedula,' . $responsable->id,
            'nombre' => 'required|string|max:150',
            'correo_institucional' => 'nullable|email|max:150',
            'dependencia' => 'required|string|max:150',
            'cargo' => 'required|string|max:150',
            'tipo_funcionario' => 'required|string|max:50',
        ]);

        $responsable->update($validated);

        return redirect()
            ->route('responsables.index')
            ->with('success', 'Responsable actualizado correctamente');
    }

    public function destroy(Responsable $responsable)
    {
        $responsable->delete();

        return redirect()
            ->route('responsables.index')
            ->with('success', 'Responsable eliminado correctamente');
    }
    public function buscar($cedula)
    {
        $responsable = Responsable::where('cedula', $cedula)->first();
        return response()->json($responsable);
    }

    public function buscarPorNombre(Request $request)
    {
        $q = trim($request->input('q', ''));
        if (strlen($q) < 2) return response()->json([]);

        $resultados = Responsable::where('nombre', 'ILIKE', "%{$q}%")
            ->orWhere('cedula', 'ILIKE', "%{$q}%")
            ->orderBy('nombre')
            ->limit(12)
            ->get(['id', 'cedula', 'nombre', 'cargo', 'dependencia', 'tipo_funcionario', 'numero_de_celular']);

        return response()->json($resultados);
    }


}
