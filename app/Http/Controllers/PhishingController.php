<?php

namespace App\Http\Controllers;

use App\Data\PhishingScenarios;
use App\Models\PhishingConfig;
use App\Models\PhishingParticipante;
use App\Models\PhishingResultado;
use Illuminate\Http\Request;

class PhishingController extends Controller
{
    public function index()
    {
        return view('sgspi.phishing.registro');
    }

    public function registrar(Request $request)
    {
        $request->validate([
            'nombre'    => 'required|string|max:100',
            'documento' => 'required|string|max:20',
            'area'      => 'required|string|max:120',
        ], [
            'nombre.required'    => 'El nombre es obligatorio.',
            'documento.required' => 'El número de documento es obligatorio.',
            'area.required'      => 'El área o dependencia es obligatoria.',
        ]);

        $participante = PhishingParticipante::create($request->only('nombre', 'documento', 'area'));

        session()->put('phishing_game', [
            'participante_id' => $participante->id,
        ]);

        return redirect()->route('sgspi.phishing.jugar');
    }

    public function jugar()
    {
        $game = session('phishing_game');
        if (!$game) {
            return redirect()->route('sgspi.phishing.index')
                ->with('error', 'Por favor regístrate antes de jugar.');
        }

        $participante = PhishingParticipante::find($game['participante_id']);

        $config = PhishingConfig::get();
        $n      = $config->escenarios;

        // Selección proporcional por nivel: distribuir N entre 4 niveles
        $porNivel  = collect(PhishingScenarios::all())->groupBy('nivel');
        $base      = (int) floor($n / 4);
        $extra     = $n % 4;
        $seleccionados = collect();
        foreach ([1, 2, 3, 4] as $i => $nivel) {
            $cantidad = $base + ($i < $extra ? 1 : 0);
            $seleccionados = $seleccionados->concat(
                $porNivel->get($nivel, collect())->shuffle()->take($cantidad)
            );
        }
        $escenarios = $seleccionados->sortBy('nivel')->values()->all();

        return view('sgspi.phishing.jugar', compact('participante', 'escenarios', 'config'));
    }

    public function finalizar(Request $request)
    {
        $game = session('phishing_game');
        if (!$game) {
            return response()->json(['error' => 'Sesión expirada'], 419);
        }

        $resultado = PhishingResultado::create([
            'participante_id' => $game['participante_id'],
            'puntaje'         => (int) $request->puntaje,
            'correctas'       => (int) $request->correctas,
            'total'           => (int) $request->total,
            'bonus'           => (int) $request->bonus,
            'nivel_alcanzado' => (int) $request->nivel_alcanzado,
        ]);

        session()->forget('phishing_game');

        return response()->json(['resultado_id' => $resultado->id]);
    }

    public function resultado(PhishingResultado $resultado)
    {
        $resultado->load('participante');

        $leaderboard = PhishingResultado::with('participante')
            ->orderByDesc('puntaje')
            ->take(10)
            ->get();

        $porcentaje = $resultado->total > 0
            ? round(($resultado->correctas / $resultado->total) * 100)
            : 0;

        $posicion = PhishingResultado::where('puntaje', '>', $resultado->puntaje)->count() + 1;

        return view('sgspi.phishing.resultado', compact('resultado', 'porcentaje', 'leaderboard', 'posicion'));
    }

    // Admin: resultados
    public function adminResultados()
    {
        $resultados = PhishingResultado::with('participante')
            ->latest()
            ->paginate(30);

        $stats = [
            'total'      => PhishingResultado::count(),
            'prom_score' => round(PhishingResultado::avg('puntaje') ?? 0),
            'prom_pct'   => round(
                PhishingResultado::whereRaw('total > 0')->avg(\DB::raw('correctas::float / total * 100')) ?? 0
            ),
        ];

        $config      = PhishingConfig::get();
        $totalBanco  = count(PhishingScenarios::all());

        return view('sgspi.phishing.admin', compact('resultados', 'stats', 'config', 'totalBanco'));
    }

    // Admin: ver y editar configuración
    public function adminConfig()
    {
        $config     = PhishingConfig::get();
        $totalBanco = count(PhishingScenarios::all());

        return view('sgspi.phishing.configuracion', compact('config', 'totalBanco'));
    }

    public function adminConfigUpdate(Request $request)
    {
        $totalBanco = count(PhishingScenarios::all());

        $request->validate([
            'escenarios' => ['required', 'integer', 'min:4', "max:{$totalBanco}"],
        ], [
            'escenarios.min' => 'Deben jugarse al menos 4 escenarios.',
            'escenarios.max' => "El banco tiene {$totalBanco} escenarios disponibles.",
        ]);

        PhishingConfig::get()->update($request->only('escenarios'));

        return redirect()->route('sgspi.phishing.admin.config')
            ->with('success', 'Configuración actualizada correctamente.');
    }
}
