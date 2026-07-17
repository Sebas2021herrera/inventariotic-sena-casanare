@extends('layouts.public')
@section('title', 'Buscaminas SGSPI — Jugando')

@push('head')
<style>
    .cell {
        aspect-ratio: 1;
        cursor: pointer;
        transition: transform 0.15s, box-shadow 0.15s;
        user-select: none;
    }
    .cell:hover:not(.revealed) { transform: scale(1.06); }
    .cell.revealed { cursor: default; }
    .cell-flip { animation: flipIn 0.3s ease-out; }
    @keyframes flipIn {
        from { transform: rotateY(90deg) scale(0.8); opacity: 0; }
        to   { transform: rotateY(0deg) scale(1);   opacity: 1; }
    }
    #modal-overlay {
        backdrop-filter: blur(4px);
        background: rgba(0,0,0,0.5);
    }
</style>
@endpush

@section('content')
<div class="max-w-2xl mx-auto space-y-5">

    {{-- Barra de estado --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 px-5 py-3 flex items-center justify-between gap-4">
        <div class="flex items-center gap-2 min-w-0">
            <div class="w-8 h-8 sena-bg rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="fas fa-user text-white text-xs"></i>
            </div>
            <div class="min-w-0">
                <p class="font-black text-gray-800 text-sm truncate">{{ $participante->nombre }}</p>
                <p class="text-[10px] text-gray-400 font-bold truncate">{{ $participante->area }}</p>
            </div>
        </div>
        <div class="flex items-center gap-4 flex-shrink-0">
            <div class="text-center">
                <p class="text-[10px] font-black text-gray-400 uppercase">Puntos</p>
                <p id="score-display" class="text-2xl font-black sena-text">{{ $game['score'] }}</p>
            </div>
            <div class="text-center">
                <p class="text-[10px] font-black text-gray-400 uppercase">Correctas</p>
                <p id="correctas-display" class="text-2xl font-black text-blue-500">
                    {{ $game['correctas'] }}<span class="text-gray-300 text-sm">/{{ $game['total_preguntas'] }}</span>
                </p>
            </div>
            <div class="text-center">
                <p class="text-[10px] font-black text-gray-400 uppercase">Celdas</p>
                <p id="reveladas-display" class="text-2xl font-black text-gray-600">
                    {{ count(array_filter($game['cells'], fn($c) => $c['revealed'])) }}<span class="text-gray-300 text-sm">/{{ $game['total_celdas'] }}</span>
                </p>
            </div>
        </div>
    </div>

    {{-- Tablero dinámico --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-5">
        <div class="grid gap-2" style="grid-template-columns: repeat({{ $game['columnas'] }}, minmax(0, 1fr))">
            @foreach($game['cells'] as $i => $cell)
                @php
                    $revealed = $cell['revealed'];
                    $correct  = $cell['correct'];
                    $type     = $cell['type'];

                    if (!$revealed) {
                        $cls  = 'bg-gray-700 hover:bg-gray-600 border-gray-600';
                        $icon = '<i class="fas fa-lock text-gray-400 text-xl"></i>';
                    } elseif ($type === 'safe') {
                        $cls  = 'bg-blue-50 border-blue-200 revealed';
                        $icon = '<i class="fas fa-shield-alt text-blue-400 text-xl"></i>';
                    } elseif ($correct) {
                        $cls  = 'bg-green-50 border-green-200 revealed';
                        $icon = '<i class="fas fa-check-circle text-green-500 text-xl"></i>';
                    } else {
                        $cls  = 'bg-red-50 border-red-200 revealed';
                        $icon = '<i class="fas fa-times-circle text-red-400 text-xl"></i>';
                    }
                @endphp
                <div id="cell-{{ $i }}"
                     class="cell rounded-xl border-2 flex items-center justify-center {{ $cls }}"
                     @if(!$revealed) onclick="clickCelda({{ $i }})" @endif>
                    {!! $icon !!}
                </div>
            @endforeach
        </div>

        {{-- Leyenda --}}
        <div class="mt-4 flex flex-wrap gap-3 justify-center text-[10px] font-bold text-gray-400 uppercase">
            <span><span class="inline-block w-3 h-3 bg-gray-700 rounded mr-1"></span>Sin revelar</span>
            <span><span class="inline-block w-3 h-3 bg-green-100 border border-green-200 rounded mr-1"></span>Correcta</span>
            <span><span class="inline-block w-3 h-3 bg-red-100 border border-red-200 rounded mr-1"></span>Incorrecta</span>
            <span><span class="inline-block w-3 h-3 bg-blue-100 border border-blue-200 rounded mr-1"></span>Zona segura</span>
        </div>
    </div>

    {{-- Botón finalizar (oculto hasta completar) --}}
    <div id="btn-finalizar-wrap" class="hidden text-center">
        <button onclick="finalizarJuego()"
            class="sena-bg text-white font-black px-10 py-4 rounded-2xl shadow-xl hover:scale-105 transition-transform text-sm uppercase tracking-widest">
            <i class="fas fa-flag-checkered mr-2"></i> Ver mi Resultado
        </button>
    </div>

</div>

{{-- ── MODAL DE PREGUNTA ── --}}
<div id="modal-overlay" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
    <div id="modal-card" class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden">

        {{-- Header del modal --}}
        <div class="sena-bg text-white px-6 py-4 flex items-center justify-between">
            <div>
                <p id="modal-tema" class="text-[10px] font-black uppercase tracking-widest opacity-75"></p>
                <p class="font-black text-base uppercase tracking-tight">Pregunta de Seguridad</p>
            </div>
            <div class="bg-white/20 rounded-xl px-3 py-1.5">
                <i class="fas fa-question text-white"></i>
            </div>
        </div>

        {{-- Pregunta --}}
        <div class="px-6 pt-5 pb-3">
            <p id="modal-pregunta" class="font-bold text-gray-800 text-sm leading-relaxed"></p>
        </div>

        {{-- Opciones --}}
        <div id="modal-opciones" class="px-6 pb-4 space-y-2"></div>

        {{-- Feedback (oculto hasta responder) --}}
        <div id="modal-feedback" class="hidden px-6 pb-4">
            <div id="feedback-box" class="rounded-2xl p-4 text-sm font-bold">
                <p id="feedback-titulo" class="font-black text-base mb-1 uppercase tracking-tight"></p>
                <p id="feedback-respuesta" class="text-[11px] mb-2"></p>
                <p id="feedback-explicacion" class="text-[11px] font-normal opacity-80 italic"></p>
            </div>
            <button onclick="cerrarModal()"
                class="mt-4 w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-black py-3 rounded-2xl transition text-xs uppercase tracking-widest">
                Continuar Jugando <i class="fas fa-arrow-right ml-1"></i>
            </button>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    const CSRF   = document.querySelector('meta[name="csrf-token"]').content;
    const URL_CELDA     = '/sgspi/celda/';
    const URL_RESPONDER = '/sgspi/responder';
    const URL_FINALIZAR = '/sgspi/finalizar';

    let esperandoRespuesta = false;
    let celda_actual = null;

    // Contadores iniciales desde PHP
    let scoreActual     = {{ $game['score'] }};
    let correctasActual = {{ $game['correctas'] }};
    let reveladasActual = {{ count(array_filter($game['cells'], fn($c) => $c['revealed'])) }};
    const totalCeldas   = {{ $game['total_celdas'] }};
    const totalPreguntas = {{ $game['total_preguntas'] }};

    actualizarUI();

    function actualizarUI() {
        document.getElementById('score-display').textContent      = scoreActual;
        document.getElementById('correctas-display').innerHTML    = correctasActual + '<span class="text-gray-300 text-sm">/' + totalPreguntas + '</span>';
        document.getElementById('reveladas-display').innerHTML    = reveladasActual + '<span class="text-gray-300 text-sm">/25</span>';
    }

    function clickCelda(index) {
        if (esperandoRespuesta) return;

        const cell = document.getElementById('cell-' + index);
        if (cell.classList.contains('revealed')) return;

        esperandoRespuesta = true;
        cell.innerHTML = '<div class="w-5 h-5 border-2 border-[#39A900] border-t-transparent rounded-full animate-spin"></div>';

        fetch(URL_CELDA + index, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json', 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.error) { esperandoRespuesta = false; return; }

            if (data.type === 'safe') {
                revelarSafe(index);
                reveladasActual = data.total_reveladas;
                scoreActual     = data.score;
                actualizarUI();
                verificarFin(data.total_reveladas, totalCeldas);
                esperandoRespuesta = false;
            } else {
                celda_actual = data.cell_index;
                mostrarModal(data);
                cell.innerHTML = '<i class="fas fa-lock text-gray-400 text-xl"></i>';
                esperandoRespuesta = false;
            }
        })
        .catch(() => { esperandoRespuesta = false; });
    }

    function revelarSafe(index) {
        const cell = document.getElementById('cell-' + index);
        cell.classList.remove('bg-gray-700', 'hover:bg-gray-600', 'border-gray-600');
        cell.classList.add('bg-blue-50', 'border-blue-200', 'revealed', 'cell-flip');
        cell.onclick = null;
        cell.innerHTML = '<i class="fas fa-shield-alt text-blue-400 text-xl"></i>';
    }

    function revelarCorrecta(index) {
        const cell = document.getElementById('cell-' + index);
        cell.classList.remove('bg-gray-700', 'hover:bg-gray-600', 'border-gray-600');
        cell.classList.add('bg-green-50', 'border-green-200', 'revealed', 'cell-flip');
        cell.onclick = null;
        cell.innerHTML = '<i class="fas fa-check-circle text-green-500 text-xl"></i>';
    }

    function revelarIncorrecta(index) {
        const cell = document.getElementById('cell-' + index);
        cell.classList.remove('bg-gray-700', 'hover:bg-gray-600', 'border-gray-600');
        cell.classList.add('bg-red-50', 'border-red-200', 'revealed', 'cell-flip');
        cell.onclick = null;
        cell.innerHTML = '<i class="fas fa-times-circle text-red-400 text-xl"></i>';
    }

    function mostrarModal(data) {
        document.getElementById('modal-tema').textContent    = data.tema;
        document.getElementById('modal-pregunta').textContent = data.pregunta;
        document.getElementById('modal-feedback').classList.add('hidden');

        const opcionesDiv = document.getElementById('modal-opciones');
        opcionesDiv.innerHTML = '';

        for (const [letra, texto] of Object.entries(data.opciones)) {
            const btn = document.createElement('button');
            btn.className = 'opcion-btn w-full text-left px-4 py-3 rounded-xl border-2 border-gray-100 bg-gray-50 hover:border-[#39A900] hover:bg-green-50 transition text-sm font-bold text-gray-700 flex items-start gap-3';
            btn.innerHTML = `<span class="font-black text-[#39A900] w-5 flex-shrink-0">${letra}.</span><span>${texto}</span>`;
            btn.onclick = () => enviarRespuesta(letra);
            opcionesDiv.appendChild(btn);
        }

        document.getElementById('modal-overlay').classList.remove('hidden');
    }

    function enviarRespuesta(answer) {
        // Deshabilitar botones
        document.querySelectorAll('.opcion-btn').forEach(b => {
            b.disabled = true;
            b.classList.add('opacity-60', 'cursor-not-allowed');
        });

        fetch(URL_RESPONDER, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ cell_index: celda_actual, answer: answer })
        })
        .then(r => r.json())
        .then(data => {
            // Resaltar opciones
            document.querySelectorAll('.opcion-btn').forEach(btn => {
                const letra = btn.querySelector('span').textContent.replace('.', '').trim();
                if (letra === data.respuesta_correcta) {
                    btn.classList.add('border-green-400', 'bg-green-50', 'text-green-800');
                    btn.classList.remove('opacity-60');
                } else if (letra === answer && !data.correct) {
                    btn.classList.add('border-red-300', 'bg-red-50', 'text-red-700');
                }
            });

            // Mostrar feedback
            const box = document.getElementById('feedback-box');
            if (data.correct) {
                box.className = 'rounded-2xl p-4 text-sm font-bold bg-green-50 border border-green-200 text-green-800';
                document.getElementById('feedback-titulo').textContent = '✓ ¡Correcto! +10 puntos';
            } else {
                box.className = 'rounded-2xl p-4 text-sm font-bold bg-red-50 border border-red-200 text-red-800';
                document.getElementById('feedback-titulo').textContent = '✗ Respuesta incorrecta';
                document.getElementById('feedback-respuesta').textContent = 'La respuesta correcta era: ' + data.respuesta_correcta;
            }
            document.getElementById('feedback-explicacion').textContent = data.explicacion || '';
            document.getElementById('modal-feedback').classList.remove('hidden');

            // Actualizar celda y contadores
            if (data.correct) {
                revelarCorrecta(celda_actual);
            } else {
                revelarIncorrecta(celda_actual);
            }

            scoreActual     = data.score;
            correctasActual = data.correctas;
            reveladasActual = data.total_reveladas;
            actualizarUI();
            verificarFin(data.total_reveladas, data.total_celdas);
        });
    }

    function cerrarModal() {
        document.getElementById('modal-overlay').classList.add('hidden');
        celda_actual = null;
    }

    // Cerrar modal al hacer clic fuera
    document.getElementById('modal-overlay').addEventListener('click', function(e) {
        if (e.target === this && document.getElementById('modal-feedback').classList.contains('hidden') === false) {
            cerrarModal();
        }
    });

    function verificarFin(reveladas, total) {
        if (reveladas >= total) {
            document.getElementById('btn-finalizar-wrap').classList.remove('hidden');
            document.getElementById('btn-finalizar-wrap').scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    function finalizarJuego() {
        cerrarModal();
        fetch(URL_FINALIZAR, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json', 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.resultado_id) {
                window.location.href = '/sgspi/resultado/' + data.resultado_id;
            }
        });
    }
</script>
@endpush
