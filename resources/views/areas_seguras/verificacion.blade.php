@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">

    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-black text-gray-800 uppercase italic tracking-tighter">
                Checklist <span class="text-[#39A900]">ISO 27001</span>
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

    <form action="{{ route('areas-seguras.verificacion.store', $area) }}" method="POST">
        @csrf

        {{-- Encabezado de la verificación --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-6">
            <h2 class="text-[10px] font-black text-[#39A900] uppercase tracking-widest mb-4 border-b pb-2">
                <i class="fas fa-calendar mr-2"></i> Datos de la Verificación
            </h2>
            <div class="grid grid-cols-2 gap-5">
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Fecha de Verificación *</label>
                    <input type="date" name="fecha_verificacion" value="{{ old('fecha_verificacion', now()->format('Y-m-d')) }}" required
                           class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-[#39A900]">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Corte / Periodo *</label>
                    <input type="text" name="corte" value="{{ old('corte', now()->translatedFormat('F Y')) }}" required
                           class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-[#39A900]"
                           placeholder="Ej: Octubre 2026">
                </div>
            </div>
        </div>

        {{-- Checklist --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
            <div class="px-6 py-4 bg-[#1e3a5f] text-white">
                <h2 class="font-black uppercase text-sm tracking-tight flex items-center gap-2">
                    <i class="fas fa-list-check"></i>
                    Verificación de Controles — Anexo A ISO 27001:2022 (Categoría Físicos)
                </h2>
                <p class="text-white/70 text-[10px] mt-1">Marca S (Sí Cumple) o N (No Cumple) para cada ítem</p>
            </div>

            <div class="divide-y divide-gray-50">
                @foreach($items as $i => $item)
                <div class="px-6 py-5 hover:bg-gray-50/50 transition">
                    <div class="flex flex-col md:flex-row md:items-start gap-4">

                        {{-- Control badge --}}
                        <div class="flex-shrink-0">
                            <span class="inline-block px-2 py-1 bg-[#1e3a5f] text-white rounded-lg text-[10px] font-black w-14 text-center">
                                {{ $item['control'] }}
                            </span>
                        </div>

                        {{-- Pregunta --}}
                        <div class="flex-1">
                            <p class="text-[10px] font-black text-[#39A900] uppercase mb-1">{{ $item['categoria'] }}</p>
                            <p class="text-sm text-gray-700 font-bold leading-snug">{{ $item['item'] }}</p>
                        </div>

                        {{-- Respuesta S/N --}}
                        <div class="flex-shrink-0 flex gap-3">
                            <label class="flex items-center gap-2 px-4 py-2 rounded-xl border-2 cursor-pointer transition
                                          {{ old("items.{$i}.cumple") === 'S' ? 'border-green-500 bg-green-50' : 'border-gray-200 hover:border-green-300' }}">
                                <input type="radio" name="items[{{ $i }}][cumple]" value="S"
                                       {{ old("items.{$i}.cumple") === 'S' ? 'checked' : '' }}
                                       class="accent-green-600"
                                       onchange="resaltarItem(this)">
                                <span class="text-xs font-black text-green-700">S — Cumple</span>
                            </label>
                            <label class="flex items-center gap-2 px-4 py-2 rounded-xl border-2 cursor-pointer transition
                                          {{ old("items.{$i}.cumple") === 'N' ? 'border-red-400 bg-red-50' : 'border-gray-200 hover:border-red-300' }}">
                                <input type="radio" name="items[{{ $i }}][cumple]" value="N"
                                       {{ old("items.{$i}.cumple") === 'N' ? 'checked' : '' }}
                                       class="accent-red-500"
                                       onchange="resaltarItem(this)">
                                <span class="text-xs font-black text-red-600">N — No Cumple</span>
                            </label>
                        </div>
                    </div>

                    {{-- Observaciones del ítem --}}
                    <div class="mt-3 ml-0 md:ml-18">
                        <input type="text" name="items[{{ $i }}][observaciones]"
                               value="{{ old("items.{$i}.observaciones") }}"
                               placeholder="Observación opcional para este ítem..."
                               class="w-full bg-gray-50 border-gray-200 rounded-xl p-2.5 text-xs outline-none focus:ring-2 focus:ring-[#39A900]">
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Observaciones generales + contador --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-6">
            <div class="flex items-center justify-between mb-3">
                <label class="block text-[10px] font-black text-gray-400 uppercase">Observaciones Generales</label>
                <div id="contador-badge" class="text-[10px] font-black text-gray-400 bg-gray-100 px-3 py-1 rounded-full">
                    0 / {{ count($items) }} conformes
                </div>
            </div>
            <textarea name="observaciones_generales" rows="3"
                      class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-[#39A900] resize-none"
                      placeholder="Aspectos a mejorar, hallazgos relevantes, acciones correctivas recomendadas...">{{ old('observaciones_generales') }}</textarea>
        </div>

        <div class="flex gap-3 justify-end">
            <a href="{{ route('areas-seguras.show', $area) }}"
               class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-2xl font-black text-xs uppercase tracking-widest transition">
                Cancelar
            </a>
            <button type="submit"
                    class="px-8 py-3 bg-[#39A900] hover:bg-green-700 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg transition">
                <i class="fas fa-save mr-2"></i> Guardar Verificación
            </button>
        </div>
    </form>
</div>

<script>
function resaltarItem(radio) {
    const fila = radio.closest('.px-6.py-5');
    fila.classList.remove('bg-green-50/30','bg-red-50/30');
    fila.classList.add(radio.value === 'S' ? 'bg-green-50/30' : 'bg-red-50/30');
    actualizarContador();
}

function actualizarContador() {
    const total   = document.querySelectorAll('input[type=radio][value="S"]').length / 1;
    const cumpleS = document.querySelectorAll('input[type=radio][value="S"]:checked').length;
    const totalItems = {{ count($items) }};
    document.getElementById('contador-badge').textContent = `${cumpleS} / ${totalItems} conformes`;
    const pct = Math.round(cumpleS / totalItems * 100);
    document.getElementById('contador-badge').className =
        `text-[10px] font-black px-3 py-1 rounded-full ${pct >= 70 ? 'bg-green-100 text-green-700' : pct >= 40 ? 'bg-orange-100 text-orange-700' : 'bg-red-100 text-red-700'}`;
}

document.querySelectorAll('input[type=radio]').forEach(r => r.addEventListener('change', actualizarContador));
</script>
@endsection
