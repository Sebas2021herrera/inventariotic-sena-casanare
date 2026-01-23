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
                            <button type="button" onclick="buscarResponsable()" class="bg-blue-600 text-white px-4 rounded-xl hover:bg-blue-700 transition">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                        <p id="msj-responsable" class="text-[10px] font-bold mt-2 hidden italic uppercase tracking-tighter"></p>
                    </div>
                    
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Nombre Completo *</label>
                        <input type="text" id="nombre_responsable" name="nombre_responsable" value="{{ old('nombre_responsable') }}" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3" required>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Celular</label>
                            <input type="text" id="numero_de_celular" name="numero_de_celular" value="{{ old('numero_de_celular') }}" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Tipo</label>
                            <select id="tipo_funcionario" name="tipo_funcionario" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3">
                                <option value="Contratista">Contratista</option>
                                <option value="Planta">Planta</option>
                                <option value="Aprendiz">Aprendiz</option>
                            </select>
                        </div>
        </div>

        <input type="text" id="dependencia" name="dependencia" placeholder="Dependencia" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3">
        <input type="text" id="cargo" name="cargo" placeholder="Cargo" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3">
    </div>

</div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <div class="flex items-center mb-6 text-[#39A900] font-black uppercase text-xs tracking-widest border-b pb-2">
                        <i class="fas fa-map-marker-alt mr-2"></i> Ubicación Física
                    </div>
                    <div class="space-y-4">
                        <input type="text" name="sede" list="listado-sedes" placeholder="Sede" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3" required>
                        <datalist id="listado-sedes">
                            <option value="Yopal"><option value="Paz de Ariporo"><option value="Monterrey"><option value="Aguazul"><option value="Villanueva">
                        </datalist>
                        <div class="grid grid-cols-2 gap-3">
                            <input type="text" name="bloque" placeholder="Bloque" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3">
                            <input type="text" name="ambiente" placeholder="Ambiente" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 space-y-6">
                
                <div id="card-identificacion" class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 transition-all duration-500">
                    <div class="flex items-center mb-6 text-[#39A900] font-black uppercase text-xs tracking-widest border-b pb-2">
                        <i class="fas fa-desktop mr-2"></i> Identificación y Clasificación
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Placa SENA *</label>
                            <input type="text" id="input-placa" name="placa" value="{{ old('placa') }}" 
                                oninput="verificarPlacaRealTime()" 
                               class="w-full bg-white border-[#39A900] border rounded-xl p-3 font-bold text-[#39A900] outline-none transition-all"  placeholder="Ej: 95191020321" required>
                            <p id="msj-placa" class="text-[10px] font-bold mt-2 hidden italic uppercase tracking-tighter"></p>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Serial de Fábrica *</label>
                            <input type="text" name="serial" value="{{ old('serial') }}" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 font-mono uppercase outline-none focus:bg-white transition" required>
                        </div>

                        <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-4 bg-gray-50 p-4 rounded-xl border border-gray-100">
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
                                <select name="funcion" class="w-full bg-white border-gray-200 rounded-lg p-2 text-sm font-bold outline-none">
                                    <option value="FORMACION">FORMACIÓN</option>
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

                        <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Marca</label>
                                <input type="text" name="marca" value="{{ old('marca') }}" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 outline-none focus:bg-white transition">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Modelo</label>
                                <input type="text" name="modelo" value="{{ old('modelo') }}" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 outline-none focus:bg-white transition">
                            </div>
                        </div>

                        <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Categoría</label>
                                <select name="categoria" id="categoria-select" onchange="toggleSecciones()" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 font-bold text-[#39A900] outline-none">
                                    <option value="computo">Computadores</option>
                                    <option value="conectividad">Redes / Conectividad</option>
                                    <option value="impresoras">Impresoras / Escáner</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Estado Físico</label>
                                <select name="estado_fisico" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 font-bold outline-none">
                                    <option value="Bueno" class="text-green-600">Bueno</option>
                                    <option value="Regular" class="text-yellow-600">Regular</option>
                                    <option value="Malo" class="text-red-600">Malo</option>
                                </select>
                            </div>
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
                            <input type="text" name="procesador" value="{{ old('procesador') }}" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm" placeholder="Ej: Core i7">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Memoria RAM</label>
                            <input type="text" name="ram" value="{{ old('ram') }}" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm" placeholder="Ej: 16 GB">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">S.O.</label>
                            <input type="text" name="so" value="{{ old('so') }}" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Tipo Disco</label>
                            <input type="text" name="tipo_disco" value="{{ old('tipo_disco') }}" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm" placeholder="SSD / HDD">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Capacidad</label>
                            <input type="text" name="capacidad_disco" value="{{ old('capacidad_disco') }}" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">MAC Address</label>
                            <input type="text" name="mac_address" value="{{ old('mac_address') }}" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 font-mono text-xs uppercase">
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
                                <input type="text" name="perifericos[{{ $periferico }}][placa]" placeholder="Placa" class="text-[10px] p-2 rounded-lg border-gray-200">
                                <input type="text" name="perifericos[{{ $periferico }}][serial]" placeholder="Serial" class="text-[10px] p-2 rounded-lg border-gray-200">
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
     */
    function toggleSecciones() {
        const cat = document.getElementById('categoria-select').value;
        const sComputo = document.getElementById('seccion-computo');
        const sRedes = document.getElementById('seccion-redes');
        const sPerifericos = document.getElementById('seccion-perifericos');

        if (cat === 'conectividad') {
            sRedes.classList.remove('hidden');
            sComputo.classList.add('hidden');
            sPerifericos.classList.add('hidden'); // Opcional: ocultar monitor/teclado para redes
        } else {
            sRedes.classList.add('hidden');
            sComputo.classList.remove('hidden');
            sPerifericos.classList.remove('hidden');
        }
    }

    // Ejecutar al cargar para validar el estado inicial
    window.onload = toggleSecciones;

 function buscarResponsable() {
    const cedula = document.getElementById('cedula').value;
    const msj = document.getElementById('msj-responsable');
    
    // Limpiamos mensajes previos y campos
    msj.classList.add('hidden');
    msj.innerText = '';

    if (!cedula) return;

    // Prefijo GITIC incluido
    fetch(`/gitic/responsables/buscar/${cedula}`)
        .then(res => res.json())
        .then(data => {
            if (data && data.id) {
                // EXITO: Llenamos campos
                document.getElementById('nombre_responsable').value = data.nombre;
                document.getElementById('numero_de_celular').value = data.numero_de_celular;
                document.getElementById('tipo_funcionario').value = data.tipo_funcionario;
                document.getElementById('dependencia').value = data.dependencia;
                document.getElementById('cargo').value = data.cargo;

                // Aviso visual de éxito
                msj.innerText = "✓ Responsable encontrado";
                msj.className = "text-[10px] font-bold mt-2 text-green-600 block italic";
            } else {
                // ERROR: No existe
                msj.innerText = "✗ El número de identificación no existe en la base de datos";
                msj.className = "text-[10px] font-bold mt-2 text-red-500 block italic";
                
                // Limpiar campos de nombre por si acaso
                document.getElementById('nombre_responsable').value = '';
            }
        })
        .catch(error => {
            msj.innerText = "⚠ Error de conexión con GITIC";
            msj.className = "text-[10px] font-bold mt-2 text-orange-500 block";
            console.error(error);
        });
}

function verificarPlacaRealTime() {
    const placa = document.getElementById('input-placa').value;
    const msj = document.getElementById('msj-placa');
    const card = document.getElementById('card-identificacion');
    const input = document.getElementById('input-placa');

    // Si el técnico borra la placa, reseteamos el visual
    if (placa.length < 1) {
        msj.classList.add('hidden');
        card.classList.remove('border-red-500', 'border-green-500', 'ring-4', 'ring-red-50', 'ring-green-50');
        input.classList.replace('text-red-600', 'text-[#39A900]');
        return;
    }

    // Ruta absoluta con prefijo /gitic
    fetch(`/gitic/dispositivos/verificar-placa/${placa}`)
        .then(res => res.json())
        .then(data => {
            if (data.exists) {
                // SEMÁFORO ROJO: Placa Duplicada
                msj.innerText = "✗ Esta placa ya está registrada en el inventario";
                msj.className = "text-[10px] font-bold mt-2 text-red-500 block italic uppercase tracking-tighter";
                card.classList.add('border-red-500', 'ring-4', 'ring-red-50');
                card.classList.remove('border-green-500', 'ring-green-50');
                input.classList.replace('text-[#39A900]', 'text-red-600');
            } else {
                // SEMÁFORO VERDE: Placa Disponible
                msj.innerText = "✓ Placa disponible para registro";
                msj.className = "text-[10px] font-bold mt-2 text-green-600 block italic uppercase tracking-tighter";
                card.classList.add('border-green-500', 'ring-4', 'ring-green-50');
                card.classList.remove('border-red-500', 'ring-red-50');
                input.classList.replace('text-red-600', 'text-[#39A900]');
            }
        })
        .catch(err => console.error("Error de conexión GITIC:", err));
}

</script>
@endsection