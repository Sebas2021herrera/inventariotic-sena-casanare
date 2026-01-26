<?php

namespace App\Http\Controllers;

use App\Models\Mantenimiento;
use App\Models\Dispositivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MantenimientoController extends Controller
{
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
                $dispositivo->update(['estado_fisico' => 'EN REPARACIÓN']);
            } else {
                $dispositivo->update(['estado_fisico' => 'BUENO']);
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
                $dispositivo->update(['estado_fisico' => 'EN REPARACIÓN']);
            } else {
                $dispositivo->update(['estado_fisico' => 'BUENO']);
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
}