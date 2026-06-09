@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto pb-12">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-black text-gray-800 uppercase italic tracking-tighter">
                Editar Registro <span class="text-[#39A900]">SENA</span>
            </h1>
            <p class="text-gray-500 text-sm font-medium italic">Editando Placa: {{ $dispositivo->placa }}</p>
        </div>
        <a href="{{ route('dispositivos.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-xl font-bold transition flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Cancelar y Volver
        </a>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border-l-8 border-red-500 p-4 mb-8 rounded-r-xl shadow-md">
            <div class="flex items-center mb-2">
                <i class="fas fa-exclamation-circle text-red-500 mr-2 text-xl"></i>
                <h3 class="text-red-800 font-black uppercase text-sm">Errores por corregir</h3>
            </div>
            <ul class="text-red-700 text-sm list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('dispositivos.update', $dispositivo) }}" method="POST" id="form-inventario">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-1 space-y-6">
                

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <div class="flex justify-between items-center mb-6 border-b pb-2">
                        <div class="text-[#39A900] font-black uppercase text-xs tracking-widest">
                            <i class="fas fa-user-tie mr-2"></i> Datos del Responsable
                        </div>
                        <div class="flex gap-2">
                            <button type="button" onclick="habilitarEdicionActual()" id="btn-editar-actual"
                                    class="text-[10px] font-bold bg-blue-100 text-blue-600 px-2 py-1 rounded-lg hover:bg-blue-200 transition">
                                <i class="fas fa-user-edit mr-1"></i> ACTUALIZAR
                            </button>

                            <button type="button" onclick="habilitarCambioResponsable()" id="btn-cambiar"
                                    class="text-[10px] font-bold bg-orange-100 text-orange-600 px-2 py-1 rounded-lg hover:bg-orange-200 transition">
                                <i class="fas fa-exchange-alt mr-1"></i> CAMBIAR
                            </button>

                            <button type="button" onclick="abrirModalResponsable()" id="btn-buscar-nombre"
                                    class="text-[10px] font-bold bg-green-100 text-[#39A900] px-2 py-1 rounded-lg hover:bg-green-200 transition">
                                <i class="fas fa-users mr-1"></i> BUSCAR
                            </button>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Cédula / ID *</label>
                            <div class="flex gap-2">
                                <input type="text" id="cedula" name="cedula" value="{{ old('cedula', $dispositivo->responsable->cedula) }}" 
                                    class="flex-1 bg-gray-100 border-gray-200 rounded-xl p-3 font-bold text-gray-500 shadow-inner cursor-not-allowed" 
                                    readonly required>
                                
                                <button type="button" id="btn-buscar" onclick="buscarResponsable()" 
                                        class="hidden bg-blue-600 text-white px-4 rounded-xl hover:bg-blue-700 transition shadow-sm items-center justify-center w-12">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                            <p id="msj-responsable" class="text-[10px] font-bold mt-2 hidden italic uppercase tracking-tighter"></p>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Nombre Completo *</label>
                            <input type="text" id="nombre_responsable" name="nombre_responsable" 
                                value="{{ old('nombre_responsable', $dispositivo->responsable->nombre) }}" 
                                class="w-full bg-gray-100 border-gray-200 rounded-xl p-3 text-gray-500 cursor-not-allowed" readonly required>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Celular</label>
                                <input type="text" id="numero_de_celular" name="numero_de_celular" 
                                    value="{{ old('numero_de_celular', $dispositivo->responsable->numero_de_celular) }}" 
                                    class="w-full bg-gray-100 border-gray-200 rounded-xl p-3 text-gray-500 cursor-not-allowed" readonly>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Tipo</label>
                                <input type="text" id="tipo_funcionario_display" value="{{ $dispositivo->responsable->tipo_funcionario }}" 
                                    class="w-full bg-gray-100 border-gray-200 rounded-xl p-3 text-gray-500 cursor-not-allowed" readonly>
                                <select id="tipo_funcionario" name="tipo_funcionario" class="hidden w-full bg-gray-50 border-gray-200 rounded-xl p-3">
                                    <option value="Contratista" {{ $dispositivo->responsable->tipo_funcionario == 'Contratista' ? 'selected' : '' }}>Contratista</option>
                                    <option value="Planta" {{ $dispositivo->responsable->tipo_funcionario == 'Planta' ? 'selected' : '' }}>Planta</option>
                                    <option value="Aprendiz" {{ $dispositivo->responsable->tipo_funcionario == 'Aprendiz' ? 'selected' : '' }}>Aprendiz</option>
                                </select>
                            </div>
                        </div>
                        <input type="text" id="dependencia" name="dependencia" value="{{ old('dependencia', $dispositivo->responsable->dependencia) }}" 
                            class="w-full bg-gray-100 border-gray-200 rounded-xl p-3 text-gray-500 cursor-not-allowed" readonly placeholder="Dependencia">
                        <input type="text" id="cargo" name="cargo" value="{{ old('cargo', $dispositivo->responsable->cargo) }}" 
                            class="w-full bg-gray-100 border-gray-200 rounded-xl p-3 text-gray-500 cursor-not-allowed" readonly placeholder="Cargo">
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <div class="flex items-center mb-6 text-[#39A900] font-black uppercase text-xs tracking-widest border-b pb-2">
                        <i class="fas fa-map-marker-alt mr-2"></i> Ubicación Física
                    </div>
                    @php $sedeActual = old('sede', $dispositivo->ubicacion->sede->nombre ?? ''); @endphp
                    <div class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Sede *</label>
                            <select name="sede" id="select-sede" required
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 font-bold text-gray-700 outline-none focus:ring-2 focus:ring-[#39A900] transition appearance-none">
                                <option value="">— Selecciona la sede —</option>
                                @foreach($sedes as $s)
                                    <option value="{{ $s }}" {{ $sedeActual === $s ? 'selected' : '' }}>{{ $s }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Bloque</label>
                                <input type="text" name="bloque"
                                       value="{{ old('bloque', $dispositivo->ubicacion->bloque) }}"
                                       placeholder="Ej: A, B, CUARTO"
                                       oninput="normalizarMayusculas(this)"
                                       style="text-transform:uppercase;"
                                       class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 font-mono text-sm outline-none focus:ring-2 focus:ring-[#39A900] transition">
                                <p class="text-[9px] text-gray-400 mt-0.5 ml-1">Se guarda en MAYÚSCULAS</p>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Ambiente *</label>
                                <input type="text" name="ambiente"
                                       value="{{ old('ambiente', $dispositivo->ubicacion->ambiente) }}"
                                       placeholder="Ej: 101, ADMIN" required
                                       oninput="normalizarMayusculas(this)"
                                       style="text-transform:uppercase;"
                                       class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 font-mono text-sm outline-none focus:ring-2 focus:ring-[#39A900] transition">
                                <p class="text-[9px] text-gray-400 mt-0.5 ml-1">Se guarda en MAYÚSCULAS</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 space-y-6">
                
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <div class="flex items-center mb-6 text-[#39A900] font-black uppercase text-xs tracking-widest border-b pb-2">
                        <i class="fas fa-desktop mr-2"></i> Identificación del Bien
                    </div>
                    
                    @php
                        $ef   = old('estado_fisico', $dispositivo->estado_fisico);
                        $el   = old('estado_logico',  $dispositivo->estado_logico ?? 'Bueno');
                        $prop = old('propietario', $dispositivo->propietario);
                        $func = old('funcion', $dispositivo->funcion);
                        $int  = old('en_intune', $dispositivo->en_intune);
                    @endphp
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        {{-- Fila 1: Placa + Serial --}}
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">
                                Placa SENA
                                @if(auth()->user()->role === 'admin')
                                    <i class="fas fa-pencil-alt ml-1 text-[#39A900]"></i>
                                @else
                                    <i class="fas fa-lock ml-1 text-gray-300"></i>
                                @endif
                            </label>
                            @if(auth()->user()->role === 'admin')
                                <input type="text" name="placa" value="{{ old('placa', $dispositivo->placa) }}"
                                    class="w-full bg-white border-[#39A900] border-2 rounded-xl p-3 font-black text-xl text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#39A900]">
                                <p class="text-[9px] text-[#39A900] mt-1 italic">* Solo administradores pueden modificar la placa.</p>
                            @else
                                <input type="text" value="{{ $dispositivo->placa }}"
                                    class="w-full bg-gray-100 border-gray-300 border-2 rounded-xl p-3 font-black text-xl text-gray-500 shadow-inner cursor-not-allowed" readonly>
                                <p class="text-[9px] text-gray-400 mt-1 italic">* La placa no se puede modificar una vez registrada.</p>
                            @endif
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Serial de Fábrica *</label>
                            <input type="text" name="serial" value="{{ old('serial', $dispositivo->serial) }}"
                                class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 font-mono uppercase" required>
                        </div>

                        {{-- Fila 2: Categoría + Tipo Equipo --}}
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Categoría *</label>
                            <select name="categoria" id="categoria-select" onchange="toggleSecciones()"
                                    class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 font-bold text-[#39A900] outline-none focus:bg-white transition">
                                @foreach(['computo'=>'Computadores','conectividad'=>'Redes / Conectividad','impresoras'=>'Impresoras / Escáner'] as $val => $lbl)
                                    <option value="{{ $val }}" {{ old('categoria',$dispositivo->categoria) === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if($dispositivo->categoria === 'computo')
                        <div id="div-tipo-equipo">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Tipo de Equipo</label>
                            <select name="tipo_equipo" id="tipo-equipo"
                                    class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 font-bold outline-none focus:bg-white transition">
                                <option value="">— Selecciona —</option>
                                @foreach(['Portátil','Escritorio','Workstation'] as $te)
                                    <option value="{{ $te }}" {{ old('tipo_equipo',$dispositivo->tipo_equipo) === $te ? 'selected' : '' }}>{{ $te }}</option>
                                @endforeach
                            </select>
                        </div>
                        @else
                        <div></div>
                        <input type="hidden" name="tipo_equipo" value="{{ old('tipo_equipo',$dispositivo->tipo_equipo) }}">
                        @endif

                        {{-- Fila 3: Propietario + Función + Intune --}}
                        <div class="md:col-span-2 grid grid-cols-3 gap-4 bg-gray-50 p-4 rounded-xl border border-gray-100">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Propietario</label>
                                <select name="propietario" class="w-full bg-white border-gray-200 rounded-lg p-2 text-sm font-bold">
                                    @foreach(['SENA','TELEFONICA'=>'TELEFÓNICA','OTRO'] as $k => $v)
                                    @php $val = is_int($k) ? $v : $k; $lbl = is_int($k) ? $v : $v; @endphp
                                    <option value="{{ $val }}" {{ $prop === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Función</label>
                                <select name="funcion" class="w-full bg-white border-gray-200 rounded-lg p-2 text-sm font-bold">
                                    <option value="FORMACION"    {{ $func === 'FORMACION'    ? 'selected' : '' }}>APRENDIZ / FORMACIÓN</option>
                                    <option value="ADMINISTRATIVO" {{ $func === 'ADMINISTRATIVO' ? 'selected' : '' }}>ADMINISTRATIVO</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">¿INTUNE?</label>
                                <select name="en_intune" class="w-full bg-white border-gray-200 rounded-lg p-2 text-sm font-bold">
                                    <option value="NO" {{ $int === 'NO' ? 'selected' : '' }}>NO</option>
                                    <option value="SI" {{ $int === 'SI' ? 'selected' : '' }}>SI</option>
                                </select>
                            </div>
                        </div>

                        {{-- Fila 4: Marca + Modelo --}}
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Marca</label>
                            <input type="text" name="marca" value="{{ old('marca',$dispositivo->marca) }}" placeholder="Marca" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Modelo</label>
                            <input type="text" name="modelo" value="{{ old('modelo',$dispositivo->modelo) }}" placeholder="Modelo" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3">
                        </div>

                        {{-- Fila 5: Estado Físico + Estado Lógico --}}
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Estado Físico</label>
                            <select name="estado_fisico" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 font-bold outline-none focus:bg-white transition">
                                @foreach(['Bueno','Regular','Malo','En Reparación'] as $est)
                                    <option value="{{ $est }}" {{ $ef === $est ? 'selected' : '' }}>{{ $est }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Estado Lógico</label>
                            <select name="estado_logico" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 font-bold outline-none focus:bg-white transition">
                                @foreach(['Bueno','Regular','Malo'] as $est)
                                    <option value="{{ $est }}" {{ $el === $est ? 'selected' : '' }}>{{ $est }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Fila 6: Hostname (al final) --}}
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Hostname / Nombre del Equipo</label>
                            <div class="flex gap-2">
                                <input type="text" name="hostname" id="input-hostname"
                                    value="{{ old('hostname',$dispositivo->hostname) }}"
                                    class="flex-1 bg-gray-50 border-gray-200 rounded-xl p-3 font-mono uppercase outline-none focus:bg-white transition"
                                    placeholder="Ej: YOPADRCNCSD001"
                                    pattern="[a-zA-Z0-9\-_\.]+"
                                    oninput="this.value=this.value.toUpperCase()"
                                    title="Solo letras, números, guiones y puntos.">
                                @if($dispositivo->categoria === 'computo')
                                <button type="button" onclick="generarHostnameAuto()"
                                        class="px-4 py-3 bg-[#39A900] text-white rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-green-700 transition whitespace-nowrap flex-shrink-0">
                                    <i class="fas fa-magic mr-1"></i> Generar
                                </button>
                                @endif
                            </div>
                            <p class="text-[10px] text-gray-400 mt-1">Sin espacios ni caracteres especiales</p>
                        </div>

                    </div>
                </div>

                <div id="seccion-computo" class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <div class="flex items-center mb-6 text-[#39A900] font-black uppercase text-xs tracking-widest border-b pb-2">
                        <i class="fas fa-server mr-2"></i> Especificaciones de Cómputo
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @php
                            $especs   = $dispositivo->especificaciones;
                            $ramNum   = intval($especs->ram ?? 0) ?: '';
                            $capNum   = intval($especs->capacidad_disco ?? 0) ?: '';
                            $tipoActual = old('tipo_disco', $especs->tipo_disco ?? '');
                        @endphp

                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Procesador</label>
                            <input type="text" name="procesador" value="{{ old('procesador', $especs->procesador ?? '') }}" placeholder="Ej: Core i7-1165G7" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm">
                        </div>

                        {{-- RAM: solo número, badge GB --}}
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">
                                Memoria RAM <span class="text-gray-300 font-normal normal-case">(en GB)</span>
                            </label>
                            <div class="flex items-stretch bg-gray-50 border border-gray-200 rounded-xl overflow-hidden">
                                <input type="number" name="ram" value="{{ old('ram', $ramNum) }}" min="1" max="512" step="1"
                                       class="flex-1 bg-transparent px-3 py-3 text-sm outline-none"
                                       placeholder="Ej: 16">
                                <span class="px-3 flex items-center text-[10px] font-black text-gray-400 bg-gray-100 border-l border-gray-200">GB</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">S.O.</label>
                            <input type="text" name="so" value="{{ old('so', $especs->so ?? '') }}" placeholder="Ej: Windows 11 Pro" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm">
                        </div>

                        {{-- Tipo Disco: lista predefinida --}}
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Tipo de Disco</label>
                            <select name="tipo_disco" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm font-bold text-gray-700 outline-none focus:ring-2 focus:ring-[#39A900]">
                                <option value="">— Seleccionar —</option>
                                <option value="SSD — Sólido SATA"  {{ $tipoActual == 'SSD — Sólido SATA'  ? 'selected' : '' }}>SSD — Sólido SATA</option>
                                <option value="SSD M.2 — NVMe"     {{ $tipoActual == 'SSD M.2 — NVMe'     ? 'selected' : '' }}>SSD M.2 — NVMe</option>
                                <option value="HDD — Mecánico"     {{ $tipoActual == 'HDD — Mecánico'     ? 'selected' : '' }}>HDD — Mecánico</option>
                                <option value="SSHD — Híbrido"     {{ $tipoActual == 'SSHD — Híbrido'     ? 'selected' : '' }}>SSHD — Híbrido</option>
                                <option value="N/A"                {{ $tipoActual == 'N/A'                ? 'selected' : '' }}>N/A</option>
                            </select>
                        </div>

                        {{-- Capacidad: solo número, badge GB --}}
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">
                                Capacidad <span class="text-gray-300 font-normal normal-case">(en GB)</span>
                            </label>
                            <div class="flex items-stretch bg-gray-50 border border-gray-200 rounded-xl overflow-hidden">
                                <input type="number" name="capacidad_disco" value="{{ old('capacidad_disco', $capNum) }}" min="1" step="1"
                                       class="flex-1 bg-transparent px-3 py-3 text-sm outline-none"
                                       placeholder="Ej: 512">
                                <span class="px-3 flex items-center text-[10px] font-black text-gray-400 bg-gray-100 border-l border-gray-200">GB</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">MAC Address</label>
                            <input type="text" name="mac_address" value="{{ old('mac_address', $especs->mac_address ?? '') }}" placeholder="Ej: AA:BB:CC:DD:EE:FF" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 font-mono text-xs">
                        </div>
                    </div>
                </div>

                <div id="seccion-redes" class="hidden bg-blue-50 p-6 rounded-2xl border border-blue-100 shadow-inner">
                    <div class="flex items-center mb-6 text-blue-700 font-black uppercase text-xs tracking-widest border-b border-blue-200 pb-2">
                        <i class="fas fa-network-wired mr-2"></i> Detalles de Red
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <textarea name="descripcion_tecnica" rows="2" class="md:col-span-2 border-blue-100 rounded-xl p-3 text-xs" placeholder="Descripción">{{ old('descripcion_tecnica', $dispositivo->descripcion_tecnica) }}</textarea>
                        <input type="number" name="puertos" value="{{ old('puertos', $dispositivo->puertos) }}" placeholder="Puertos" class="border-blue-100 rounded-xl p-3">
                        <input type="text" name="mac_red" value="{{ old('mac_red', $dispositivo->mac_address) }}" placeholder="MAC Red" class="border-blue-100 rounded-xl p-3 font-mono">
                        <input type="text" name="ap_conectado_a" value="{{ old('ap_conectado_a', $dispositivo->ap_conectado_a) }}" placeholder="Conectado a SW" class="border-blue-100 rounded-xl p-3">
                        <input type="text" name="puerto_origen" value="{{ old('puerto_origen', $dispositivo->puerto_origen) }}" placeholder="Puerto SW" class="border-blue-100 rounded-xl p-3">
                    </div>
                </div>

                <div id="seccion-perifericos" class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <div class="flex items-center mb-6 text-[#39A900] font-black uppercase text-xs tracking-widest border-b pb-2">
                        <i class="fas fa-plug mr-2"></i> Periféricos / SFPs
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @php
                            $tipos = ($dispositivo->categoria == 'conectividad') 
                                ? ['SFP Slot 1', 'SFP Slot 2', 'SFP Slot 3', 'SFP Slot 4']
                                : ['Monitor', 'Teclado', 'Mouse', 'Cargador'];
                        @endphp

                        @foreach($tipos as $tipo)
                        @php
                            $existente = $dispositivo->perifericos->where('tipo', $tipo)->first();
                        @endphp
                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                            <h4 class="text-[10px] font-black text-gray-400 uppercase mb-2">{{ $tipo }}</h4>
                            <div class="grid grid-cols-2 gap-2">
                                <input type="text" name="perifericos[{{ $tipo }}][placa]"  value="{{ old("perifericos.$tipo.placa",  $existente->placa  ?? '') }}" placeholder="Placa SENA" class="text-[10px] p-2 rounded-lg border-gray-200 bg-white">
                                <input type="text" name="perifericos[{{ $tipo }}][serial]" value="{{ old("perifericos.$tipo.serial", $existente->serial ?? '') }}" placeholder="Serial"      class="text-[10px] p-2 rounded-lg border-gray-200 bg-white">
                                <input type="text" name="perifericos[{{ $tipo }}][marca]"  value="{{ old("perifericos.$tipo.marca",  $existente->marca  ?? '') }}" placeholder="Marca"       class="text-[10px] p-2 rounded-lg border-gray-200 bg-white">
                                <input type="text" name="perifericos[{{ $tipo }}][modelo]" value="{{ old("perifericos.$tipo.modelo", $existente->modelo ?? '') }}" placeholder="Modelo"      class="text-[10px] p-2 rounded-lg border-gray-200 bg-white">
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Observaciones / Novedades</label>
                    <textarea name="observaciones" rows="3" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm">{{ old('observaciones', $dispositivo->observaciones) }}</textarea>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit" class="bg-blue-600 text-white px-16 py-5 rounded-2xl font-black uppercase tracking-widest shadow-2xl hover:scale-105 transition-all flex items-center">
                        <i class="fas fa-sync-alt mr-3 text-xl"></i> Guardar Cambios
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
    /**
     * Alterna la visibilidad entre Cómputo y Redes
     * Se usa encadenamiento opcional (?.) para evitar errores si un ID no existe
     */
    function toggleSecciones() {
        const cat = document.getElementById('categoria-select')?.value;
        const sComputo = document.getElementById('seccion-computo');
        const sRedes = document.getElementById('seccion-redes');

        if (cat === 'conectividad') {
            if (sRedes) sRedes.classList.remove('hidden');
            if (sComputo) sComputo.classList.add('hidden');
        } else {
            if (sRedes) sRedes.classList.add('hidden');
            if (sComputo) sComputo.classList.remove('hidden');
        }
    }

    // Aseguramos que se ejecute al cargar para mostrar la sección correcta del equipo
    window.onload = toggleSecciones;

    /**
     * Busca el responsable por cédula (Ruta dinámica para clonación)
     */
    function buscarResponsable() {
        const cedulaInput = document.getElementById('cedula');
        const msj = document.getElementById('msj-responsable');
        
        if (!cedulaInput || !msj) return;

        const cedula = cedulaInput.value.trim();
        
        if (!cedula) return;

        // Mostrar estado de carga
        msj.classList.remove('hidden');
        msj.innerText = 'Buscando en base de datos...';
        msj.className = "text-[10px] font-bold mt-2 text-blue-500 block italic";

        // URL DINÁMICA: Se adapta automáticamente al entorno (Local o Servidor)
        fetch("{{ url('/responsables/buscar') }}/" + cedula)
            .then(res => res.json())
            .then(data => {
                // Sincronizado con la lógica de 'create'
                const resp = data.responsable || (data.id ? data : null);

                if (resp && resp.id) {
                    // ÉXITO: Actualizamos los campos con la información encontrada
                    document.getElementById('nombre_responsable').value = resp.nombre || '';
                    document.getElementById('numero_de_celular').value = resp.numero_de_celular || '';
                    document.getElementById('tipo_funcionario').value = resp.tipo_funcionario || 'Contratista';
                    document.getElementById('dependencia').value = resp.dependencia || '';
                    document.getElementById('cargo').value = resp.cargo || '';

                    msj.innerText = "✓ Responsable encontrado y actualizado";
                    msj.className = "text-[10px] font-bold mt-2 text-green-600 block italic uppercase";
                } else {
                    // AVISO: No se encontró, pero permitimos que el técnico edite manualmente
                    msj.innerText = "✗ El número de identificación no existe en el sistema";
                    msj.className = "text-[10px] font-bold mt-2 text-red-500 block italic uppercase";
                }
            })
            .catch(error => {
                msj.innerText = "⚠ Error de conexión con el servidor de GITIC";
                msj.className = "text-[10px] font-bold mt-2 text-orange-500 block italic";
                console.error("Fallo en fetch de edición:", error);
            });
    }

        /**
     * OPCIÓN A: Actualiza datos del responsable actual.
     * Mantiene la Cédula BLOQUEADA.
     */
    function habilitarEdicionActual() {
        // Definimos qué campos se desbloquean (Cédula NO se incluye)
        const campos = ['nombre_responsable', 'numero_de_celular', 'dependencia', 'cargo'];
        
        campos.forEach(id => {
            const el = document.getElementById(id);
            el.readOnly = false;
            el.classList.remove('bg-gray-100', 'text-gray-500', 'cursor-not-allowed');
            el.classList.add('bg-white', 'border-blue-300', 'ring-1', 'ring-blue-100');
        });

        // Cambiar visualización del tipo de funcionario
        document.getElementById('tipo_funcionario_display').classList.add('hidden');
        document.getElementById('tipo_funcionario').classList.remove('hidden');

        // Feedback visual
        const msj = document.getElementById('msj-responsable');
        msj.classList.remove('hidden');
        msj.innerText = "✎ Modo edición: Corrigiendo datos del responsable actual";
        msj.className = "text-[10px] font-bold mt-2 text-blue-600 block italic uppercase";

        // Ocultar botones para evitar conflictos
        document.getElementById('btn-cambiar').classList.add('hidden');
        document.getElementById('btn-editar-actual').classList.add('hidden');
    }

    /**
     * OPCIÓN B: Cambiar totalmente de responsable.
     * Desbloquea TODO y limpia los campos.
     */
    function habilitarCambioResponsable() {
        if(!confirm("¿Deseas asignar un responsable distinto? Se borrarán los datos actuales.")) return;

        const campos = ['cedula', 'nombre_responsable', 'numero_de_celular', 'dependencia', 'cargo'];
        
        campos.forEach(id => {
            const el = document.getElementById(id);
            el.value = ''; // Limpiar campo
            el.readOnly = false;
            el.classList.remove('bg-gray-100', 'text-gray-500', 'cursor-not-allowed');
            el.classList.add('bg-white', 'border-orange-300', 'ring-1', 'ring-orange-100');
        });

        document.getElementById('tipo_funcionario_display').classList.add('hidden');
        document.getElementById('tipo_funcionario').classList.remove('hidden');
        document.getElementById('btn-buscar').classList.remove('hidden');
        document.getElementById('btn-buscar').classList.add('flex');

        const msj = document.getElementById('msj-responsable');
        msj.classList.remove('hidden');
        msj.innerText = "🔄 Ingrese la nueva cédula para buscar o registrar";
        msj.className = "text-[10px] font-bold mt-2 text-orange-600 block italic uppercase";

        document.getElementById('btn-cambiar').classList.add('hidden');
        document.getElementById('btn-editar-actual').classList.add('hidden');
        
        document.getElementById('cedula').focus();
    }
</script>

@include('partials.modal-buscar-responsable')

<script>
const _urlGenerarHostname = "{{ route('dispositivos.generar-hostname') }}";

async function generarHostnameAuto() {
    const sede       = document.getElementById('select-sede')?.value?.trim();
    const funcion    = document.querySelector('[name="funcion"], [name="funcion"] option:checked')?.value?.trim()
                    || '{{ $dispositivo->funcion }}';
    const tipoEquipo = document.getElementById('tipo-equipo')?.value;

    if (!sede || !tipoEquipo) {
        alert('Completa sede y tipo de equipo para generar el hostname.');
        return;
    }
    try {
        const params = new URLSearchParams({ sede, funcion, tipo_equipo: tipoEquipo });
        const res  = await fetch(`${_urlGenerarHostname}?${params}`);
        const data = await res.json();
        if (data.hostname) document.getElementById('input-hostname').value = data.hostname;
    } catch(e) { alert('Error al generar hostname.'); }
}

window.seleccionarDesdeModal = function(resp) {
    // Desbloquea todos los campos (mismo efecto que CAMBIAR, sin confirm)
    const campos = ['cedula','nombre_responsable','numero_de_celular','dependencia','cargo'];
    campos.forEach(id => {
        const el = document.getElementById(id);
        if (!el) return;
        el.readOnly = false;
        el.classList.remove('bg-gray-100','text-gray-500','cursor-not-allowed',
                            'border-orange-300','ring-orange-100');
        el.classList.add('bg-white','border-green-300','ring-1','ring-green-100');
    });

    document.getElementById('tipo_funcionario_display')?.classList.add('hidden');
    document.getElementById('tipo_funcionario')?.classList.remove('hidden');
    document.getElementById('btn-cambiar')?.classList.add('hidden');
    document.getElementById('btn-editar-actual')?.classList.add('hidden');
    document.getElementById('btn-buscar-nombre')?.classList.add('hidden');

    // Rellena los campos
    document.getElementById('cedula').value              = resp.cedula            || '';
    document.getElementById('nombre_responsable').value  = resp.nombre            || '';
    document.getElementById('numero_de_celular').value   = resp.numero_de_celular || '';
    document.getElementById('tipo_funcionario').value    = resp.tipo_funcionario  || 'Contratista';
    document.getElementById('dependencia').value         = resp.dependencia       || '';
    document.getElementById('cargo').value               = resp.cargo             || '';

    const msj = document.getElementById('msj-responsable');
    msj.classList.remove('hidden');
    msj.innerText = '✓ Responsable cambiado: ' + resp.nombre;
    msj.className = 'text-[10px] font-bold mt-2 text-green-600 block italic uppercase';
};
</script>
@endsection