@extends('layouts.app')
@section('content')
<div class="max-w-2xl mx-auto space-y-8">

    <div>
        <h1 class="text-3xl font-black text-gray-800 uppercase italic tracking-tighter">
            Configuración <span class="text-[#1e3a5f]">Detector Phishing</span>
        </h1>
        <p class="text-gray-400 text-sm font-bold italic">SGSPI — Parámetros del juego</p>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 rounded-2xl px-5 py-4 flex items-center gap-3">
            <i class="fas fa-check-circle text-green-500"></i>
            <span class="text-xs font-bold">{{ session('success') }}</span>
        </div>
    @endif

    <form action="{{ route('sgspi.phishing.admin.config.update') }}" method="POST"
          class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 space-y-7">
        @csrf @method('PUT')

        {{-- Escenarios por partida --}}
        <div>
            <label class="block text-[10px] font-black text-gray-400 uppercase mb-1 tracking-widest">
                Escenarios por Partida
            </label>
            <div class="flex items-center gap-4">
                <input type="number" name="escenarios"
                    value="{{ old('escenarios', $config->escenarios) }}"
                    min="4" max="{{ $totalBanco }}"
                    class="w-32 bg-gray-50 border-gray-200 rounded-xl p-3 font-black text-2xl text-center text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#1e3a5f] @error('escenarios') ring-2 ring-red-400 @enderror">
                <div class="text-xs text-gray-400 font-bold leading-relaxed">
                    <p>Emails aleatorios que se presentan en cada partida.</p>
                    <p class="text-gray-300 mt-0.5">Banco disponible: <strong class="text-[#1e3a5f]">{{ $totalBanco }} escenarios</strong> ({{ intdiv($totalBanco, 4) }} por nivel).</p>
                    <p class="text-gray-300 mt-0.5">La selección se distribuye proporcionalmente entre los 4 niveles.</p>
                </div>
            </div>
            @error('escenarios')
                <p class="text-red-500 text-[10px] font-bold mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Vista previa calculada --}}
        <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Vista previa de la configuración</p>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-center">
                <div class="bg-white rounded-xl p-3 border border-gray-200">
                    <p id="prev-escenarios" class="text-2xl font-black text-[#1e3a5f]">{{ $config->escenarios }}</p>
                    <p class="text-[10px] font-black text-gray-400 uppercase mt-1">Escenarios</p>
                </div>
                <div class="bg-white rounded-xl p-3 border border-gray-200">
                    <p id="prev-pts" class="text-2xl font-black text-[#39A900]">{{ $config->escenarios * 10 }}</p>
                    <p class="text-[10px] font-black text-gray-400 uppercase mt-1">Pts. Base</p>
                </div>
                <div class="bg-white rounded-xl p-3 border border-gray-200">
                    <p id="prev-bonus" class="text-2xl font-black text-yellow-500">{{ $config->escenarios * 5 }}</p>
                    <p class="text-[10px] font-black text-gray-400 uppercase mt-1">Pts. Bonus</p>
                </div>
                <div class="bg-white rounded-xl p-3 border border-gray-200">
                    <p id="prev-max" class="text-2xl font-black text-purple-500">{{ $config->puntajeMaximo() }}</p>
                    <p class="text-[10px] font-black text-gray-400 uppercase mt-1">Pts. Máximos</p>
                </div>
            </div>

            {{-- Desglose por nivel --}}
            <div class="mt-4">
                <p class="text-[10px] font-black text-gray-300 uppercase tracking-widest mb-2">Distribución por nivel</p>
                <div class="grid grid-cols-4 gap-2" id="nivel-breakdown">
                    @php
                        $base  = (int) floor($config->escenarios / 4);
                        $extra = $config->escenarios % 4;
                        $niveles = [
                            ['Fácil',    'bg-green-100 text-green-700'],
                            ['Medio',    'bg-blue-100 text-blue-700'],
                            ['Difícil',  'bg-orange-100 text-orange-700'],
                            ['Avanzado', 'bg-red-100 text-red-700'],
                        ];
                    @endphp
                    @foreach($niveles as $i => [$label, $color])
                    <div class="bg-white rounded-xl p-2.5 border border-gray-200 text-center">
                        <p class="text-lg font-black text-gray-700 nivel-count" data-idx="{{ $i }}">{{ $base + ($i < $extra ? 1 : 0) }}</p>
                        <span class="{{ $color }} text-[9px] font-black px-1.5 py-0.5 rounded-full uppercase">{{ $label }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="flex gap-3 pt-2">
            <a href="{{ route('sgspi.admin.resultados') }}#phishing"
               class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-600 font-black py-4 rounded-2xl transition text-xs uppercase tracking-widest">
                Cancelar
            </a>
            <button type="submit"
                class="flex-1 bg-[#1e3a5f] text-white font-black py-4 rounded-2xl shadow-lg hover:scale-[1.02] transition-transform active:scale-95 uppercase tracking-widest text-xs">
                <i class="fas fa-save mr-2"></i> Guardar Configuración
            </button>
        </div>
    </form>

</div>
@endsection

@push('scripts')
<script>
    const inEscenarios = document.querySelector('[name="escenarios"]');

    function actualizarPreview() {
        const n = parseInt(inEscenarios.value) || 0;
        const base  = Math.floor(n / 4);
        const extra = n % 4;

        document.getElementById('prev-escenarios').textContent = n;
        document.getElementById('prev-pts').textContent        = n * 10;
        document.getElementById('prev-bonus').textContent      = n * 5;
        document.getElementById('prev-max').textContent        = n * 15;

        document.querySelectorAll('.nivel-count').forEach((el, i) => {
            el.textContent = base + (i < extra ? 1 : 0);
        });
    }

    inEscenarios.addEventListener('input', actualizarPreview);
</script>
@endpush
