@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto pb-12">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-black text-gray-800 uppercase italic tracking-tighter">
                Nuevo Registro <span class="text-[#39A900]">SENA</span>
            </h1>
            <p class="text-gray-500 text-sm font-medium">Gestión de Inventario - Regional Casanare</p>
        </div>
        <a href="{{ route('dispositivos.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-xl font-bold transition flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Volver al Listado
        </a>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border-l-8 border-red-500 p-4 mb-8 rounded-r-xl shadow-md">
            <div class="flex items-center mb-2">
                <i class="fas fa-exclamation-circle text-red-500 mr-2 text-xl"></i>
                <h3 class="text-red-800 font-black uppercase text-sm">Errores detectados</h3>
            </div>
            <ul class="text-red-700 text-sm list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('dispositivos.store') }}" method="POST" id="form-inventario">
        @csrf


        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden">
                <div class="flex items-center mb-6 text-[#39A900] font-black uppercase text-xs tracking-widest border-b pb-2">
                    <i class="fas fa-user-tie mr-2"></i> Datos del Responsable
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Cédula / ID *</label>
                        <div class="flex gap-2">
                            <input type="text" id="cedula" name="cedula" value="{{ old('cedula') }}" class="flex-1 bg-gray-50 border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-green-500 transition" required>
                            <button type="button" onclick="buscarResponsable()" title="Buscar por cédula"
                                    class="bg-blue-600 text-white px-4 rounded-xl hover:bg-blue-700 transition">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                        <button type="button" onclick="abrirModalResponsable()"
                                class="mt-2 text-[10px] font-bold text-[#39A900] hover:underline flex items-center gap-1 transition">
                            <i class="fas fa-users text-[9px]"></i> ¿No conoces la cédula? Busca por nombre
                        </button>
                        <p id="msj-responsable" class="text-[10px] font-bold mt-1 hidden italic uppercase tracking-tighter"></p>
                    </div>
                    
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Nombre Completo *</label>
                        <input type="text" id="nombre_responsable" name="nombre_responsable" value="{{ old('nombre_responsable') }}" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3" required>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Celular</label>
                            <input type="text" id="numero_de_celular" name="numero_de_celular" value="{{ old('numero_de_celular') }}" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3" placeholder="">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Tipo</label>
                            <select id="tipo_funcionario" name="tipo_funcionario" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3">
                                <option value="Contratista">Contratista</option>
                                <option value="Planta">Planta</option>
                                <option value="Aprendiz">Aprendiz</option>
                                <option value="Aprendiz">Externo</option>
                            </select>
                        </div>
        </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Dependencia</label>
                    <input type="text" id="dependencia" name="dependencia" placeholder="Bienestar, Contratación, etc.." class="w-full bg-gray-50 border-gray-200 rounded-xl p-3">
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Cargo</label>
                  <input type="text" id="cargo" name="cargo" placeholder="Apoyo,Instructor,Evaluador, etc.." class="w-full bg-gray-50 border-gray-200 rounded-xl p-3">
                </div>

    </div>

