@extends('layouts.app')
@section('content')
<div class="max-w-2xl mx-auto space-y-8">

    <div>
        <h1 class="text-3xl font-black text-gray-800 uppercase italic tracking-tighter">
            Configuración <span class="text-[#39A900]">Buscaminas</span>
        </h1>
        <p class="text-gray-400 text-sm font-bold italic">SGSPI — Parámetros del juego</p>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 rounded-2xl px-5 py-4 flex items-center gap-3">
            <i class="fas fa-check-circle text-green-500"></i>
            <span class="text-xs font-bold">{{ session('success') }}</span>
        </div>
    @endif

    <form action="{{ route('sgspi.admin.config.update') }}" method="POST" class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 space-y-7">
        @csrf @method('PUT')

        {{-- Total de celdas --}}
        <div>
            <label class="block text-[10px] font-black text-gray-400 uppercase mb-1 tracking-widest">
                Total de Celdas del Tablero
            </label>
            <div class="flex items-center gap-4">
                <input type="number" name="total_celdas"
                    value="{{ old('total_celdas', $config->total_celdas) }}"
                    min="4" max="100"
                    class="w-32 bg-gray-50 border-gray-200 rounded-xl p-3 font-black text-2xl text-center text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#39A900] @error('total_celdas') ring-2 ring-red-400 @enderror">
                <div class="text-xs text-gray-400 font-bold leading-relaxed">
                    <p>Número total de celdas en el tablero (preguntas + zonas seguras).</p>
                    <p class="text-gray-300 mt-0.5">Las celdas restantes serán zonas seguras.</p>
                </div>
            </div>
            @error('total_celdas')
                <p class="text-red-500 text-[10px] font-bold mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Número de preguntas --}}
        <div>
            <label class="block text-[10px] font-black text-gray-400 uppercase mb-1 tracking-widest">
                Número de Preguntas por Partida
            </label>
            <div class="flex items-center gap-4">
                <input type="number" name="preguntas"
                    value="{{ old('preguntas', $config->preguntas) }}"
                    min="1" max="{{ $totalBanco }}"
                    class="w-32 bg-gray-50 border-gray-200 rounded-xl p-3 font-black text-2xl text-center text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#39A900] @error('preguntas') ring-2 ring-red-400 @enderror">
                <div class="text-xs text-gray-400 font-bold leading-relaxed">
                    <p>Preguntas aleatorias del banco por sesión.</p>
                    <p class="text-gray-300 mt-0.5">Banco disponible: <strong class="text-[#39A900]">{{ $totalBanco }} preguntas</strong>. Debe ser ≤ total de celdas.</p>
                </div>
            </div>
            @error('preguntas')
                <p class="text-red-500 text-[10px] font-bold mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Columnas --}}
        <div>
            <label class="block text-[10px] font-black text-gray-400 uppercase mb-1 tracking-widest">
                Columnas del Tablero
            </label>
            <div class="flex items-center gap-4">
                <input type="number" name="columnas"
                    value="{{ old('columnas', $config->columnas) }}"
                    min="2" max="10"
                    class="w-32 bg-gray-50 border-gray-200 rounded-xl p-3 font-black text-2xl text-center text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#39A900] @error('columnas') ring-2 ring-red-400 @enderror">
                <div class="text-xs text-gray-400 font-bold leading-relaxed">
                    <p>Número de columnas del tablero visual.</p>
                    <p class="text-gray-300 mt-0.5">Las filas se calculan automáticamente (celdas ÷ columnas).</p>
                </div>
            </div>
            @error('columnas')
                <p class="text-red-500 text-[10px] font-bold mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Vista previa calculada --}}
        <div id="preview" class="bg-gray-50 rounded-2xl p-5 border border-gray-100">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Vista previa de la configuración</p>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-center">
                <div class="bg-white rounded-xl p-3 border border-gray-200">
                    <p id="prev-celdas" class="text-2xl font-black text-gray-800">{{ $config->total_celdas }}</p>
                    <p class="text-[10px] font-black text-gray-400 uppercase mt-1">Celdas</p>
                </div>
                <div class="bg-white rounded-xl p-3 border border-gray-200">
                    <p id="prev-preguntas" class="text-2xl font-black text-[#39A900]">{{ $config->preguntas }}</p>
                    <p class="text-[10px] font-black text-gray-400 uppercase mt-1">Preguntas</p>
                </div>
                <div class="bg-white rounded-xl p-3 border border-gray-200">
                    <p id="prev-safe" class="text-2xl font-black text-blue-400">{{ $config->total_celdas - $config->preguntas }}</p>
                    <p class="text-[10px] font-black text-gray-400 uppercase mt-1">Zonas Seguras</p>
                </div>
                <div class="bg-white rounded-xl p-3 border border-gray-200">
                    <p id="prev-puntaje" class="text-2xl font-black text-purple-500">{{ $config->preguntas * 10 }}</p>
                    <p class="text-[10px] font-black text-gray-400 uppercase mt-1">Pts. Máximos</p>
                </div>
            </div>
            <div class="mt-3 flex items-center gap-2 text-xs text-gray-400 font-bold">
                <i class="fas fa-th text-gray-300"></i>
                <span id="prev-grid">Tablero de <span id="prev-filas">{{ ceil($config->total_celdas / $config->columnas) }}</span> filas × {{ $config->columnas }} columnas</span>
            </div>
        </div>

        <div class="flex gap-3 pt-2">
            <a href="{{ route('sgspi.admin.resultados') }}"
               class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-600 font-black py-4 rounded-2xl transition text-xs uppercase tracking-widest">
                Cancelar
            </a>
            <button type="submit"
                class="flex-1 sena-bg text-white font-black py-4 rounded-2xl shadow-lg hover:scale-[1.02] transition-transform active:scale-95 uppercase tracking-widest text-xs">
                <i class="fas fa-save mr-2"></i> Guardar Configuración
            </button>
        </div>
    </form>

