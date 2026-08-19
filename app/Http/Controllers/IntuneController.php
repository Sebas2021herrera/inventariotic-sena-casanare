<?php

namespace App\Http\Controllers;

use App\Models\IntuneCuenta;
use App\Models\Dispositivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IntuneController extends Controller
{
    // ── Listado con búsqueda ──────────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = IntuneCuenta::with('dispositivo');

        if ($buscar = $request->get('buscar')) {
            $b = strtolower(trim($buscar));
            $query->where(function ($q) use ($b) {
                $q->whereRaw('LOWER(placa) LIKE ?', ["%{$b}%"])
                  ->orWhereRaw('LOWER(cuenta) LIKE ?', ["%{$b}%"])
                  ->orWhereRaw('LOWER(id_sede) LIKE ?', ["%{$b}%"])
                  ->orWhereHas('dispositivo.responsable', function ($r) use ($b) {
                      $r->whereRaw('LOWER(nombre) LIKE ?', ["%{$b}%"]);
                  });
            });
        }

        if ($estado = $request->get('estado')) {
            $query->where('estado', $estado);
        }

        if ($tipo = $request->get('tipo')) {
            $query->where('tipo', $tipo);
        }

        $cuentas = $query->orderBy('tipo')->orderBy('placa')->paginate(30)->withQueryString();

        $stats = [
            'total'          => IntuneCuenta::count(),
            'activas'        => IntuneCuenta::where('estado', 'activa')->count(),
            'pendientes'     => IntuneCuenta::where('estado', 'pendiente')->count(),
            'dispositivos'   => IntuneCuenta::where('tipo', 'dispositivo')->count(),
            'usuarios'       => IntuneCuenta::where('tipo', 'usuario')->count(),
        ];

        return view('intune.index', compact('cuentas', 'stats'));
    }

    // ── Formulario de carga ───────────────────────────────────────────────────
    public function cargar()
    {
        return view('intune.cargar');
    }

    // ── Procesar carga de cuentas ─────────────────────────────────────────────
    public function procesar(Request $request)
    {
        $request->validate([
            'cuentas'        => 'required|string',
            'fecha_reporte'  => 'nullable|date',
            'estado'         => 'required|in:activa,pendiente',
        ]);

        $lineas = preg_split('/[\r\n,;]+/', $request->cuentas);
        $lineas = array_filter(array_map('trim', $lineas));

        $insertadas  = 0;
        $actualizadas = 0;
        $errores     = [];

        foreach ($lineas as $linea) {
            $cuenta = strtolower(trim($linea));

            // Formato esperado: {id_sede}_{placa}@senaedu.edu.co  (id_sede puede ser alfanumérico: 170, 170A, etc.)
            if (!preg_match('/^([a-z0-9]+)_([^@]+)@senaedu\.edu\.co$/i', $cuenta, $m)) {
                $errores[] = $cuenta;
                continue;
            }

            $idSede = $m[1];
            $placa  = strtoupper($m[2]);

            // Estado según si el dispositivo ya está enrolado
            $disp   = Dispositivo::where('placa', $placa)->first();
            $estado = ($disp && $disp->en_intune === 'SI') ? 'activa' : 'pendiente';

            $existente = IntuneCuenta::where('cuenta', $cuenta)->first();

            if ($existente) {
                $existente->update([
                    'id_sede'       => $idSede,
                    'placa'         => $placa,
                    'estado'        => $estado,
                    'fecha_reporte' => $request->fecha_reporte ?: null,
                ]);
                $actualizadas++;
            } else {
                IntuneCuenta::create([
                    'cuenta'        => $cuenta,
                    'id_sede'       => $idSede,
                    'placa'         => $placa,
                    'estado'        => $estado,
                    'fecha_reporte' => $request->fecha_reporte ?: null,
                ]);
                $insertadas++;
            }

            // en_intune en dispositivos no se toca: se actualiza solo durante el mantenimiento
        }

        $msg = "Carga completada: {$insertadas} nuevas, {$actualizadas} actualizadas.";
        if ($errores) {
            $msg .= ' ' . count($errores) . ' línea(s) ignorada(s) por formato inválido.';
        }

        return redirect()->route('intune.index')->with('success', $msg);
    }

    // ── Registrar/actualizar correo de usuario para equipo administrativo ────
    public function registrarAdministrativo(Request $request)
    {
        $request->validate([
            'placa'          => 'required|string|exists:dispositivos,placa',
            'correo_usuario' => 'required|email|max:150',
            'notas'          => 'nullable|string|max:500',
        ], [
            'correo_usuario.email'    => 'El formato del correo no es válido.',
            'correo_usuario.required' => 'El correo del usuario es obligatorio.',
            'placa.exists'            => 'La placa no existe en el inventario.',
        ]);

        $placa  = strtoupper(trim($request->placa));
        $correo = strtolower(trim($request->correo_usuario));

        IntuneCuenta::updateOrCreate(
            ['placa' => $placa, 'tipo' => 'usuario'],
            [
                'cuenta'  => $correo,
                'id_sede' => 'ADM',
                'estado'  => 'activa',
                'tipo'    => 'usuario',
                'notas'   => $request->notas ?: null,
            ]
        );

        return back()->with('success', 'Cuenta Intune del usuario registrada correctamente.');
    }

    // ── AJAX: buscar cuenta por placa ─────────────────────────────────────────
    public function buscarPorPlaca($placa)
    {
        $cuenta = IntuneCuenta::where('placa', strtoupper($placa))->first();

        if (!$cuenta) {
            return response()->json(['existe' => false]);
        }

        return response()->json([
            'existe'        => true,
            'cuenta'        => $cuenta->cuenta,
            'id_sede'       => $cuenta->id_sede,
            'estado'        => $cuenta->estado,
            'fecha_reporte' => $cuenta->fecha_reporte?->format('d/m/Y'),
        ]);
    }

    // ── Editar cuenta (fecha_reporte y notas) ────────────────────────────────
    public function update(Request $request, IntuneCuenta $intune)
    {
        if ($intune->tipo === 'usuario') {
            $request->validate([
                'cuenta' => 'required|email|max:150',
                'notas'  => 'nullable|string|max:500',
            ], ['cuenta.email' => 'El formato del correo no es válido.']);

            $intune->update([
                'cuenta' => strtolower(trim($request->cuenta)),
                'notas'  => $request->notas ?: null,
            ]);
        } else {
            $request->validate([
                'fecha_reporte' => 'nullable|date',
                'notas'         => 'nullable|string|max:500',
            ]);

            $intune->update([
                'fecha_reporte' => $request->fecha_reporte ?: null,
                'notas'         => $request->notas ?: null,
            ]);
        }

        return back()->with('success', 'Cuenta Intune actualizada.');
    }

    // ── Eliminar cuenta ───────────────────────────────────────────────────────
    public function destroy(IntuneCuenta $intune)
    {
        $intune->delete();

        return back()->with('success', 'Cuenta Intune eliminada.');
    }
}
