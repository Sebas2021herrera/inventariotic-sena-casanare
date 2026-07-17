<?php

namespace App\Http\Controllers;

use App\Models\SgspiParticipante;
use App\Models\SgspiPregunta;
use App\Models\SgspiResultado;
use App\Models\SgspiConfig;
use Illuminate\Http\Request;

class SgspiController extends Controller
{
    // ── Página principal pública ──────────────────────────────────────────────
    public function index()
    {
        return view('sgspi.index');
    }

    // ── Instrucciones + QR imprimible ─────────────────────────────────────────
    public function instrucciones()
    {
        $url = url('/sgspi');
        return view('sgspi.instrucciones', compact('url'));
    }

    // ── Registro para el juego ────────────────────────────────────────────────
    public function buscaminas()
    {
        return view('sgspi.buscaminas.registro');
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

        $participante = SgspiParticipante::create($request->only('nombre', 'documento', 'area'));

        $config       = SgspiConfig::get();
        $nPreguntas   = $config->preguntas;
        $nCeldas      = $config->total_celdas;
        $nSafe        = max(0, $nCeldas - $nPreguntas);

        $preguntas = SgspiPregunta::inRandomOrder()->take($nPreguntas)->get();

        $tipos = array_merge(array_fill(0, $nPreguntas, 'pregunta'), array_fill(0, $nSafe, 'safe'));
        shuffle($tipos);

        $cells  = [];
        $qIndex = 0;

        foreach ($tipos as $tipo) {
            if ($tipo === 'pregunta') {
                $cells[] = ['type' => 'pregunta', 'pregunta_id' => $preguntas[$qIndex++]->id, 'revealed' => false, 'correct' => null];
            } else {
                $cells[] = ['type' => 'safe', 'pregunta_id' => null, 'revealed' => false, 'correct' => null];
            }
        }

        session()->put('sgspi_game', [
            'participante_id'  => $participante->id,
            'cells'            => $cells,
            'score'            => 0,
            'correctas'        => 0,
            'respondidas'      => 0,
            'total_preguntas'  => $nPreguntas,
            'total_celdas'     => $nCeldas,
            'columnas'         => $config->columnas,
        ]);

        return redirect()->route('sgspi.jugar');
    }

    // ── Pantalla del juego ────────────────────────────────────────────────────
    public function jugar()
    {
        $game = session('sgspi_game');

        if (!$game) {
            return redirect()->route('sgspi.buscaminas')
                ->with('error', 'Por favor regístrate antes de jugar.');
        }

        $participante = SgspiParticipante::find($game['participante_id']);

        return view('sgspi.buscaminas.jugar', compact('game', 'participante'));
    }

    // ── AJAX: revelar celda ────────────────────────────────────────────────────
    public function celda(Request $request, $index)
    {
        $game = session('sgspi_game');
        if (!$game) return response()->json(['error' => 'Sesión expirada'], 419);

        $index = (int) $index;
        $cell  = $game['cells'][$index] ?? null;

        if (!$cell || $cell['revealed']) {
            return response()->json(['error' => 'Celda no disponible'], 400);
        }

        if ($cell['type'] === 'safe') {
            $game['cells'][$index]['revealed'] = true;
            $game['cells'][$index]['correct']  = null;
            session()->put('sgspi_game', $game);

            $reveladas = count(array_filter($game['cells'], fn($c) => $c['revealed']));

            return response()->json([
                'type'           => 'safe',
                'score'          => $game['score'],
                'total_reveladas' => $reveladas,
                'total_celdas'   => count($game['cells']),
            ]);
        }

        // Es una pregunta — devolver sin la respuesta
        $pregunta = SgspiPregunta::find($cell['pregunta_id']);

        return response()->json([
            'type'       => 'pregunta',
            'cell_index' => $index,
            'tema'       => $pregunta->tema,
            'pregunta'   => $pregunta->pregunta,
            'opciones'   => $pregunta->opciones,
        ]);
    }