</div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <div class="flex items-center mb-6 text-[#39A900] font-black uppercase text-xs tracking-widest border-b pb-2">
                        <i class="fas fa-map-marker-alt mr-2"></i> Ubicación Física
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Sede *</label>
                            <select name="sede" id="select-sede" required
                                    onchange="sugerirHostname()"
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 font-bold text-gray-700 outline-none focus:ring-2 focus:ring-[#39A900] transition appearance-none">
                                <option value="">— Selecciona la sede —</option>
                                @foreach($sedes as $s)
                                    <option value="{{ $s }}" {{ old('sede') === $s ? 'selected' : '' }}>{{ $s }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Bloque</label>
                                <input type="text" name="bloque" value="{{ old('bloque') }}"
                                       placeholder="Ej: A, B, CUARTO"
                                       oninput="normalizarMayusculas(this)"
                                       style="text-transform:uppercase;"
                                       class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 font-mono text-sm outline-none focus:ring-2 focus:ring-[#39A900] transition">
                                <p class="text-[9px] text-gray-400 mt-0.5 ml-1">Se guarda en MAYÚSCULAS</p>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Ambiente *</label>
                                <input type="text" name="ambiente" value="{{ old('ambiente') }}"
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
                
                <div id="card-identificacion" class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 transition-all duration-500">
                    <div class="flex items-center mb-6 text-[#39A900] font-black uppercase text-xs tracking-widest border-b pb-2">
                        <i class="fas fa-desktop mr-2"></i> Identificación y Clasificación
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        {{-- Fila 1: Placa + Serial --}}
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Placa SENA *</label>
                            <input type="text" id="input-placa" name="placa" value="{{ old('placa') }}"
                                oninput="verificarPlacaRealTime()"
                                class="w-full bg-white border-[#39A900] border rounded-xl p-3 font-bold text-[#39A900] outline-none transition-all" placeholder="Ej: 95191020321" required>
                            <p id="msj-placa" class="text-[10px] font-bold mt-2 hidden italic uppercase tracking-tighter"></p>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Serial de Fábrica *</label>
                            <input type="text" id="input-serial" name="serial" value="{{ old('serial') }}"
                                oninput="verificarSerialRealTime()"
                                class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 font-mono uppercase outline-none focus:bg-white transition @error('serial') border-red-400 ring-2 ring-red-100 @enderror" required>
                            <p id="msj-serial" class="text-[10px] font-bold mt-2 hidden italic uppercase tracking-tighter"></p>
                            @error('serial')
                                <p class="text-[10px] font-bold mt-1 text-red-500 italic">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Fila 2: Categoría + Tipo Equipo (al lado) --}}
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Categoría *</label>
                            <select name="categoria" id="categoria-select" onchange="toggleSecciones()"
                                    class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 font-bold text-[#39A900] outline-none focus:bg-white transition">
                                <option value="computo">Computadores</option>
                                <option value="conectividad">Redes / Conectividad</option>
                                <option value="impresoras">Impresoras / Escáner</option>
                            </select>
                        </div>
                        <div id="div-tipo-equipo" class="hidden">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Tipo de Equipo</label>
                            <select name="tipo_equipo" id="tipo-equipo"
                                    class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 font-bold outline-none focus:bg-white transition"
                                    onchange="sugerirHostname()">
                                <option value="">— Selecciona —</option>
                                <option value="Portátil"    {{ old('tipo_equipo') === 'Portátil'    ? 'selected' : '' }}>Portátil</option>
                                <option value="Escritorio"  {{ old('tipo_equipo') === 'Escritorio'  ? 'selected' : '' }}>Escritorio</option>
                                <option value="Workstation" {{ old('tipo_equipo') === 'Workstation' ? 'selected' : '' }}>Workstation</option>
                            </select>
                        </div>
                        {{-- Placeholder cuando no es computo --}}
                        <div id="div-tipo-equipo-vacio" class="hidden"></div>

                        {{-- Fila 3: Propietario + Función + Intune --}}
                        <div class="md:col-span-2 grid grid-cols-3 gap-4 bg-gray-50 p-4 rounded-xl border border-gray-100">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Propietario</label>
                                <select name="propietario" class="w-full bg-white border-gray-200 rounded-lg p-2 text-sm font-bold outline-none">
                                    <option value="SENA">SENA</option>
                                    <option value="TELEFONICA">TELEFÓNICA</option>
                                    <option value="OTRO">OTRO</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Función</label>
                                <select name="funcion" class="w-full bg-white border-gray-200 rounded-lg p-2 text-sm font-bold outline-none" onchange="sugerirHostname()">
                                    <option value="FORMACION">APRENDIZ / FORMACIÓN</option>
                                    <option value="ADMINISTRATIVO">ADMINISTRATIVO</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">¿INTUNE?</label>
                                <select name="en_intune" class="w-full bg-white border-gray-200 rounded-lg p-2 text-sm font-bold outline-none">
                                    <option value="NO">NO</option>
                                    <option value="SI">SI</option>
                                </select>
                            </div>
                        </div>

                        {{-- Fila 4: Marca + Modelo --}}
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Marca</label>
                            <input type="text" name="marca" value="{{ old('marca') }}" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 outline-none focus:bg-white transition" placeholder="ASUS, HP, Dell...">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Modelo</label>
                            <input type="text" name="modelo" value="{{ old('modelo') }}" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 outline-none focus:bg-white transition" placeholder="ExpertBook B1400...">
                        </div>

                        {{-- Fila 5: Estado Físico + Estado Lógico --}}
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Estado Físico</label>
                            <select name="estado_fisico" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 font-bold outline-none focus:bg-white transition">
                                @foreach(['Bueno','Regular','Malo','En Reparación'] as $ef)
                                    <option value="{{ $ef }}" {{ old('estado_fisico','Bueno') === $ef ? 'selected' : '' }}>{{ $ef }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Estado Lógico</label>
                            <select name="estado_logico" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 font-bold outline-none focus:bg-white transition">
                                @foreach(['Bueno','Regular','Malo'] as $el)
                                    <option value="{{ $el }}" {{ old('estado_logico','Bueno') === $el ? 'selected' : '' }}>{{ $el }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Fila 6: Hostname (al final, con botón generar) --}}
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">
                                Hostname / Nombre del Equipo
                                <span class="text-gray-300 font-normal normal-case ml-1" id="hint-hostname">— selecciona categoría, función y tipo para generar automáticamente</span>
                            </label>
                            <div class="flex gap-2">
                                <input type="text" name="hostname" id="input-hostname" value="{{ old('hostname') }}"
                                    class="flex-1 bg-gray-50 border-gray-200 rounded-xl p-3 font-mono uppercase outline-none focus:bg-white transition"
                                    placeholder="Ej: YOPADRCNCSD001"
                                    pattern="[a-zA-Z0-9\-_\.]+"
                                    oninput="this.value=this.value.toUpperCase(); this.dataset.generado='false';"
                                    title="Solo letras, números, guiones, guión bajo y puntos.">
                                <button type="button" id="btn-generar-hostname"
                                        onclick="generarHostnameAuto()"
                                        class="hidden px-4 py-3 bg-[#39A900] text-white rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-green-700 transition whitespace-nowrap flex-shrink-0">
                                    <i class="fas fa-magic mr-1"></i> Generar
                                </button>
                            </div>
                            <div id="msj-hostname" class="text-[10px] mt-1 text-gray-400">Sin espacios ni caracteres especiales</div>
                        </div>

                    </div>
                </div>



                <div id="seccion-computo" class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <div class="flex items-center mb-6 text-[#39A900] font-black uppercase text-xs tracking-widest border-b pb-2">
                        <i class="fas fa-server mr-2"></i> Especificaciones del Sistema
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Procesador</label>
                            <input type="text" name="procesador" value="{{ old('procesador') }}" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm" placeholder="Ej: Core i7-1165G7">
                        </div>

                        {{-- RAM: solo número, badge GB --}}
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">
                                Memoria RAM <span class="text-gray-300 font-normal normal-case">(en GB)</span>
                            </label>
                            <div class="flex items-stretch bg-gray-50 border border-gray-200 rounded-xl overflow-hidden">
                                <input type="number" name="ram" value="{{ old('ram') }}" min="1" max="512" step="1"
                                       class="flex-1 bg-transparent px-3 py-3 text-sm outline-none"
                                       placeholder="Ej: 16">
                                <span class="px-3 flex items-center text-[10px] font-black text-gray-400 bg-gray-100 border-l border-gray-200">GB</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">S.O.</label>
                            <input type="text" name="so" value="{{ old('so') }}" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm" placeholder="Ej: Windows 11 Pro">
                        </div>

                        {{-- Tipo Disco: lista predefinida --}}
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Tipo de Disco</label>
                            <select name="tipo_disco" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm font-bold text-gray-700 outline-none focus:ring-2 focus:ring-[#39A900]">
                                <option value="">— Seleccionar —</option>
                                <option value="SSD — Sólido SATA"  {{ old('tipo_disco') == 'SSD — Sólido SATA'  ? 'selected' : '' }}>SSD — Sólido SATA</option>
                                <option value="SSD M.2 — NVMe"     {{ old('tipo_disco') == 'SSD M.2 — NVMe'     ? 'selected' : '' }}>SSD M.2 — NVMe</option>
                                <option value="HDD — Mecánico"     {{ old('tipo_disco') == 'HDD — Mecánico'     ? 'selected' : '' }}>HDD — Mecánico</option>
                                <option value="SSHD — Híbrido"     {{ old('tipo_disco') == 'SSHD — Híbrido'     ? 'selected' : '' }}>SSHD — Híbrido</option>
                                <option value="N/A"                {{ old('tipo_disco') == 'N/A'                ? 'selected' : '' }}>N/A</option>
                            </select>
                        </div>

                        {{-- Capacidad: solo número, badge GB --}}
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">
                                Capacidad <span class="text-gray-300 font-normal normal-case">(en GB)</span>
                            </label>
                            <div class="flex items-stretch bg-gray-50 border border-gray-200 rounded-xl overflow-hidden">
                                <input type="number" name="capacidad_disco" value="{{ old('capacidad_disco') }}" min="1" step="1"
                                       class="flex-1 bg-transparent px-3 py-3 text-sm outline-none"
                                       placeholder="Ej: 512">
                                <span class="px-3 flex items-center text-[10px] font-black text-gray-400 bg-gray-100 border-l border-gray-200">GB</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">MAC Address</label>
                            <input type="text" name="mac_address" value="{{ old('mac_address') }}" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 font-mono text-xs uppercase" placeholder="Ej: AA:BB:CC:DD:EE:FF">
                        </div>
                    </div>
                </div>

                <div id="seccion-redes" class="hidden bg-blue-50 p-6 rounded-2xl border border-blue-100 shadow-inner">
                    <div class="flex items-center mb-6 text-blue-700 font-black uppercase text-xs tracking-widest border-b border-blue-200 pb-2">
                        <i class="fas fa-network-wired mr-2"></i> Detalles de Red (Switch/AP)
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-bold text-blue-400 uppercase mb-1">Descripción Técnica (Equipo)</label>
                            <textarea name="descripcion_tecnica" rows="2" class="w-full border-blue-100 rounded-xl p-3 text-xs">{{ old('descripcion_tecnica') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-blue-400 uppercase mb-1">No. Puertos</label>
                            <input type="number" name="puertos" value="{{ old('puertos') }}" class="w-full border-blue-100 rounded-xl p-3">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-blue-400 uppercase mb-1">MAC Red</label>
                            <input type="text" name="mac_red" value="{{ old('mac_red') }}" class="w-full border-blue-100 rounded-xl p-3 font-mono text-xs">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-blue-400 uppercase mb-1">AP Conectado a SW</label>
                            <input type="text" name="ap_conectado_a" value="{{ old('ap_conectado_a') }}" class="w-full border-blue-100 rounded-xl p-3">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-blue-400 uppercase mb-1">Puerto del SW</label>
                            <input type="text" name="puerto_origen" value="{{ old('puerto_origen') }}" class="w-full border-blue-100 rounded-xl p-3">
                        </div>
                    </div>
                    <div class="mt-6 border-t border-blue-200 pt-4">
                        <h4 class="text-[10px] font-black text-blue-500 uppercase mb-3">Módulos SFP (Slots 1-4)</h4>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                            @foreach([1, 2, 3, 4] as $i)
                                <input type="text" name="perifericos[SFP Slot {{ $i }}][placa]" placeholder="Placa SFP {{ $i }}" class="w-full text-[10px] p-2 rounded-lg border-blue-100">
                            @endforeach
                        </div>
                    </div>
                </div>

                <div id="seccion-perifericos" class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <div class="flex items-center mb-6 text-[#39A900] font-black uppercase text-xs tracking-widest border-b pb-2">
                        <i class="fas fa-plug mr-2"></i> Periféricos Asociados
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach(['Monitor', 'Teclado', 'Mouse', 'Cargador'] as $periferico)
                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                            <h4 class="text-[10px] font-black text-gray-400 uppercase mb-3 italic">{{ $periferico }}</h4>
                            <div class="grid grid-cols-2 gap-2">
                                <input type="text" name="perifericos[{{ $periferico }}][placa]"  placeholder="Placa SENA"  class="text-[10px] p-2 rounded-lg border-gray-200 bg-white">
                                <input type="text" name="perifericos[{{ $periferico }}][serial]" placeholder="Serial"       class="text-[10px] p-2 rounded-lg border-gray-200 bg-white">
                                <input type="text" name="perifericos[{{ $periferico }}][marca]"  placeholder="Marca"        class="text-[10px] p-2 rounded-lg border-gray-200 bg-white">
                                <input type="text" name="perifericos[{{ $periferico }}][modelo]" placeholder="Modelo"       class="text-[10px] p-2 rounded-lg border-gray-200 bg-white">
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Observaciones / Novedades</label>
                    <textarea name="observaciones" rows="3" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm">{{ old('observaciones') }}</textarea>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit" class="bg-[#39A900] text-white px-16 py-5 rounded-2xl font-black uppercase tracking-widest shadow-2xl hover:scale-105 transition-all active:scale-95">
                        <i class="fas fa-save mr-3 text-xl"></i> Registrar Equipo
                    </button>
                </div>
            </div>
        </div>



    </form>
</div>
<script>
    /**
     * Alterna la visibilidad entre Cómputo y Redes
     * No se pierde la lógica de perifericos
     */
    function toggleSecciones() {
        const cat = document.getElementById('categoria-select')?.value;
        const sComputo = document.getElementById('seccion-computo');
        const sRedes = document.getElementById('seccion-redes');
        const sPerifericos = document.getElementById('seccion-perifericos');
        const divTipoEquipo = document.getElementById('div-tipo-equipo');
        const btnGenerar = document.getElementById('btn-generar-hostname');
        const esComputo = cat === 'computo';

        // Mostrar/ocultar tipo_equipo y placeholder + botón hostname
        divTipoEquipo?.classList.toggle('hidden', !esComputo);
        btnGenerar?.classList.toggle('hidden', !esComputo);
        document.getElementById('div-tipo-equipo-vacio')?.classList.toggle('hidden', esComputo);

        if (cat === 'conectividad') {
            sRedes?.classList.remove('hidden');
            sComputo?.classList.add('hidden');
            sPerifericos?.classList.add('hidden');
        } else {
            sRedes?.classList.add('hidden');
            sComputo?.classList.remove('hidden');
            sPerifericos?.classList.remove('hidden');
        }
    }

    // Ejecutar al cargar para validar el estado inicial
    window.onload = toggleSecciones;

    /**
     * Busca responsable con ruta dinámica
     */
    function buscarResponsable() {
        const cedulaInput = document.getElementById('cedula');
        const msj = document.getElementById('msj-responsable');
        
        if (!cedulaInput || !msj) return;

        const cedula = cedulaInput.value.trim();
        
        // Limpiamos mensajes previos
        msj.classList.add('hidden');
        msj.innerText = '';

        if (!cedula) return;

        // URL dinámica: Se adapta si es /gitic/ o si es localhost
        fetch("{{ url('/responsables/buscar') }}/" + cedula)
            .then(res => res.json())
            .then(data => {
                // Compatibilidad de objeto: data o data.responsable
                const resp = data.responsable || (data.id ? data : null);

                if (resp && resp.id) {
                    // EXITO: Llenamos campos
                    document.getElementById('nombre_responsable').value = resp.nombre || '';
                    document.getElementById('numero_de_celular').value = resp.numero_de_celular || '';
                    document.getElementById('tipo_funcionario').value = resp.tipo_funcionario || 'Contratista';
                    document.getElementById('dependencia').value = resp.dependencia || '';
                    document.getElementById('cargo').value = resp.cargo || '';

                    // Aviso visual de éxito (Mantiene tu estilo)
                    msj.innerText = "✓ Responsable encontrado";
                    msj.className = "text-[10px] font-bold mt-2 text-green-600 block italic";
                    msj.classList.remove('hidden');
                } else {
                    // ERROR: No existe
                    msj.innerText = "✗ El número de identificación no existe en la base de datos";
                    msj.className = "text-[10px] font-bold mt-2 text-red-500 block italic";
                    msj.classList.remove('hidden');
                    
                    // Limpiar campos para evitar datos erróneos
                    const campos = ['nombre_responsable', 'numero_de_celular', 'dependencia', 'cargo'];
                    campos.forEach(id => document.getElementById(id).value = '');
                }
            })
            .catch(error => {
                msj.innerText = "⚠ Error de conexión con el sistema";
                msj.className = "text-[10px] font-bold mt-2 text-orange-500 block";
                msj.classList.remove('hidden');
                console.error(error);
            });
    }

    /**
     * Verificación de placa en tiempo real con ruta dinámica
     */
    function verificarPlacaRealTime() {
        const input = document.getElementById('input-placa');
        const msj = document.getElementById('msj-placa');
        const card = document.getElementById('card-identificacion');

        if (!input || !msj || !card) return;

        const placa = input.value.trim();

        // Reseteo visual si está vacío
        if (placa.length < 1) {
            msj.classList.add('hidden');
            card.classList.remove('border-red-500', 'border-green-500', 'ring-4', 'ring-red-50', 'ring-green-50');
            input.classList.remove('text-red-600');
            input.classList.add('text-[#39A900]');
            return;
        }

        // URL dinámica para verificación
        fetch("{{ url('/dispositivos/verificar-placa') }}/" + placa)
            .then(res => res.json())
            .then(data => {
                msj.classList.remove('hidden');
                if (data.exists) {
                    // SEMÁFORO ROJO (Mismo estilo SENA)
                    msj.innerText = "✗ Esta placa ya está registrada en el inventario";
                    msj.className = "text-[10px] font-bold mt-2 text-red-500 block italic uppercase tracking-tighter";
                    card.classList.add('border-red-500', 'ring-4', 'ring-red-50');
                    card.classList.remove('border-green-500', 'ring-green-50');
                    input.classList.remove('text-[#39A900]');
                    input.classList.add('text-red-600');
                } else {
                    // SEMÁFORO VERDE
                    msj.innerText = "✓ Placa disponible para registro";
                    msj.className = "text-[10px] font-bold mt-2 text-green-600 block italic uppercase tracking-tighter";
                    card.classList.add('border-green-500', 'ring-4', 'ring-green-50');
                    card.classList.remove('border-red-500', 'ring-red-50');
                    input.classList.remove('text-red-600');
                    input.classList.add('text-[#39A900]');
                }
            })
            .catch(err => console.error("Error de conexión:", err));
    }

    let _serialTimer = null;
    function verificarSerialRealTime() {
        const input = document.getElementById('input-serial');
        const msj   = document.getElementById('msj-serial');
        if (!input || !msj) return;

        const serial = input.value.trim();

        if (serial.length < 3) {
            msj.classList.add('hidden');
            input.classList.remove('border-red-400', 'ring-2', 'ring-red-100', 'border-green-500', 'ring-green-100');
            return;
        }

        clearTimeout(_serialTimer);
        _serialTimer = setTimeout(() => {
            fetch("{{ url('/dispositivos/verificar-serial') }}/" + encodeURIComponent(serial))
                .then(res => res.json())
                .then(data => {
                    msj.classList.remove('hidden');
                    if (data.exists) {
                        msj.innerText = "✗ Serial ya registrado en la placa: " + data.placa;
                        msj.className = "text-[10px] font-bold mt-2 text-red-500 block italic uppercase tracking-tighter";
                        input.classList.add('border-red-400', 'ring-2', 'ring-red-100');
                        input.classList.remove('border-green-500', 'ring-green-100');
                    } else {
                        msj.innerText = "✓ Serial disponible";
                        msj.className = "text-[10px] font-bold mt-2 text-green-600 block italic uppercase tracking-tighter";
                        input.classList.add('border-green-500', 'ring-2', 'ring-green-100');
                        input.classList.remove('border-red-400', 'ring-red-100');
                    }
                })
                .catch(err => console.error("Error verificando serial:", err));
        }, 500);
    }
</script>

@include('partials.modal-buscar-responsable')

<script>
/**
 * Genera el hostname según nomenclatura SENA:
 * [SEDE 4][DR|PR][CNCS][P|D|W][001]
 * Llama al backend para obtener el siguiente consecutivo disponible.
 */
const _urlGenerarHostname = "{{ route('dispositivos.generar-hostname') }}";

function sugerirHostname() {
    // Si ya hay un hostname escrito manualmente, no sobreescribir
    const hostnameInput = document.getElementById('input-hostname');
    if (hostnameInput?.value && hostnameInput.dataset.generado !== 'true') return;
    generarHostnameAuto();
}

async function generarHostnameAuto() {
    const sede       = document.getElementById('select-sede')?.value?.trim();
    const funcion    = document.querySelector('[name="funcion"]')?.value?.trim();
    const tipoEquipo = document.getElementById('tipo-equipo')?.value;
    const msj        = document.getElementById('msj-hostname');
    const input      = document.getElementById('input-hostname');

    if (!sede || !tipoEquipo) {
        if (msj) {
            msj.textContent = '⚠ Completa sede, función y tipo de equipo para generar.';
            msj.className = 'text-[10px] mt-1 text-orange-500';
        }
        return;
    }

    if (msj) { msj.textContent = 'Generando...'; msj.className = 'text-[10px] mt-1 text-blue-500'; }

    try {
        const params = new URLSearchParams({ sede, funcion, tipo_equipo: tipoEquipo });
        const res = await fetch(`${_urlGenerarHostname}?${params}`);
        const data = await res.json();

        if (data.hostname) {
            input.value = data.hostname;
            input.dataset.generado = 'true';
            if (msj) {
                msj.innerHTML = `<i class="fas fa-check-circle text-green-500 mr-1"></i>Hostname generado: <strong>${data.hostname}</strong> · Puedes editarlo si lo necesitas`;
                msj.className = 'text-[10px] mt-1 text-green-600';
            }
        }
    } catch (e) {
        if (msj) { msj.textContent = '⚠ Error al generar hostname.'; msj.className = 'text-[10px] mt-1 text-red-500'; }
    }
}

// Limpiar flag de "generado" si el usuario edita manualmente
document.getElementById('input-hostname')?.addEventListener('input', function () {
    this.dataset.generado = 'false';
});

window.seleccionarDesdeModal = function(resp) {
    document.getElementById('cedula').value              = resp.cedula            || '';
    document.getElementById('nombre_responsable').value  = resp.nombre            || '';
    document.getElementById('numero_de_celular').value   = resp.numero_de_celular || '';
    document.getElementById('tipo_funcionario').value    = resp.tipo_funcionario  || 'Contratista';
    document.getElementById('dependencia').value         = resp.dependencia       || '';
    document.getElementById('cargo').value               = resp.cargo             || '';

    const msj = document.getElementById('msj-responsable');
    msj.innerText   = '✓ Responsable seleccionado: ' + resp.nombre;
    msj.className   = 'text-[10px] font-bold mt-1 text-green-600 block italic';
};
</script>
@endsection