<?php

namespace App\Http\Controllers;

use App\Models\EquipoEnergia;
use App\Models\Sede;
use App\Models\Ubicacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EquipoEnergiaController extends Controller
{
    public function index(Request $request)
    {
        $query = EquipoEnergia::with(['sede'])
            ->where('activo', true);

        if ($request->tipo)   $query->where('tipo', $request->tipo);
        if ($request->sede)   $query->where('sede_id', $request->sede);
        if ($request->estado) $query->where('estado', $request->estado);
        if ($request->search) {
            $q = $request->search;
            $query->where(fn($q2) => $q2
                ->where('marca',        'ILIKE', "%{$q}%")
                ->orWhere('modelo',     'ILIKE', "%{$q}%")
                ->orWhere('placa',      'ILIKE', "%{$q}%")
                ->orWhere('numero_serie','ILIKE', "%{$q}%")
                ->orWhere('cuarto',     'ILIKE', "%{$q}%")
            );
        }

        $equipos = $query->orderBy('sede_id')->orderBy('cuarto')->orderBy('tipo')->paginate(20)->withQueryString();

        $stats = [
            'total'       => EquipoEnergia::where('activo', true)->count(),
            'por_tipo'    => EquipoEnergia::where('activo', true)
                                ->selectRaw('tipo, count(*) as total')
                                ->groupBy('tipo')->pluck('total', 'tipo'),
            'mantenimiento_vencido' => EquipoEnergia::where('activo', true)
                                ->whereNotNull('proximo_mantenimiento')
                                ->whereDate('proximo_mantenimiento', '<', now())
                                ->count(),
        ];

        $sedes = Sede::orderBy('nombre')->pluck('nombre', 'id');

        return view('equipos_energia.index', compact('equipos', 'stats', 'sedes'));
    }

    public function create()
    {
        $sedes     = Sede::orderBy('nombre')->pluck('nombre', 'id');
        $ubicaciones = Ubicacion::with('sede')->orderBy('ambiente')->get();
        return view('equipos_energia.create', compact('sedes', 'ubicaciones'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cuarto' => ['required','string','max:150'],
            'tipo'   => ['required','in:'.implode(',', EquipoEnergia::TIPOS)],
            'marca'  => ['required','string','max:100'],
            'modelo' => ['required','string','max:100'],
            'estado' => ['required','in:Bueno,Regular,Malo,En Mantenimiento,Dado de Baja'],
            'fase'   => ['nullable','in:Monofásica,Bifásica,Trifásica'],
        ]);

        EquipoEnergia::create(array_merge($request->except('_token'), [
            'marquillado' => $request->boolean('marquillado'),
            'activo'      => true,
            'created_by'  => Auth::id(),
            'updated_by'  => Auth::id(),
        ]));

        return redirect()->route('equipos-energia.index')
            ->with('success', "Equipo {$request->marca} {$request->modelo} registrado.");
    }

    public function show(EquipoEnergia $equiposEnergium)
    {
        $equiposEnergium->load(['sede','ubicacion','creador','editor']);
        return view('equipos_energia.show', ['equipo' => $equiposEnergium]);
    }

    public function edit(EquipoEnergia $equiposEnergium)
    {
        $sedes       = Sede::orderBy('nombre')->pluck('nombre', 'id');
        $ubicaciones = Ubicacion::with('sede')->orderBy('ambiente')->get();
        return view('equipos_energia.edit', ['equipo' => $equiposEnergium, 'sedes' => $sedes, 'ubicaciones' => $ubicaciones]);
    }

    public function update(Request $request, EquipoEnergia $equiposEnergium)
    {
        $request->validate([
            'cuarto' => ['required','string','max:150'],
            'tipo'   => ['required','in:'.implode(',', EquipoEnergia::TIPOS)],
            'marca'  => ['required','string','max:100'],
            'modelo' => ['required','string','max:100'],
            'estado' => ['required','in:Bueno,Regular,Malo,En Mantenimiento,Dado de Baja'],
            'fase'   => ['nullable','in:Monofásica,Bifásica,Trifásica'],
        ]);

        $equiposEnergium->update(array_merge($request->except(['_token','_method']), [
            'marquillado' => $request->boolean('marquillado'),
            'updated_by'  => Auth::id(),
        ]));

        return redirect()->route('equipos-energia.show', $equiposEnergium)
            ->with('success', 'Equipo actualizado correctamente.');
    }

    public function destroy(EquipoEnergia $equiposEnergium)
    {
        $equiposEnergium->update(['activo' => false, 'updated_by' => Auth::id()]);
        return redirect()->route('equipos-energia.index')
            ->with('success', 'Equipo dado de baja del inventario.');
    }
}
