<?php

namespace App\Http\Controllers;

use App\Models\Mantenimiento;
use App\Models\Dispositivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class MantenimientoController extends Controller
{
    public function index(Request $request)
    {
        $query = Mantenimiento::with(['dispositivo'])
            ->orderBy('fecha', 'desc');

        if ($buscar = $request->get('buscar')) {
            $b = strtolower(trim($buscar));
            $query->whereHas('dispositivo', fn($q) =>
                $q->whereRaw('LOWER(placa) LIKE ?', ["%{$b}%"])
                  ->orWhereRaw('LOWER(marca) LIKE ?', ["%{$b}%"])
            )->orWhereRaw('LOWER(tecnico_encargado) LIKE ?', ["%{$b}%"]);
        }

        if ($tipo = $request->get('tipo')) {
            $query->where('tipo', $tipo);
        }

        if ($request->get('pendientes')) {
            $query->where('finalizado', false);
        }

        $mantenimientos = $query->paginate(20)->withQueryString();

        $stats = [
            'total'      => Mantenimiento::count(),
            'pendientes' => Mantenimiento::where('finalizado', false)->count(),
            'preventivos'=> Mantenimiento::where('tipo', 'Preventivo')->count(),
            'correctivos'=> Mantenimiento::where('tipo', 'Correctivo')->count(),
        ];

        return view('mantenimientos.index', compact('mantenimientos', 'stats'));
    }

    public function show(Mantenimiento $mantenimiento)
    {
        return redirect()->route('dispositivos.show', $mantenimiento->dispositivo_id);
    }

    public function create(Request $request)
    {
        $dispositivo = Dispositivo::findOrFail($request->dispositivo_id);
        return view('mantenimientos.create', compact('dispositivo'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'dispositivo_id' => 'required|exists:dispositivos,id',
            'fecha' => 'required|date',
            'tipo' => 'required|in:Preventivo,Correctivo',
            'tecnico_encargado' => 'required|string|max:255',
            'tareas_realizadas' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            // Guardamos el mantenimiento (asegúrate de que 'finalizado' sea fillable en el Modelo)
            $mantenimiento = Mantenimiento::create($request->all());
            $dispositivo = $mantenimiento->dispositivo;

            // Lógica de Estados:
            // Si es Correctivo y NO está finalizado -> EN REPARACIÓN
            // En cualquier otro caso (Preventivo o Correctivo Finalizado) -> BUENO
            $esFinalizado = $request->boolean('finalizado');

            if ($request->tipo === 'Correctivo' && !$esFinalizado) {
                $dispositivo->update(['estado_fisico' => 'En Reparación']);
            } else {
                $dispositivo->update(['estado_fisico' => 'Bueno']);
            }

            DB::commit();

            return redirect()->route('dispositivos.show', $request->dispositivo_id)
                             ->with('success', 'Mantenimiento registrado y estado del equipo actualizado.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Error: ' . $e->getMessage()])->withInput();
        }
    }

    public function edit(Mantenimiento $mantenimiento)
    {
        return view('mantenimientos.edit', compact('mantenimiento'));
    }

    public function update(Request $request, Mantenimiento $mantenimiento)
    {
        $request->validate([
            'fecha' => 'required|date',
            'tipo' => 'required|in:Preventivo,Correctivo',
            'tecnico_encargado' => 'required|string',
            'tareas_realizadas' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            $mantenimiento->update($request->all());
            $dispositivo = $mantenimiento->dispositivo;

            // Sincronizamos el estado del dispositivo al editar el reporte
            $esFinalizado = $request->boolean('finalizado');

            if ($request->tipo === 'Correctivo' && !$esFinalizado) {
                $dispositivo->update(['estado_fisico' => 'En Reparación']);
            } else {
                $dispositivo->update(['estado_fisico' => 'Bueno']);
            }

            DB::commit();

            return redirect()->route('dispositivos.show', $mantenimiento->dispositivo_id)
                             ->with('success', 'Registro y estado del equipo actualizados.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function destroy(Mantenimiento $mantenimiento)
    {
        $dispositivoId = $mantenimiento->dispositivo_id;
        $mantenimiento->delete();
        return redirect()->route('dispositivos.show', $dispositivoId)
                         ->with('success', 'Registro eliminado.');
    }

    public function exportarPDF(Mantenimiento $mantenimiento)
    {
        $mantenimiento->load(['dispositivo.responsable', 'dispositivo.ubicacion', 'dispositivo.especificaciones']);
        $pdf = Pdf::loadView('mantenimientos.pdf', compact('mantenimiento'));
        $pdf->setPaper('letter', 'portrait');
        return $pdf->download('Mantenimiento_Placa_' . $mantenimiento->dispositivo->placa . '_' . $mantenimiento->fecha . '.pdf');
    }
}