</div>
@endsection

@push('scripts')
<script>
    const inCeldas    = document.querySelector('[name="total_celdas"]');
    const inPreguntas = document.querySelector('[name="preguntas"]');
    const inColumnas  = document.querySelector('[name="columnas"]');

    function actualizarPreview() {
        const celdas    = parseInt(inCeldas.value) || 0;
        const preguntas = parseInt(inPreguntas.value) || 0;
        const columnas  = parseInt(inColumnas.value) || 5;
        const safe      = Math.max(0, celdas - preguntas);
        const filas     = columnas > 0 ? Math.ceil(celdas / columnas) : 0;

        document.getElementById('prev-celdas').textContent    = celdas;
        document.getElementById('prev-preguntas').textContent = preguntas;
        document.getElementById('prev-safe').textContent      = safe;
        document.getElementById('prev-puntaje').textContent   = preguntas * 10;
        document.getElementById('prev-filas').textContent     = filas;
        document.querySelector('#prev-grid').innerHTML        =
            `Tablero de <span id="prev-filas">${filas}</span> fila${filas !== 1 ? 's' : ''} × ${columnas} columnas`;

        // Alerta si preguntas > celdas
        const warn = document.getElementById('warn-preguntas');
        if (preguntas > celdas) {
            if (!warn) {
                const p = document.createElement('p');
                p.id = 'warn-preguntas';
                p.className = 'text-orange-500 text-[10px] font-bold mt-2';
                p.textContent = '⚠ Las preguntas no pueden superar el total de celdas.';
                inPreguntas.closest('div').appendChild(p);
            }
        } else if (warn) {
            warn.remove();
        }
    }

    [inCeldas, inPreguntas, inColumnas].forEach(el => el.addEventListener('input', actualizarPreview));
</script>
@endpush
