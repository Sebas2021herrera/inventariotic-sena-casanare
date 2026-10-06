@extends('layouts.app')

@section('content')
@php
$estados = [
    'CUMPLE'              => ['label'=>'Cumple',              'bg'=>'bg-green-100',  'text'=>'text-green-700',  'border'=>'border-green-400',  'dot'=>'bg-green-500'],
    'CUMPLE PARCIALMENTE' => ['label'=>'Cumple Parcialmente', 'bg'=>'bg-yellow-100', 'text'=>'text-yellow-700', 'border'=>'border-yellow-400', 'dot'=>'bg-yellow-500'],
    'NO CUMPLE'           => ['label'=>'No Cumple',           'bg'=>'bg-red-100',    'text'=>'text-red-700',    'border'=>'border-red-400',    'dot'=>'bg-red-500'],
    'NO APLICA'           => ['label'=>'No Aplica',           'bg'=>'bg-gray-100',   'text'=>'text-gray-500',   'border'=>'border-gray-300',   'dot'=>'bg-gray-400'],
];
@endphp

<div class="max-w-4xl mx-auto">

    {{-- Cabecera --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-black text-gray-800 uppercase italic tracking-tighter">
                Verificación <span class="text-[#39A900]">GIL-F-102</span>
            </h1>
            <p class="text-gray-500 text-sm font-bold italic">
                {{ $area->codigo }} — {{ $area->nombre_dependencia }}
            </p>
        </div>
        <a href="{{ route('areas-seguras.show', $area) }}"
           class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-5 py-2 rounded-xl font-bold transition flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-xl">
            <ul class="text-red-700 text-sm list-disc list-inside space-y-1">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('areas-seguras.verificacion.store', $area) }}" method="POST" class="space-y-6">
        @csrf

        {{-- ── Bloque 1: Identificación de la Inspección ─────────────────── --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <div class="flex items-center gap-2 mb-5 border-b pb-2">
                <span class="bg-[#1e3a5f] text-white text-[9px] font-black px-2 py-0.5 rounded">BLOQUE 1</span>
                <h2 class="text-[10px] font-black text-gray-600 uppercase tracking-widest">Identificación de la Inspección</h2>
            </div>

            {{-- Datos del área (solo lectura) --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-5 bg-gray-50 rounded-xl p-4">
                <div>
                    <p class="text-[9px] font-black text-gray-400 uppercase">Nombre del Área</p>
                    <p class="text-xs font-bold text-gray-700">{{ $area->nombre_dependencia }}</p>
                </div>
                <div>
                    <p class="text-[9px] font-black text-gray-400 uppercase">Código / ID</p>
                    <p class="text-xs font-bold text-gray-700 font-mono">{{ $area->codigo }}</p>
                </div>
                <div>
                    <p class="text-[9px] font-black text-gray-400 uppercase">Clasificación</p>
                    <p class="text-xs font-bold text-gray-700">{{ $area->nivel_sena }}</p>
                </div>
                <div>
                    <p class="text-[9px] font-black text-gray-400 uppercase">Zonificación</p>
                    <p class="text-xs font-bold text-gray-700">{{ $area->zonificacion ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-[9px] font-black text-gray-400 uppercase">Responsable</p>
                    <p class="text-xs font-bold text-gray-700">{{ $area->responsable_cargo }}</p>
                </div>
                <div>
                    <p class="text-[9px] font-black text-gray-400 uppercase">Ubicación</p>
                    <p class="text-xs font-bold text-gray-700">
                        @if($area->bloque)Blq. {{ $area->bloque }}@endif
                        @if($area->piso) Piso {{ $area->piso }}@endif
                        @if($area->numero_oficina) Of. {{ $area->numero_oficina }}@endif
                        @if(!$area->bloque && !$area->piso && !$area->numero_oficina)—@endif
                    </p>
                </div>
            </div>

            {{-- Datos de la inspección (editables) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Fecha de Inspección *</label>
                    <input type="date" name="fecha_verificacion"
                           value="{{ old('fecha_verificacion', now()->format('Y-m-d')) }}" required
                           class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-[#39A900]">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Período / Corte *</label>
                    <input type="text" name="corte"
                           value="{{ old('corte', now()->translatedFormat('F Y')) }}" required
                           class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-[#39A900]"
                           placeholder="Ej: Septiembre 2026">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Persona(s) que realizan la inspección</label>
                    <input type="text" name="inspectores"
                           value="{{ old('inspectores') }}"
                           class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-[#39A900]"
                           placeholder="Nombre(s) de los funcionarios que inspeccionan el área">
                </div>
            </div>
        </div>

        {{-- ── Bloque 2: Controles de Acceso Físico ──────────────────────── --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 bg-[#1e3a5f] text-white flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="bg-white/20 text-[9px] font-black px-2 py-0.5 rounded">BLOQUE 2</span>
                    <h2 class="font-black uppercase text-sm tracking-tight">Controles de Acceso Físico — Sección 5.4</h2>
                </div>
                <div id="contador-acceso" class="text-[10px] font-black bg-white/20 px-3 py-1 rounded-full">
                    0 / {{ count($controlesAcceso) }} evaluados
                </div>
            </div>

            {{-- Leyenda de estados --}}
            <div class="px-6 py-3 bg-gray-50 border-b border-gray-100 flex flex-wrap gap-3">
                @foreach($estados as $key => $est)
                <div class="flex items-center gap-1.5">
                    <div class="w-2.5 h-2.5 rounded-full {{ $est['dot'] }}"></div>
                    <span class="text-[9px] font-black text-gray-500 uppercase">{{ $est['label'] }}</span>
                </div>
                @endforeach
            </div>

            <div class="divide-y divide-gray-50">
                @foreach($controlesAcceso as $i => $ctrl)
                <div class="px-6 py-5 hover:bg-gray-50/50 transition control-row" data-index="{{ $i }}">
                    <div class="flex flex-col md:flex-row md:items-start gap-4">

                        {{-- Badge de código --}}
                        <div class="flex-shrink-0">
                            <span class="inline-block px-2 py-1.5 bg-[#1e3a5f] text-white rounded-lg text-[9px] font-black w-16 text-center leading-tight">
                                {{ $ctrl['codigo'] }}
                            </span>
                        </div>

                        {{-- Descripción del control --}}
                        <div class="flex-1">
                            <p class="text-sm text-gray-700 font-bold leading-snug">{{ $ctrl['item'] }}</p>
                        </div>

                        {{-- Selector de estado (4 opciones) --}}
                        <div class="flex-shrink-0">
                            <select name="items[{{ $i }}][estado]"
                                    onchange="actualizarEstado(this, {{ $i }})"
                                    class="estado-select bg-gray-100 border border-gray-200 rounded-xl px-3 py-2 text-xs font-black outline-none focus:ring-2 focus:ring-[#39A900] min-w-[160px]">
                                @foreach($estados as $key => $est)
                                    <option value="{{ $key }}" {{ old("items.{$i}.estado", 'NO APLICA') === $key ? 'selected' : '' }}>
                                        {{ $est['label'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Observación del ítem --}}
                    <div class="mt-3 md:ml-20">
                        <input type="text" name="items[{{ $i }}][observaciones]"
                               value="{{ old("items.{$i}.observaciones") }}"
                               placeholder="Observación o evidencia para este control..."
                               class="w-full bg-gray-50 border-gray-200 rounded-xl p-2.5 text-xs outline-none focus:ring-2 focus:ring-[#39A900]">
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- ── Bloque 3: Controles Medioambientales ──────────────────────── --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 bg-[#374151] text-white flex items-center gap-3">
                <span class="bg-white/20 text-[9px] font-black px-2 py-0.5 rounded">BLOQUE 3</span>
                <h2 class="font-black uppercase text-sm tracking-tight">Controles Medioambientales — Sección 5.5</h2>
            </div>

            <div class="px-6 py-5 border-b border-gray-100">
                <label class="block text-[10px] font-black text-gray-400 uppercase mb-3">
                    Tipo de área — selecciona el bloque que aplica para esta inspección
                </label>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    @foreach($controlesMedioambientales as $tipo => $items)
                    @php
                        $icon = str_contains($tipo,'Datos') ? 'fa-server' : (str_contains($tipo,'Archivo') ? 'fa-folder-open' : 'fa-building');
                        $selTipo = old('tipo_medioambiental') === $tipo;
                    @endphp
                    <label class="tipo-medio-card border-2 rounded-2xl p-4 cursor-pointer transition select-none
                                  {{ $selTipo ? 'border-gray-500 bg-gray-50' : 'border-gray-200 hover:border-gray-400' }}"
                           data-tipo="{{ $tipo }}">
                        <input type="radio" name="tipo_medioambiental" value="{{ $tipo }}"
                               {{ $selTipo ? 'checked' : '' }} class="sr-only"
                               onchange="mostrarMedioambiental(this.value)">
                        <div class="flex items-center gap-2 mb-1">
                            <i class="fas {{ $icon }} text-gray-500 text-sm"></i>
                            <span class="font-black text-[10px] text-gray-700">{{ $tipo }}</span>
                        </div>
                        <p class="text-[9px] text-gray-400">{{ count($items) }} controles</p>
                    </label>
                    @endforeach
                    <label class="tipo-medio-card border-2 rounded-2xl p-4 cursor-pointer transition select-none
                                  {{ old('tipo_medioambiental') === 'No Aplica' ? 'border-gray-300 bg-gray-50' : 'border-gray-200 hover:border-gray-300' }}"
                           data-tipo="No Aplica">
                        <input type="radio" name="tipo_medioambiental" value="No Aplica"
                               {{ old('tipo_medioambiental','No Aplica') === 'No Aplica' ? 'checked' : '' }}
                               class="sr-only" onchange="mostrarMedioambiental('No Aplica')">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-ban text-gray-300 text-sm"></i>
                            <span class="font-black text-[10px] text-gray-400">No aplica para esta área</span>
                        </div>
                    </label>
                </div>
            </div>

            @foreach($controlesMedioambientales as $tipo => $items)
            <div id="medio-{{ Str::slug($tipo) }}" class="hidden divide-y divide-gray-50">
                <div class="px-6 py-3 bg-gray-50 border-b border-gray-100">
                    <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest">{{ $tipo }}</p>
                </div>
                @foreach($items as $j => $item)
                <div class="px-6 py-4 hover:bg-gray-50/50 transition">
                    <div class="flex flex-col md:flex-row md:items-start gap-4">
                        <div class="flex-shrink-0">
                            <span class="inline-block px-2 py-1.5 bg-gray-600 text-white rounded-lg text-[9px] font-black w-16 text-center leading-tight">
                                {{ $item['codigo'] }}
                            </span>
                        </div>
                        <div class="flex-1">
                            @if(!empty($item['categoria']))
                                <p class="text-[9px] text-gray-400 font-black uppercase tracking-widest mb-0.5">{{ $item['categoria'] }}</p>
                            @endif
                            <p class="text-sm text-gray-700 font-bold leading-snug">{{ $item['nombre'] ?? $item['item'] ?? '' }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <select name="medioambiental[{{ $j }}][estado]"
                                    class="bg-gray-100 border border-gray-200 rounded-xl px-3 py-2 text-xs font-black outline-none focus:ring-2 focus:ring-gray-400 min-w-[160px]">
                                @foreach($estados as $key => $est)
                                    <option value="{{ $key }}" {{ old("medioambiental.{$j}.estado", 'NO APLICA') === $key ? 'selected' : '' }}>
                                        {{ $est['label'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mt-3 md:ml-20">
                        <input type="text" name="medioambiental[{{ $j }}][observaciones]"
                               value="{{ old("medioambiental.{$j}.observaciones") }}"
                               placeholder="Observación o evidencia..."
                               class="w-full bg-gray-50 border-gray-200 rounded-xl p-2.5 text-xs outline-none focus:ring-2 focus:ring-gray-400">
                    </div>
                </div>
                @endforeach
            </div>
            @endforeach
        </div>

        {{-- ── Observaciones Generales ────────────────────────────────────── --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-3">
                <label class="block text-[10px] font-black text-gray-400 uppercase">Observaciones Generales</label>
                <div id="resumen-badge" class="text-[10px] font-black bg-gray-100 text-gray-500 px-3 py-1 rounded-full">
                    Sin evaluar
                </div>
            </div>
            <textarea name="observaciones_generales" rows="3"
                      class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-[#39A900] resize-none"
                      placeholder="Hallazgos relevantes, acciones correctivas recomendadas, aspectos a mejorar...">{{ old('observaciones_generales') }}</textarea>
        </div>

        <div class="flex gap-3 justify-end">
            <a href="{{ route('areas-seguras.show', $area) }}"
               class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-2xl font-black text-xs uppercase tracking-widest transition">
                Cancelar
            </a>
            <button type="submit"
                    class="px-8 py-3 bg-[#39A900] hover:bg-green-700 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg transition">
                <i class="fas fa-save mr-2"></i> Guardar Verificación GIL-F-102
            </button>
        </div>
    </form>
</div>

<script>
const TOTAL = {{ count($controlesAcceso) }};

function actualizarEstado(select, index) {
    const row   = select.closest('.control-row');
    const val   = select.value;
    row.classList.remove('bg-green-50/30','bg-yellow-50/30','bg-red-50/30','bg-gray-50/30');
    const clsMap = {
        'CUMPLE':'bg-green-50/30',
        'CUMPLE PARCIALMENTE':'bg-yellow-50/30',
        'NO CUMPLE':'bg-red-50/30',
        'NO APLICA':'bg-gray-50/30'
    };
    row.classList.add(clsMap[val] || '');
    actualizarResumen();
}

function actualizarResumen() {
    const selects  = document.querySelectorAll('select[name^="items["]');
    let cumple = 0, parcial = 0, noCumple = 0, noAplica = 0;
    selects.forEach(s => {
        if (s.value === 'CUMPLE') cumple++;
        else if (s.value === 'CUMPLE PARCIALMENTE') parcial++;
        else if (s.value === 'NO CUMPLE') noCumple++;
        else noAplica++;
    });
    const badge = document.getElementById('resumen-badge');
    badge.textContent = `C:${cumple} CP:${parcial} NC:${noCumple} NA:${noAplica} / ${TOTAL}`;
    badge.className = `text-[10px] font-black px-3 py-1 rounded-full ${
        noCumple > 0 ? 'bg-red-100 text-red-700' :
        parcial > 0  ? 'bg-yellow-100 text-yellow-700' :
                       'bg-green-100 text-green-700'
    }`;
    const cntBadge = document.getElementById('contador-acceso');
    const eval_ = cumple + parcial + noCumple;
    cntBadge.textContent = `${eval_} / ${TOTAL} evaluados`;
}

function mostrarMedioambiental(tipo) {
    // Ocultar todos los bloques medioambientales
    document.querySelectorAll('[id^="medio-"]').forEach(d => d.classList.add('hidden'));
    // Actualizar estilos de las tarjetas
    document.querySelectorAll('.tipo-medio-card').forEach(c => {
        c.classList.remove('border-gray-500','bg-gray-50','border-gray-300');
        c.classList.add('border-gray-200');
    });
    const card = document.querySelector(`.tipo-medio-card[data-tipo="${tipo}"]`);
    if (card) {
        card.classList.remove('border-gray-200');
        card.classList.add('border-gray-500','bg-gray-50');
    }
    if (tipo && tipo !== 'No Aplica') {
        const slug = tipo.replace(/[^a-z0-9]+/gi, '-').toLowerCase();
        const panel = document.getElementById(`medio-${slug}`);
        if (panel) panel.classList.remove('hidden');
    }
}

// Inicializar estado según valor previo
document.querySelectorAll('select[name^="items["]').forEach((s, i) => {
    if (s.value !== 'NO APLICA') actualizarEstado(s, i);
});
actualizarResumen();

// Mostrar bloque medioambiental según selección previa
const tipoInicialEl = document.querySelector('input[name="tipo_medioambiental"]:checked');
if (tipoInicialEl) mostrarMedioambiental(tipoInicialEl.value);

// Escuchar cambios en tipo medioambiental
document.querySelectorAll('.tipo-medio-card').forEach(card => {
    card.addEventListener('click', () => {
        const radio = card.querySelector('input[type=radio]');
        radio.checked = true;
        mostrarMedioambiental(card.dataset.tipo);
    });
});
</script>
@endsection
