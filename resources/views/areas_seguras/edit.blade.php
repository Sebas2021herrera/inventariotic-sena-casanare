@extends('layouts.app')

@section('content')
@php
$controlesAccesoOpc   = \App\Models\AreaSegura::CONTROLES_ACCESO_OPCIONES;
$controlesMonitoreoOpc = \App\Models\AreaSegura::CONTROLES_MONITOREO_OPCIONES;
$clasificaciones       = \App\Models\AreaSegura::CLASIFICACIONES_INFO;
$zonas                 = \App\Models\AreaSegura::ZONAS;
$selAcceso             = old('controles_acceso',   $area->controles_acceso   ?? []);
$selMonitoreo          = old('controles_monitoreo', $area->controles_monitoreo ?? []);
@endphp

<div class="max-w-3xl mx-auto">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-black text-gray-800 uppercase italic tracking-tighter">
                Editar <span class="text-[#39A900]">Área Segura</span>
            </h1>
            <p class="text-gray-500 text-sm font-bold italic font-mono">{{ $area->codigo }} — GIL-F-101</p>
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

        {{-- ── Bloque 1: Identificación ───────────────────────────────────── --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h2 class="text-[10px] font-black text-[#39A900] uppercase tracking-widest mb-5 border-b pb-2">
                <i class="fas fa-id-card mr-2"></i> Identificación del Área (GIL-F-101)
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">ID de Zona *</label>
                    <input type="text" name="codigo" value="{{ old('codigo', $area->codigo) }}" required
                           class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 font-mono uppercase text-sm outline-none focus:ring-2 focus:ring-[#39A900]"
                           oninput="this.value=this.value.toUpperCase()">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Fecha de Inventario</label>
                    <input type="date" name="fecha_inventario"
                           value="{{ old('fecha_inventario', $area->fecha_inventario?->format('Y-m-d')) }}"
                           class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-[#39A900]">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Nombre del Área Segura *</label>
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
                           class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-[#39A900]">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Clasificación de la Información</label>
                    <select name="clasificacion_informacion"
                            class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-[#39A900]">
                        <option value="">— Seleccionar —</option>
                        @foreach($clasificaciones as $c)
                            <option value="{{ $c }}" {{ old('clasificacion_informacion', $area->clasificacion_informacion) === $c ? 'selected' : '' }}>{{ $c }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Clasificación Nivel GIL --}}
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Clasificación del Área Segura *</label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        @foreach($nivelesSena as $nivel => $cfg)
                        @php
                            $color = match($nivel){ 'Nivel 3'=>'red','Nivel 2'=>'orange','Nivel 1'=>'blue',default=>'gray' };
                            $sel   = old('nivel_sena', $area->nivel_sena) === $nivel;
                        @endphp
                        <label data-nivel-card="{{ $nivel }}" data-color="{{ $color }}"
                               class="nivel-card border-2 rounded-2xl p-4 cursor-pointer transition select-none
                                      {{ $sel ? "border-{$color}-400 bg-{$color}-50" : 'border-gray-200 bg-white hover:border-gray-300' }}">
                            <input type="radio" name="nivel_sena" value="{{ $nivel }}"
                                   {{ $sel ? 'checked' : '' }} class="sr-only">
                            <div class="flex items-center gap-2 mb-1">
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
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Zonificación</label>
                    <select name="zonificacion"
                            class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-[#39A900]">
                        <option value="">— Seleccionar zona —</option>
                        @foreach($zonas as $zona => $desc)
                            <option value="{{ $zona }}" {{ old('zonificacion', $area->zonificacion) === $zona ? 'selected' : '' }}>{{ $zona }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Criticidad CIA *</label>
                    <select name="nivel_criticidad" required
                            class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-[#39A900]">
                        @foreach(['Alto'=>'Alta (C-I-A críticos)','Medio'=>'Media','Bajo'=>'Baja'] as $val => $lbl)
                            <option value="{{ $val }}" {{ old('nivel_criticidad', $area->nivel_criticidad) === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- ── Bloque 2: Ubicación Física ────────────────────────────────── --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h2 class="text-[10px] font-black text-[#39A900] uppercase tracking-widest mb-4 border-b pb-2">
                <i class="fas fa-map-marker-alt mr-2"></i> Ubicación del Área Segura
            </h2>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Bloque / Edificio</label>
                    <input type="text" name="bloque" value="{{ old('bloque', $area->bloque) }}"
                           class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 uppercase text-sm outline-none focus:ring-2 focus:ring-[#39A900]"
                           oninput="this.value=this.value.toUpperCase()">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Piso</label>
                    <input type="text" name="piso" value="{{ old('piso', $area->piso) }}"
                           class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-[#39A900]">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Oficina / Sala</label>
                    <input type="text" name="numero_oficina" value="{{ old('numero_oficina', $area->numero_oficina) }}"
                           class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 uppercase text-sm outline-none focus:ring-2 focus:ring-[#39A900]"
                           oninput="this.value=this.value.toUpperCase()">
                </div>
            </div>
        </div>

        {{-- ── Bloque 3: Responsable del Área ────────────────────────────── --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h2 class="text-[10px] font-black text-[#39A900] uppercase tracking-widest mb-4 border-b pb-2">
                <i class="fas fa-user-shield mr-2"></i> Responsable del Área
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Nombre completo</label>
                    <input type="text" name="responsable_nombre" value="{{ old('responsable_nombre', $area->responsable_nombre) }}"
                           class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-[#39A900]">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Cargo / Rol *</label>
                    <input type="text" name="responsable_cargo" value="{{ old('responsable_cargo', $area->responsable_cargo) }}" required
                           class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-[#39A900]">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Contacto</label>
                    <input type="text" name="responsable_contacto" value="{{ old('responsable_contacto', $area->responsable_contacto) }}"
                           class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-[#39A900]">
                </div>
            </div>
        </div>

        {{-- ── Bloque 4: Controles de Acceso y Monitoreo ─────────────────── --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h2 class="text-[10px] font-black text-[#39A900] uppercase tracking-widest mb-4 border-b pb-2">
                <i class="fas fa-lock mr-2"></i> Controles de Acceso y Monitoreo
            </h2>
            <div class="space-y-5">
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Perímetro de Seguridad *</label>
                    <input type="text" name="perimetro_seguridad" value="{{ old('perimetro_seguridad', $area->perimetro_seguridad) }}" required
                           class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-[#39A900]">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">
                        Controles de Acceso * <span class="text-gray-400 font-normal text-[9px]">(GIL-G-027)</span>
                    </label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach($controlesAccesoOpc as $ctrl)
                        <label class="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-xl p-3 cursor-pointer hover:border-[#39A900] transition {{ in_array($ctrl, $selAcceso) ? 'border-[#39A900] bg-green-50' : '' }}">
                            <input type="checkbox" name="controles_acceso[]" value="{{ $ctrl }}"
                                   {{ in_array($ctrl, $selAcceso) ? 'checked' : '' }}
                                   class="accent-[#39A900]">
                            <span class="text-xs font-bold text-gray-700">{{ $ctrl }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">
                        Controles de Monitoreo
                    </label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach($controlesMonitoreoOpc as $ctrl)
                        <label class="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-xl p-3 cursor-pointer hover:border-blue-400 transition {{ in_array($ctrl, $selMonitoreo) ? 'border-blue-400 bg-blue-50' : '' }}">
                            <input type="checkbox" name="controles_monitoreo[]" value="{{ $ctrl }}"
                                   {{ in_array($ctrl, $selMonitoreo) ? 'checked' : '' }}
                                   class="accent-blue-600">
                            <span class="text-xs font-bold text-gray-700">{{ $ctrl }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Horario *</label>
                        <select name="horario_acceso" required
                                class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-[#39A900]">
                            @foreach(['Jornada laboral','24/7','Restringido'] as $h)
                                <option value="{{ $h }}" {{ old('horario_acceso', $area->horario_acceso) === $h ? 'selected' : '' }}>{{ $h }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Estado del Área</label>
                        <input type="text" name="estado_area" value="{{ old('estado_area', $area->estado_area) }}"
                               class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-[#39A900]"
                               placeholder="Operativo, En mantenimiento...">
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Observaciones</label>
                    <textarea name="descripcion" rows="2" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-[#39A900] resize-none">{{ old('descripcion', $area->descripcion) }}</textarea>
                </div>
            </div>
        </div>

        {{-- ── GIL-F-101: Nota sobre modificación ────────────────────────── --}}
        <div class="bg-yellow-50 border border-yellow-200 p-5 rounded-2xl">
            <h2 class="text-[10px] font-black text-yellow-700 uppercase tracking-widest mb-3 flex items-center gap-2">
                <i class="fas fa-history"></i> En caso de actualización/modificación (GIL-F-101)
            </h2>
            <textarea name="nota_cambio" rows="2"
                      class="w-full bg-white border-yellow-300 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-yellow-400 resize-none"
                      placeholder="Describa brevemente los cambios realizados en esta actualización..."></textarea>
            <p class="text-[9px] text-yellow-600 mt-1 font-bold">Este registro queda en el histórico del área y no se elimina.</p>
            @if($area->historico_cambios && count($area->historico_cambios))
            <div class="mt-3 space-y-1">
                @foreach(array_reverse($area->historico_cambios) as $h)
                <div class="flex items-start gap-2 text-[10px] text-yellow-700">
                    <i class="fas fa-clock mt-0.5 flex-shrink-0 text-[8px]"></i>
                    <span><strong>{{ $h['fecha'] }}</strong> — {{ $h['cambio'] }} <span class="text-yellow-500">({{ $h['usuario'] }})</span></span>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        <div class="flex gap-3 justify-end">
            <a href="{{ route('areas-seguras.show', $area) }}"
               class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-2xl font-black text-xs uppercase tracking-widest transition">
                Cancelar
            </a>
            <button type="submit"
                    class="px-8 py-3 bg-[#39A900] hover:bg-green-700 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg transition">
                <i class="fas fa-save mr-2"></i> Guardar Cambios
            </button>
        </div>
    </form>
</div>

<script>
document.querySelectorAll('.nivel-card').forEach(card => {
    card.addEventListener('click', function () {
        const radio = this.querySelector('input[type=radio]');
        radio.checked = true;
        const color  = this.dataset.color;
        document.querySelectorAll('.nivel-card').forEach(c => {
            const cc = c.dataset.color;
            c.classList.remove(`border-${cc}-400`, `bg-${cc}-50`);
            c.classList.add('border-gray-200');
        });
        this.classList.remove('border-gray-200');
        this.classList.add(`border-${color}-400`, `bg-${color}-50`);
    });
});
</script>
@endsection
