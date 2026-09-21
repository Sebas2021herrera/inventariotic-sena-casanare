@extends('layouts.public')
@section('title', 'Detector de Phishing — SGSPI')

@section('content')
<div class="max-w-2xl mx-auto py-4 px-2" id="phishing-app">

    {{-- HUD superior --}}
    <div class="flex items-center justify-between mb-4 flex-wrap gap-2">
        <div>
            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Jugador</span>
            <p class="font-black text-gray-800 text-sm">{{ $participante->nombre }}</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="text-center bg-white rounded-2xl border border-gray-100 shadow-sm px-4 py-2 min-w-[72px]">
                <p class="text-xl font-black text-[#1e3a5f]" id="hud-score">0</p>
                <p class="text-[9px] font-black text-gray-400 uppercase">Puntos</p>
            </div>
            <div class="text-center bg-white rounded-2xl border border-gray-100 shadow-sm px-4 py-2 min-w-[72px]">
                <p class="text-xl font-black text-[#39A900]" id="hud-correctas">0</p>
                <p class="text-[9px] font-black text-gray-400 uppercase">Correctas</p>
            </div>
            <div class="text-center bg-white rounded-2xl border border-gray-100 shadow-sm px-4 py-2 min-w-[72px]">
                <span class="text-[10px] font-black uppercase" id="hud-nivel-badge">Nivel 1</span>
                <p class="text-[9px] font-black text-gray-400 uppercase">Nivel</p>
            </div>
        </div>
    </div>

    {{-- Barra de progreso --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-3 mb-4">
        <div class="flex justify-between text-[10px] font-black text-gray-400 uppercase mb-2">
            <span id="prog-label">Escenario 1 de 20</span>
            <span id="prog-nivel-label">Nivel 1 · Fácil</span>
        </div>
        <div class="h-2.5 bg-gray-100 rounded-full overflow-hidden">
            <div id="prog-bar" class="h-full bg-gradient-to-r from-[#1e3a5f] to-[#39A900] rounded-full transition-all duration-500" style="width:5%"></div>
        </div>
        {{-- Sub-barra de nivel --}}
        <div class="flex gap-1 mt-2">
            @foreach([['green','N1'],['blue','N2'],['orange','N3'],['red','N4']] as $i=>[$c,$lbl])
            <div class="flex-1 h-1.5 rounded-full bg-gray-100 overflow-hidden">
                <div id="nivel-bar-{{ $i+1 }}" class="h-full bg-{{ $c }}-400 rounded-full transition-all duration-300" style="width:0%"></div>
            </div>
            @endforeach
        </div>
        <div class="flex gap-1 mt-1">
            @foreach([['green','Fácil'],['blue','Medio'],['orange','Difícil'],['red','Avanzado']] as $i=>[$c,$lbl])
            <div class="flex-1 text-center text-[9px] font-black uppercase text-{{ $c }}-500" id="nivel-lbl-{{ $i+1 }}">{{ $lbl }}</div>
            @endforeach
        </div>
    </div>

    {{-- Email simulado --}}
    <div class="bg-white rounded-3xl shadow-md border border-gray-200 overflow-hidden mb-4" id="email-card">

        {{-- Toolbar email client --}}
        <div class="bg-gray-50 border-b border-gray-200 px-4 py-2 flex items-center gap-2">
            <div class="flex gap-1.5">
                <div class="w-3 h-3 bg-red-400 rounded-full"></div>
                <div class="w-3 h-3 bg-yellow-400 rounded-full"></div>
                <div class="w-3 h-3 bg-green-400 rounded-full"></div>
            </div>
            <span class="text-[11px] text-gray-400 font-bold ml-2">📧 Bandeja de entrada — 1 mensaje sin leer</span>
        </div>

        {{-- Header del email --}}
        <div class="border-b border-gray-100 p-4 space-y-2 bg-white">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 bg-[#1e3a5f]/10 rounded-xl flex items-center justify-center shrink-0 text-lg" id="email-avatar">📧</div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <span class="font-black text-gray-800 text-sm" id="email-de-nombre"></span>
                        </div>
                        <span class="text-[11px] text-gray-400 shrink-0" id="email-fecha"></span>
                    </div>
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <span class="text-[11px] text-gray-400">De:</span>
                        <span class="text-[11px] font-mono text-gray-600 group relative cursor-help" id="email-de-email">
                            <span class="border-b border-dotted border-gray-400" id="email-de-email-txt"></span>
                        </span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="text-[11px] text-gray-400">Para:</span>
                        <span class="text-[11px] font-mono text-gray-500" id="email-para"></span>
                    </div>
                </div>
            </div>
            <div class="mt-2 pt-2 border-t border-gray-50">
                <p class="font-black text-gray-800 text-base" id="email-asunto"></p>
            </div>
        </div>

        {{-- Cuerpo del email --}}
        <div class="p-5 min-h-[160px]">
            <div class="text-sm text-gray-700 leading-relaxed whitespace-pre-line" id="email-cuerpo"></div>
            <div id="email-enlace-wrap" class="mt-4 hidden">
                <a href="#" id="email-enlace" onclick="return false;"
                   class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition cursor-default relative group">
                    <i class="fas fa-external-link-alt text-[10px]"></i>
                    <span id="email-enlace-txt"></span>
                    {{-- Tooltip URL --}}
                    <span id="email-url-tooltip"
                          class="absolute bottom-full left-0 mb-2 bg-gray-900 text-gray-100 text-[10px] font-mono px-3 py-1.5 rounded-lg whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none shadow-xl z-10">
                    </span>
                </a>
                <p class="text-[10px] text-gray-400 mt-1.5 flex items-center gap-1">
                    <i class="fas fa-info-circle"></i> Pasa el cursor sobre el botón para ver la URL real
                </p>
            </div>
        </div>

        {{-- Indicador de nivel en el email --}}
        <div class="px-5 pb-3">
            <span id="nivel-badge-email" class="text-[10px] font-black px-3 py-1 rounded-full uppercase"></span>
        </div>
    </div>

    {{-- Botones de decisión --}}
    <div id="decision-btns" class="grid grid-cols-2 gap-3 mb-4">
        <button onclick="responder(false)"
                class="flex flex-col items-center justify-center gap-2 bg-red-50 hover:bg-red-100 border-2 border-red-200 hover:border-red-400 text-red-700 font-black py-5 rounded-2xl transition-all hover:scale-[1.02] active:scale-95 text-sm uppercase tracking-widest">
            <span class="text-2xl">🎣</span>
            Es PHISHING
        </button>
        <button onclick="responder(true)"
                class="flex flex-col items-center justify-center gap-2 bg-green-50 hover:bg-green-100 border-2 border-green-200 hover:border-green-400 text-green-700 font-black py-5 rounded-2xl transition-all hover:scale-[1.02] active:scale-95 text-sm uppercase tracking-widest">
            <span class="text-2xl">✅</span>
            Es LEGÍTIMO
        </button>
    </div>

    {{-- Panel de resultado por escenario (oculto inicialmente) --}}
    <div id="result-panel" class="hidden bg-white rounded-3xl border-2 shadow-lg p-5 mb-4">
        <div class="flex items-start gap-4 mb-4">
            <div id="result-icon" class="text-4xl shrink-0"></div>
            <div>
                <p id="result-titulo" class="font-black text-lg uppercase tracking-tight"></p>
                <p id="result-explicacion" class="text-sm text-gray-600 mt-1 leading-relaxed"></p>
            </div>
        </div>

        {{-- Señales --}}
        <div id="senales-wrap" class="hidden mb-4">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">🔍 Señales de alerta en este email</p>
            <div id="senales-list" class="space-y-2"></div>
        </div>

        {{-- Bonus --}}
        <div id="bonus-wrap" class="hidden bg-yellow-50 border border-yellow-200 rounded-2xl p-4 mb-4">
            <p class="text-sm font-black text-yellow-700 mb-3">
                <i class="fas fa-star mr-1"></i> ¿Identificaste todas las señales de alerta antes de responder?
            </p>
            <div class="flex gap-2">
                <button onclick="darBonus(true)"
                        class="flex-1 bg-yellow-400 hover:bg-yellow-500 text-white font-black py-2.5 rounded-xl text-xs uppercase tracking-widest transition">
                    ✅ Sí, las vi todas (+5 pts)
                </button>
                <button onclick="darBonus(false)"
                        class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-600 font-black py-2.5 rounded-xl text-xs uppercase tracking-widest transition">
                    No del todo
                </button>
            </div>
        </div>

        <button id="btn-siguiente" onclick="siguiente()"
                class="w-full bg-[#1e3a5f] hover:bg-[#0f2236] text-white font-black py-3.5 rounded-2xl text-xs uppercase tracking-widest transition">
            <i class="fas fa-arrow-right mr-1"></i> Siguiente escenario
        </button>
    </div>

</div>

{{-- Datos escenarios --}}
<script>
const PARTICIPANTE_ID = {{ $participante->id }};
const FINALIZAR_URL   = "{{ route('sgspi.phishing.finalizar') }}";

const RESULTADO_BASE  = "{{ url('/sgspi/phishing/resultado') }}";
const CSRF            = "{{ csrf_token() }}";

const ESCENARIOS = @json($escenarios);

const NIVEL_CONFIG = {
    1: { label: 'Fácil',    color: 'green',  badgeClass: 'bg-green-100 text-green-700' },
    2: { label: 'Medio',    color: 'blue',   badgeClass: 'bg-blue-100 text-blue-700' },
    3: { label: 'Difícil',  color: 'orange', badgeClass: 'bg-orange-100 text-orange-700' },
    4: { label: 'Avanzado', color: 'red',    badgeClass: 'bg-red-100 text-red-700' },
};

let idx        = 0;   // escenario actual
let score      = 0;
let correctas  = 0;
let bonus      = 0;
let esperandoBonus = false;
let respuestaCorrecta = null;

function cargarEscenario() {
    if (idx >= ESCENARIOS.length) { terminar(); return; }

    const e   = ESCENARIOS[idx];
    const cfg = NIVEL_CONFIG[e.nivel];

    // HUD
    document.getElementById('hud-score').textContent    = score;
    document.getElementById('hud-correctas').textContent = correctas;
    const nb = document.getElementById('hud-nivel-badge');
    nb.textContent  = 'Nivel ' + e.nivel;
    nb.className    = 'text-[11px] font-black uppercase px-2 py-0.5 rounded-full ' + cfg.badgeClass;

    // Barra progreso global
    document.getElementById('prog-label').textContent       = 'Escenario ' + (idx+1) + ' de ' + ESCENARIOS.length;
    document.getElementById('prog-nivel-label').textContent = 'Nivel ' + e.nivel + ' · ' + cfg.label;
    document.getElementById('prog-bar').style.width         = Math.round(((idx+1)/ESCENARIOS.length)*100) + '%';

    // Barras por nivel (5 por nivel)
    for (let n = 1; n <= 4; n++) {
        const escNivel  = ESCENARIOS.filter(x => x.nivel === n);
        const idxNivel  = ESCENARIOS.slice(0, idx + 1).filter(x => x.nivel === n).length;
        const pct       = Math.round((idxNivel / escNivel.length) * 100);
        document.getElementById('nivel-bar-' + n).style.width = pct + '%';
    }

    // Email
    document.getElementById('email-de-nombre').textContent = e.de_nombre;
    document.getElementById('email-de-email-txt').textContent = e.de_email;
    document.getElementById('email-para').textContent       = e.para;
    document.getElementById('email-asunto').textContent     = e.asunto;
    document.getElementById('email-fecha').textContent      = e.fecha;
    document.getElementById('email-cuerpo').textContent     = e.cuerpo;

    // Enlace
    const enlaceWrap = document.getElementById('email-enlace-wrap');
    if (e.enlace_txt) {
        enlaceWrap.classList.remove('hidden');
        document.getElementById('email-enlace-txt').textContent = e.enlace_txt;
        document.getElementById('email-url-tooltip').textContent = e.enlace_url;
    } else {
        enlaceWrap.classList.add('hidden');
    }

    // Avatar emoji por nivel
    const avatares = { 1:'📧', 2:'📩', 3:'💼', 4:'⚠️' };
    document.getElementById('email-avatar').textContent = avatares[e.nivel] || '📧';

    // Badge nivel en email
    const badge = document.getElementById('nivel-badge-email');
    badge.textContent = 'Nivel ' + e.nivel + ' · ' + cfg.label;
    badge.className   = 'text-[10px] font-black px-3 py-1 rounded-full uppercase ' + cfg.badgeClass;

    // Reset panel
    document.getElementById('result-panel').classList.add('hidden');
    document.getElementById('decision-btns').classList.remove('hidden');
    document.getElementById('bonus-wrap').classList.add('hidden');
    document.getElementById('senales-wrap').classList.add('hidden');
    esperandoBonus    = false;
    respuestaCorrecta = null;
}

function responder(esLegitimo) {
    const e = ESCENARIOS[idx];
    const correcto = (esLegitimo === !e.es_phishing);
    respuestaCorrecta = correcto;

    if (correcto) { score += 10; correctas++; }

    // Ocultar botones
    document.getElementById('decision-btns').classList.add('hidden');

    // Mostrar panel
    const panel = document.getElementById('result-panel');
    panel.classList.remove('hidden');

    if (correcto) {
        panel.className = panel.className.replace(/border-\w+-\d+/g, '') + ' border-green-300';
        document.getElementById('result-icon').textContent  = '✅';
        document.getElementById('result-titulo').textContent = correcto && e.es_phishing ? '¡Correcto! Era PHISHING' : '¡Correcto! Es un email legítimo';
        document.getElementById('result-titulo').className  = 'font-black text-lg uppercase tracking-tight text-green-700';
    } else {
        panel.className = panel.className.replace(/border-\w+-\d+/g, '') + ' border-red-300';
        document.getElementById('result-icon').textContent  = '❌';
        document.getElementById('result-titulo').textContent = e.es_phishing ? 'Incorrecto — Sí era PHISHING' : 'Incorrecto — Era un email legítimo';
        document.getElementById('result-titulo').className  = 'font-black text-lg uppercase tracking-tight text-red-700';
    }

    document.getElementById('result-explicacion').textContent = e.explicacion;

    // Señales (solo si es phishing)
    const sWrap = document.getElementById('senales-wrap');
    const sList = document.getElementById('senales-list');
    sList.innerHTML = '';
    if (e.senales && e.senales.length > 0) {
        sWrap.classList.remove('hidden');
        e.senales.forEach(s => {
            const div = document.createElement('div');
            div.className = 'flex items-start gap-2 bg-red-50 border border-red-100 rounded-xl p-2.5';
            div.innerHTML = `<span class="text-base shrink-0">${s.icon}</span><span class="text-xs text-red-700 font-bold">${s.texto}</span>`;
            sList.appendChild(div);
        });
    }

    // Bonus solo si fue correcto y es phishing
    if (correcto && e.es_phishing && e.senales && e.senales.length > 0) {
        document.getElementById('bonus-wrap').classList.remove('hidden');
        document.getElementById('btn-siguiente').classList.add('hidden');
        esperandoBonus = true;
    } else {
        document.getElementById('btn-siguiente').classList.remove('hidden');
    }

    // Actualizar HUD
    document.getElementById('hud-score').textContent     = score;
    document.getElementById('hud-correctas').textContent = correctas;
}

function darBonus(si) {
    if (si) { score += 5; bonus++; }
    document.getElementById('bonus-wrap').classList.add('hidden');
    document.getElementById('btn-siguiente').classList.remove('hidden');
    document.getElementById('hud-score').textContent = score;
    esperandoBonus = false;
}

function siguiente() {
    idx++;
    if (idx >= ESCENARIOS.length) {
        terminar();
    } else {
        cargarEscenario();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
}

function terminar() {
    const nivelAlcanzado = Math.max(...ESCENARIOS.slice(0, idx).map(e => e.nivel));

    fetch(FINALIZAR_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({
            puntaje:          score,
            correctas:        correctas,
            total:            ESCENARIOS.length,
            bonus:            bonus,
            nivel_alcanzado:  nivelAlcanzado,
        }),
    })
    .then(r => r.json())
    .then(data => {
        if (data.resultado_id) {
            window.location.href = RESULTADO_BASE + '/' + data.resultado_id;
        }
    });
}

// Iniciar
document.addEventListener('DOMContentLoaded', cargarEscenario);
</script>
@endsection
