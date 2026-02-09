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
                        <button type="button" onclick="habilitarCambioResponsable()" id="btn-cambiar" 
                                class="text-[10px] font-bold bg-orange-100 text-orange-600 px-2 py-1 rounded-lg hover:bg-orange-200 transition">
                            <i class="fas fa-exchange-alt mr-1"></i> CAMBIAR
                        </button>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Cédula / ID *</label>
                            <div class="flex gap-2">
                                <input type="text" id="cedula" name="cedula" value="{{ old('cedula', $dispositivo->responsable->cedula) }}" 
                                    class="flex-1 bg-gray-100 border-gray-200 rounded-xl p-3 font-bold text-gray-500 shadow-inner cursor-not-allowed" 
                                    readonly required>
                                
                                <button type="button" id="btn-buscar" onclick="buscarResponsable()" 
                                        class="hidden bg-blue-600 text-white px-4 rounded-xl hover:bg-blue-700 transition shadow-sm items-center justify-center">
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
                                    <option value="Contratista">Contratista</option>
                                    <option value="Planta">Planta</option>
                                    <option value="Aprendiz">Aprendiz</option>
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
                    <div class="space-y-4">
                        <input type="text" name="sede" list="listado-sedes" value="{{ old('sede', $dispositivo->ubicacion->sede) }}" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3" required>
                        <datalist id="listado-sedes">
                            <option value="Yopal"><option value="Paz de Ariporo"><option value="Monterrey"><option value="Aguazul"><option value="Villanueva">
                        </datalist>
                        <div class="grid grid-cols-2 gap-3">
                            <input type="text" name="bloque" value="{{ old('bloque', $dispositivo->ubicacion->bloque) }}" placeholder="Bloque" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3">
                            <input type="text" name="ambiente" value="{{ old('ambiente', $dispositivo->ubicacion->ambiente) }}" placeholder="Ambiente" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 space-y-6">
                
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <div class="flex items-center mb-6 text-[#39A900] font-black uppercase text-xs tracking-widest border-b pb-2">
                        <i class="fas fa-desktop mr-2"></i> Identificación del Bien
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                       <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">
                                Placa SENA <i class="fas fa-lock ml-1 text-gray-300"></i>
                            </label>
                            <input type="text" 
                                name="placa" 
                                value="{{ $dispositivo->placa }}" 
                                class="w-full bg-gray-100 border-gray-300 border-2 rounded-xl p-3 font-black text-xl text-gray-500 shadow-inner cursor-not-allowed" 
                                readonly>
                            <p class="text-[9px] text-gray-400 mt-1 italic">* La placa no se puede modificar una vez registrada.</p>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Serial de Fábrica *</label>
                            <input type="text" name="serial" value="{{ old('serial', $dispositivo->serial) }}" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 font-mono uppercase" required>
                        </div>

                        <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-4 bg-gray-50 p-4 rounded-xl border border-gray-100">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Propietario</label>
                                @php $prop = old('propietario', $dispositivo->propietario); @endphp
                                <select name="propietario" class="w-full bg-white border-gray-200 rounded-lg p-2 text-sm font-bold">
                                    <option value="SENA" {{ $prop == 'SENA' ? 'selected' : '' }}>SENA</option>
                                    <option value="TELEFONICA" {{ $prop == 'TELEFONICA' ? 'selected' : '' }}>TELEFÓNICA</option>
                                    <option value="OTRO" {{ $prop == 'OTRO' ? 'selected' : '' }}>OTRO</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Función</label>
                                @php $func = old('funcion', $dispositivo->funcion); @endphp
                                <select name="funcion" class="w-full bg-white border-gray-200 rounded-lg p-2 text-sm font-bold">
                                    <option value="FORMACION" {{ $func == 'FORMACION' ? 'selected' : '' }}>FORMACIÓN</option>
                                    <option value="ADMINISTRATIVO" {{ $func == 'ADMINISTRATIVO' ? 'selected' : '' }}>ADMINISTRATIVO</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">¿INTUNE?</label>
                                @php $intune = old('en_intune', $dispositivo->en_intune); @endphp
                                <select name="en_intune" class="w-full bg-white border-gray-200 rounded-lg p-2 text-sm font-bold">
                                    <option value="NO" {{ $intune == 'NO' ? 'selected' : '' }}>NO</option>
                                    <option value="SI" {{ $intune == 'SI' ? 'selected' : '' }}>SI</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <input type="text" name="marca" value="{{ old('marca', $dispositivo->marca) }}" placeholder="Marca" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3">
                            <input type="text" name="modelo" value="{{ old('modelo', $dispositivo->modelo) }}" placeholder="Modelo" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <select name="categoria" id="categoria-select" onchange="toggleSecciones()" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 font-bold text-[#39A900]">
                                <option value="computo" {{ $dispositivo->categoria == 'computo' ? 'selected' : '' }}>Computadores</option>
                                <option value="conectividad" {{ $dispositivo->categoria == 'conectividad' ? 'selected' : '' }}>Redes / Conectividad</option>
                                <option value="impresoras" {{ $dispositivo->categoria == 'impresoras' ? 'selected' : '' }}>Impresoras</option>
                            </select>
                            <select name="estado_fisico" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 font-bold">
                                <option value="Bueno" {{ $dispositivo->estado_fisico == 'Bueno' ? 'selected' : '' }}>Bueno</option>
                                <option value="Regular" {{ $dispositivo->estado_fisico == 'Regular' ? 'selected' : '' }}>Regular</option>
                                <option value="Malo" {{ $dispositivo->estado_fisico == 'Malo' ? 'selected' : '' }}>Malo</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div id="seccion-computo" class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <div class="flex items-center mb-6 text-[#39A900] font-black uppercase text-xs tracking-widest border-b pb-2">
                        <i class="fas fa-server mr-2"></i> Especificaciones de Cómputo
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @php $especs = $dispositivo->especificaciones; @endphp
                        <input type="text" name="procesador" value="{{ old('procesador', $especs->procesador ?? '') }}" placeholder="Procesador" class="bg-gray-50 border-gray-200 rounded-xl p-3 text-sm">
                        <input type="text" name="ram" value="{{ old('ram', $especs->ram ?? '') }}" placeholder="Memoria RAM" class="bg-gray-50 border-gray-200 rounded-xl p-3 text-sm">
                        <input type="text" name="so" value="{{ old('so', $especs->so ?? '') }}" placeholder="Sistema Operativo" class="bg-gray-50 border-gray-200 rounded-xl p-3 text-sm">
                        <input type="text" name="tipo_disco" value="{{ old('tipo_disco', $especs->tipo_disco ?? '') }}" placeholder="Tipo Disco" class="bg-gray-50 border-gray-200 rounded-xl p-3 text-sm">
                        <input type="text" name="capacidad_disco" value="{{ old('capacidad_disco', $especs->capacidad_disco ?? '') }}" placeholder="Capacidad" class="bg-gray-50 border-gray-200 rounded-xl p-3 text-sm">
                        <input type="text" name="mac_address" value="{{ old('mac_address', $especs->mac_address ?? '') }}" placeholder="MAC PC" class="bg-gray-50 border-gray-200 rounded-xl p-3 font-mono text-xs">
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
                                <input type="text" name="perifericos[{{ $tipo }}][placa]" value="{{ old("perifericos.$tipo.placa", $existente->placa ?? '') }}" placeholder="Placa" class="text-[10px] p-2 rounded-lg border-gray-200">
                                <input type="text" name="perifericos[{{ $tipo }}][serial]" value="{{ old("perifericos.$tipo.serial", $existente->serial ?? '') }}" placeholder="Serial" class="text-[10px] p-2 rounded-lg border-gray-200">
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
 * Desbloquea los campos del responsable para permitir la reasignación
 * Evita modificar accidentalmente al responsable actual
 */
                function habilitarCambioResponsable() {
                    // 1. Confirmación visual para el usuario
                    if (!confirm("¿Deseas asignar un NUEVO responsable a este equipo? Esto limpiará los datos actuales.")) return;

                    // 2. IDs de los campos a habilitar
                    const campos = ['cedula', 'nombre_responsable', 'numero_de_celular', 'dependencia', 'cargo'];
                    
                    campos.forEach(id => {
                        const el = document.getElementById(id);
                        el.value = ''; // Limpiamos el valor para evitar sobrescribir al anterior
                        el.readOnly = false;
                        el.classList.remove('bg-gray-100', 'text-gray-500', 'cursor-not-allowed');
                        el.classList.add('bg-white', 'border-blue-300', 'ring-2', 'ring-blue-50');
                    });

                    // 3. Manejo especial de los selectores
                    document.getElementById('tipo_funcionario_display').classList.add('hidden');
                    document.getElementById('tipo_funcionario').classList.remove('hidden');
                    document.getElementById('btn-buscar').classList.remove('hidden');
                    document.getElementById('btn-buscar').classList.add('flex');
                    document.getElementById('btn-cambiar').classList.add('hidden');

                    // 4. Foco en la cédula para iniciar búsqueda
                    document.getElementById('cedula').focus();
                }

// Mantenemos tu función buscarResponsable() tal como la tienes, 
// ya que funcionará perfectamente una vez desbloqueados los campos.
</script>
@endsection