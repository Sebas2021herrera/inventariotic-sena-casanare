<?php

namespace App\Http\Controllers;

use App\Models\Dispositivo;
use App\Models\SoftwareCatalogo;
use App\Models\SoftwareInstalado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SoftwareController extends Controller
{
    // ── AJAX: autocomplete para el modal de instalación ─────────────────────

    public function catalogo(Request $request)
    {
        $query = SoftwareCatalogo::where('activo', true);

        if ($q = $request->input('q')) {
            $query->where(function ($sub) use ($q) {
                $sub->where('nombre', 'ILIKE', "%{$q}%")
                    ->orWhere('subproducto', 'ILIKE', "%{$q}%");
            });
        }

        if ($tipos = $request->input('tipo')) {
            $query->whereIn('tipo', explode(',', $tipos));
        }

        return response()->json(
            $query->orderBy('nombre')->orderBy('subproducto')
                  ->limit(25)
                  ->get(['id', 'nombre', 'subproducto', 'tipo'])
        );
    }

    // ── Software instalado en un dispositivo ────────────────────────────────

    public function store(Request $request, Dispositivo $dispositivo)
    {
        $request->validate([
            'software_ids'   => 'required|array|min:1',
            'software_ids.*' => 'exists:software_catalogo,id',
            'fecha_instalacion' => 'required|date',
            'version_notas'  => 'nullable|string|max:500',
        ]);

        $fecha  = $request->fecha_instalacion;
        $notas  = $request->version_notas;
        $userId = Auth::id();
        $count  = 0;

        foreach ($request->software_ids as $swId) {
            $dispositivo->softwareInstalado()->updateOrCreate(
                ['software_catalogo_id' => $swId],
                ['fecha_instalacion' => $fecha, 'version_notas' => $notas, 'instalado_por' => $userId]
            );
            $count++;
        }

        $msg = $count === 1 ? '1 software registrado.' : "{$count} software registrados.";
        return back()->with('success', $msg);
    }

    public function destroy(Dispositivo $dispositivo, SoftwareInstalado $instalado)
    {
        abort_if($instalado->dispositivo_id !== $dispositivo->id, 403);
        $instalado->delete();
        return back()->with('success', 'Software eliminado del registro.');
    }

    // ── Gestión del catálogo DG (admin) ─────────────────────────────────────

    public function catalogoIndex(Request $request)
    {
        $buscar = $request->input('buscar');
        $tipo   = $request->input('tipo');

        $query = SoftwareCatalogo::query();

        if ($buscar) {
            $query->where(function ($q) use ($buscar) {
                $q->where('nombre', 'ILIKE', "%{$buscar}%")
                  ->orWhere('subproducto', 'ILIKE', "%{$buscar}%");
            });
        }

        if ($tipo) {
            $query->where('tipo', $tipo);
        }

        $software = $query->orderBy('tipo')->orderBy('nombre')->orderBy('subproducto')
                          ->paginate(30)->withQueryString();

        $stats = [
            'total'          => SoftwareCatalogo::count(),
            'licenciados'    => SoftwareCatalogo::where('tipo', 'licenciado')->count(),
            'libres'         => SoftwareCatalogo::where('tipo', 'libre')->count(),
            'no_autorizados' => SoftwareCatalogo::where('tipo', 'no_autorizado')->count(),
            'inactivos'      => SoftwareCatalogo::where('activo', false)->count(),
        ];

        return view('software.catalogo', compact('software', 'stats'));
    }

    public function catalogoStore(Request $request)
    {
        $validated = $request->validate([
            'nombre'         => 'required|string|max:255',
            'subproducto'    => 'nullable|string|max:255',
            'tipo'           => 'required|in:licenciado,libre,no_autorizado',
            'vigencia_hasta' => 'nullable|date',
        ]);

        SoftwareCatalogo::create(array_merge($validated, ['activo' => true]));

        return back()->with('success', 'Software agregado al catálogo.');
    }

    public function catalogoUpdate(Request $request, SoftwareCatalogo $sw)
    {
        $validated = $request->validate([
            'nombre'         => 'required|string|max:255',
            'subproducto'    => 'nullable|string|max:255',
            'tipo'           => 'required|in:licenciado,libre,no_autorizado',
            'vigencia_hasta' => 'nullable|date',
        ]);

        $sw->update($validated);

        return back()->with('success', 'Entrada actualizada.');
    }

    public function catalogoToggle(SoftwareCatalogo $sw)
    {
        $sw->update(['activo' => !$sw->activo]);
        $msg = $sw->activo ? 'Software activado.' : 'Software desactivado.';
        return back()->with('success', $msg);
    }

    public function catalogoDestroy(SoftwareCatalogo $sw)
    {
        if ($sw->instalaciones()->count() > 0) {
            return back()->withErrors(['error' => 'No se puede eliminar: hay equipos con este software registrado.']);
        }
        $sw->delete();
        return back()->with('success', 'Entrada eliminada del catálogo.');
    }
}