    // ── AJAX: verificar respuesta ─────────────────────────────────────────────
    public function responder(Request $request)
    {
        $game = session('sgspi_game');
        if (!$game) return response()->json(['error' => 'Sesión expirada'], 419);

        $index  = (int) $request->cell_index;
        $answer = strtoupper(trim($request->answer));
        $cell   = $game['cells'][$index] ?? null;

        if (!$cell || $cell['revealed']) {
            return response()->json(['error' => 'Celda no disponible'], 400);
        }

        $pregunta = SgspiPregunta::find($cell['pregunta_id']);
        $correct  = ($answer === $pregunta->respuesta);

        if ($correct) {
            $game['score'] += 10;
            $game['correctas']++;
        }
        $game['respondidas']++;
        $game['cells'][$index]['revealed'] = true;
        $game['cells'][$index]['correct']  = $correct;

        session()->put('sgspi_game', $game);

        $reveladas = count(array_filter($game['cells'], fn($c) => $c['revealed']));

        return response()->json([
            'correct'          => $correct,
            'respuesta_correcta' => $pregunta->respuesta,
            'explicacion'      => $pregunta->explicacion,
            'score'            => $game['score'],
            'correctas'        => $game['correctas'],
            'respondidas'      => $game['respondidas'],
            'total_reveladas'  => $reveladas,
            'total_celdas'     => count($game['cells']),
        ]);
    }

    // ── AJAX: finalizar partida ────────────────────────────────────────────────
    public function finalizar(Request $request)
    {
        $game = session('sgspi_game');
        if (!$game) return response()->json(['error' => 'Sesión expirada'], 419);

        $resultado = SgspiResultado::create([
            'participante_id' => $game['participante_id'],
            'puntaje'         => $game['score'],
            'correctas'       => $game['correctas'],
            'total'           => $game['respondidas'],
        ]);

        session()->forget('sgspi_game');

        return response()->json(['resultado_id' => $resultado->id]);
    }

    // ── Pantalla de resultado ─────────────────────────────────────────────────
    public function resultado(SgspiResultado $resultado)
    {
        $resultado->load('participante');
        $porcentaje = $resultado->total > 0
            ? round(($resultado->correctas / $resultado->total) * 100)
            : 0;

        return view('sgspi.buscaminas.resultado', compact('resultado', 'porcentaje'));
    }

    // ── Admin: listado de resultados ──────────────────────────────────────────
    public function adminResultados()
    {
        $resultados = SgspiResultado::with('participante')
            ->latest()
            ->paginate(30);

        return view('sgspi.admin.resultados', compact('resultados'));
    }

    // ── Admin: configuración del juego ────────────────────────────────────────
    public function adminConfig()
    {
        $config      = SgspiConfig::get();
        $totalBanco  = SgspiPregunta::count();

        return view('sgspi.admin.configuracion', compact('config', 'totalBanco'));
    }

    public function adminConfigUpdate(Request $request)
    {
        $totalBanco = SgspiPregunta::count();

        $request->validate([
            'total_celdas' => ['required', 'integer', 'min:4', 'max:100'],
            'preguntas'    => ['required', 'integer', 'min:1', "max:{$totalBanco}"],
            'columnas'     => ['required', 'integer', 'min:2', 'max:10'],
        ], [
            'total_celdas.min'  => 'El tablero debe tener al menos 4 celdas.',
            'total_celdas.max'  => 'El tablero no puede tener más de 100 celdas.',
            'preguntas.min'     => 'Debe haber al menos 1 pregunta.',
            'preguntas.max'     => "No puedes usar más de {$totalBanco} preguntas (total del banco).",
            'columnas.min'      => 'Mínimo 2 columnas.',
            'columnas.max'      => 'Máximo 10 columnas.',
        ]);

        if ($request->preguntas > $request->total_celdas) {
            return back()->withErrors(['preguntas' => 'Las preguntas no pueden superar el total de celdas.'])->withInput();
        }

        SgspiConfig::get()->update($request->only('total_celdas', 'preguntas', 'columnas'));

        return redirect()->route('sgspi.admin.config')
            ->with('success', 'Configuración actualizada correctamente.');
    }
}
