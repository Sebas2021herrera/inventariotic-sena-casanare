@extends('layouts.app')

@section('content')
@php
$controlesOpciones = ['Biométrico','Tarjeta de proximidad','Llave física','Registro manual','PIN/Clave','Guardia de seguridad'];
@endphp

<div class="max-w-3xl mx-auto">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-black text-gray-800 uppercase italic tracking-tighter">
                Nueva <span class="text-[#39A900]">Área Segura</span>
            </h1>
            <p class="text-gray-500 text-sm font-bold italic">ISO 27001:2022 · Control 7.5 / 7.6</p>
        </div>
        <a href="{{ route('areas-seguras.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-5 py-2 rounded-xl font-bold transition flex items-center gap-2">
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

    <form action="{{ route('areas-seguras.store') }}" method="POST" class="space-y-6">
        @csrf

        {{-- Identificación --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h2 class="text-[10px] font-black text-[#39A900] uppercase tracking-widest mb-5 border-b pb-2">
                <i class="fas fa-id-card mr-2"></i> Identificación del Área
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Código del Área *</label>
                    <input type="text" name="codigo" value="{{ old('codigo') }}" required
                           class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 font-mono uppercase text-sm outline-none focus:ring-2 focus:ring-[#39A900]"
                           placeholder="Ej: YOP-S01-DC" oninput="this.value=this.value.toUpperCase()">
                    <p class="text-[9px] text-gray-400 mt-1">Nomenclatura interna · único por área</p>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Nombre / Dependencia *</label>
                    <input type="text" name="nombre_dependencia" value="{{ old('nombre_dependencia') }}" required
                           class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-[#39A900]"
                           placeholder="Ej: Data Center, Archivo Central">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Sede *</label>
                    <select name="sede_id" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-[#39A900]">
                        <option value="">— Sin sede específica —</option>
                        @foreach($sedes as $id => $nombre)
                            <option value="{{ $id }}" {{ old('sede_id') == $id ? 'selected' : '' }}>{{ $nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Tipo de Área</label>
                    <input type="text" name="tipo_area" value="{{ old('tipo_area') }}"
                           class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-[#39A900]"
                           placeholder="Ej: Data Center, Oficina Administrativa">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Clasificación SENA *</label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3" id="grupo-nivel-sena">
                        @foreach($nivelesSena as $nivel => $cfg)
                        @php
                            $color     = match($nivel){ 'Nivel 1 - Crítico'=>'red','Nivel 2 - Sensible'=>'orange','Nivel 3 - Operativo'=>'blue',default=>'gray' };
                            $seleccionado = old('nivel_sena') === $nivel;
                        @endphp
                        <label data-nivel-card="{{ $nivel }}"
                               data-color="{{ $color }}"
                               class="nivel-card border-2 rounded-2xl p-4 cursor-pointer transition select-none
                                      {{ $seleccionado ? "border-{$color}-400 bg-{$color}-50" : 'border-gray-200 bg-white hover:border-gray-300' }}">
                            <input type="radio" name="nivel_sena" value="{{ $nivel }}"
                                   {{ $seleccionado ? 'checked' : '' }}
                                   class="sr-only" onchange="actualizarNivelSena(this)">
                            <div class="flex items-center gap-2 mb-2">
                                <i class="fas {{ $cfg['icono'] }} text-{{ $color }}-500 text-sm"></i>
                                <span class="font-black text-xs text-gray-800">{{ $nivel }}</span>
                            </div>
                            <p class="text-[9px] font-bold text-gray-400 uppercase mb-1">{{ $cfg['acceso'] }}</p>
                            <ul class="space-y-0.5">
                                @foreach($cfg['ejemplos'] as $ej)
                                <li class="text-[9px] text-gray-500">· {{ $ej }}</li>
                                @endforeach
                            </ul>
                        </label>
                        @endforeach
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Criticidad CIA *</label>
                    <select name="nivel_criticidad" required
                            class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-[#39A900]">
                        <option value="">— Selecciona —</option>
                        @foreach(['Alto'=>'Alta (C-I-A todos críticos)','Medio'=>'Media (algún criterio CIA afectado)','Bajo'=>'Baja (impacto bajo en CIA)'] as $val => $lbl)
                            <option value="{{ $val }}" {{ old('nivel_criticidad') === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Responsable / Custodio *</label>
                    <input type="text" name="responsable_cargo" value="{{ old('responsable_cargo') }}" required
                           class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-[#39A900]"
                           placeholder="Ej: Coordinador TI, Jefe de Archivo">
                </div>
            </div>
        </div>

        {{-- Ubicación Física --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h2 class="text-[10px] font-black text-[#39A900] uppercase tracking-widest mb-5 border-b pb-2">
                <i class="fas fa-map-marker-alt mr-2"></i> Ubicación Física
            </h2>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Bloque</label>
                    <input type="text" name="bloque" value="{{ old('bloque') }}"
                           class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 uppercase text-sm outline-none focus:ring-2 focus:ring-[#39A900]"
                           placeholder="A, B, ADMIN" oninput="normalizarMayusculas(this)">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Piso</label>
                    <input type="text" name="piso" value="{{ old('piso') }}"
                           class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-[#39A900]"
                           placeholder="1, 2, PB">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Oficina / Sala</label>
                    <input type="text" name="numero_oficina" value="{{ old('numero_oficina') }}"
                           class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 uppercase text-sm outline-none focus:ring-2 focus:ring-[#39A900]"
                           placeholder="101, DC-01" oninput="normalizarMayusculas(this)">
                </div>
            </div>
        </div>

        {{-- Seguridad Física --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h2 class="text-[10px] font-black text-[#39A900] uppercase tracking-widest mb-5 border-b pb-2">
                <i class="fas fa-lock mr-2"></i> Seguridad Física
            </h2>
            <div class="space-y-5">
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Perímetro de Seguridad *</label>
                    <input type="text" name="perimetro_seguridad" value="{{ old('perimetro_seguridad') }}" required
                           class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-[#39A900]"
                           placeholder="Ej: Muros de concreto, Drywall con vidrio templado, Malla metálica">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Controles de Acceso Existentes * <span class="text-gray-400 font-normal">(selecciona todos los que apliquen)</span></label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach($controlesOpciones as $ctrl)
                        <label class="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-xl p-3 cursor-pointer hover:border-[#39A900] transition {{ in_array($ctrl, old('controles_acceso',[])) ? 'border-[#39A900] bg-green-50' : '' }}">
                            <input type="checkbox" name="controles_acceso[]" value="{{ $ctrl }}"
                                   {{ in_array($ctrl, old('controles_acceso',[])) ? 'checked' : '' }}
                                   class="accent-[#39A900]">
                            <span class="text-xs font-bold text-gray-700">{{ $ctrl }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Horario de Acceso Permitido *</label>
                    <select name="horario_acceso" required
                            class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-[#39A900]">
                        @foreach(['Jornada laboral','24/7','Restringido'] as $h)
                            <option value="{{ $h }}" {{ old('horario_acceso') === $h ? 'selected' : '' }}>{{ $h }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Descripción / Observaciones adicionales</label>
                    <textarea name="descripcion" rows="3"
                              class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-[#39A900] resize-none"
                              placeholder="Información adicional relevante sobre el área...">{{ old('descripcion') }}</textarea>
                </div>
            </div>
        </div>

        <div class="flex gap-3 justify-end">
            <a href="{{ route('areas-seguras.index') }}"
               class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-2xl font-black text-xs uppercase tracking-widest transition">
                Cancelar
            </a>
            <button type="submit"
                    class="px-8 py-3 bg-[#39A900] hover:bg-green-700 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg transition">
                <i class="fas fa-shield-alt mr-2"></i> Registrar Área Segura
            </button>
        </div>
    </form>
</div>
@endsection
