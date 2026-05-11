@extends('layouts.app')

@section('content')
@php
$controlesOpciones = ['Biométrico','Tarjeta de proximidad','Llave física','Registro manual','PIN/Clave','Guardia de seguridad'];
$sel = old('controles_acceso', $area->controles_acceso ?? []);
@endphp

<div class="max-w-3xl mx-auto">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-black text-gray-800 uppercase italic tracking-tighter">
                Editar <span class="text-[#39A900]">Área Segura</span>
            </h1>
            <p class="text-gray-500 text-sm font-bold italic font-mono">{{ $area->codigo }}</p>
        </div>
        <a href="{{ route('areas-seguras.show', $area) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-5 py-2 rounded-xl font-bold transition flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Cancelar
        </a>
    </div>

    @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-xl">
            <ul class="text-red-700 text-sm list-disc list-inside space-y-1">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('areas-seguras.update', $area) }}" method="POST" class="space-y-6">
        @csrf @method('PUT')

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h2 class="text-[10px] font-black text-[#39A900] uppercase tracking-widest mb-5 border-b pb-2">
                <i class="fas fa-id-card mr-2"></i> Identificación
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Código *</label>
                    <input type="text" name="codigo" value="{{ old('codigo', $area->codigo) }}" required
                           class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 font-mono uppercase text-sm outline-none focus:ring-2 focus:ring-[#39A900]"
                           oninput="this.value=this.value.toUpperCase()">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Nombre / Dependencia *</label>
                    <input type="text" name="nombre_dependencia" value="{{ old('nombre_dependencia', $area->nombre_dependencia) }}" required
                           class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-[#39A900]">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Sede</label>
                    <select name="sede_id" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-[#39A900]">
                        <option value="">— Sin sede específica —</option>
                        @foreach($sedes as $id => $nombre)
                            <option value="{{ $id }}" {{ old('sede_id', $area->sede_id) == $id ? 'selected' : '' }}>{{ $nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Tipo de Área</label>
                    <input type="text" name="tipo_area" value="{{ old('tipo_area', $area->tipo_area) }}"
                           class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-[#39A900]"
                           placeholder="Ej: Data Center, Oficina Administrativa">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Clasificación SENA *</label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3" id="grupo-nivel-sena">
                        @foreach($nivelesSena as $nivel => $cfg)
                        @php
                            $color        = match($nivel){ 'Nivel 1 - Crítico'=>'red','Nivel 2 - Sensible'=>'orange','Nivel 3 - Operativo'=>'blue',default=>'gray' };
                            $seleccionado = old('nivel_sena', $area->nivel_sena) === $nivel;
                        @endphp
                        <label data-nivel-card="{{ $nivel }}"
                               data-color="{{ $color }}"
                               class="nivel-card border-2 rounded-2xl p-4 cursor-pointer transition select-none
                                      {{ $seleccionado ? "border-{$color}-400 bg-{$color}-50" : 'border-gray-200 bg-white hover:border-gray-300' }}">
                            <input type="radio" name="nivel_sena" value="{{ $nivel }}"
                                   {{ $seleccionado ? 'checked' : '' }}
                                   class="sr-only" onchange="actualizarNivelSena(this)">
                            <div class="flex items-center gap-2 mb-1">
                                <i class="fas {{ $cfg['icono'] }} text-{{ $color }}-500 text-sm"></i>
                                <span class="font-black text-xs text-gray-800">{{ $nivel }}</span>
                            </div>
                            <p class="text-[9px] font-bold text-gray-400 uppercase">{{ $cfg['acceso'] }}</p>
                        </label>
                        @endforeach
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Criticidad CIA *</label>
                    <select name="nivel_criticidad" required class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-[#39A900]">
                        @foreach(['Alto'=>'Alta (C-I-A críticos)','Medio'=>'Media (algún CIA afectado)','Bajo'=>'Baja (impacto bajo)'] as $val => $lbl)
                            <option value="{{ $val }}" {{ old('nivel_criticidad', $area->nivel_criticidad) === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Responsable / Custodio *</label>
                    <input type="text" name="responsable_cargo" value="{{ old('responsable_cargo', $area->responsable_cargo) }}" required
                           class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-[#39A900]">
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h2 class="text-[10px] font-black text-[#39A900] uppercase tracking-widest mb-4 border-b pb-2">
                <i class="fas fa-map-marker-alt mr-2"></i> Ubicación Física
            </h2>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Bloque</label>
                    <input type="text" name="bloque" value="{{ old('bloque', $area->bloque) }}"
                           class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 uppercase text-sm outline-none focus:ring-2 focus:ring-[#39A900]"
                           oninput="normalizarMayusculas(this)">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Piso</label>
                    <input type="text" name="piso" value="{{ old('piso', $area->piso) }}"
                           class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-[#39A900]">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Oficina</label>
                    <input type="text" name="numero_oficina" value="{{ old('numero_oficina', $area->numero_oficina) }}"
                           class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 uppercase text-sm outline-none focus:ring-2 focus:ring-[#39A900]"
                           oninput="normalizarMayusculas(this)">
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h2 class="text-[10px] font-black text-[#39A900] uppercase tracking-widest mb-4 border-b pb-2">
                <i class="fas fa-lock mr-2"></i> Seguridad Física
            </h2>
            <div class="space-y-5">
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Perímetro *</label>
                    <input type="text" name="perimetro_seguridad" value="{{ old('perimetro_seguridad', $area->perimetro_seguridad) }}" required
                           class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-[#39A900]">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Controles de Acceso *</label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach($controlesOpciones as $ctrl)
                        <label class="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-xl p-3 cursor-pointer hover:border-[#39A900] transition {{ in_array($ctrl, $sel) ? 'border-[#39A900] bg-green-50' : '' }}">
                            <input type="checkbox" name="controles_acceso[]" value="{{ $ctrl }}"
                                   {{ in_array($ctrl, $sel) ? 'checked' : '' }}
                                   class="accent-[#39A900]">
                            <span class="text-xs font-bold text-gray-700">{{ $ctrl }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Horario *</label>
                    <select name="horario_acceso" required class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-[#39A900]">
                        @foreach(['Jornada laboral','24/7','Restringido'] as $h)
                            <option value="{{ $h }}" {{ old('horario_acceso', $area->horario_acceso) === $h ? 'selected' : '' }}>{{ $h }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Descripción</label>
                    <textarea name="descripcion" rows="3" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-[#39A900] resize-none">{{ old('descripcion', $area->descripcion) }}</textarea>
                </div>
            </div>
        </div>

        <div class="flex gap-3 justify-end">
            <a href="{{ route('areas-seguras.show', $area) }}" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-2xl font-black text-xs uppercase tracking-widest transition">Cancelar</a>
            <button type="submit" class="px-8 py-3 bg-[#39A900] hover:bg-green-700 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg transition">
                <i class="fas fa-save mr-2"></i> Guardar Cambios
            </button>
        </div>
    </form>
</div>
@endsection
