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
                                <button type="button" onclick="buscarResponsable()" class="bg-blue-600 text-white px-4 rounded-xl hover:bg-blue-700 transition" title="Buscar responsable existente">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                            <p id="msg-responsable" class="text-[10px] mt-1 font-bold"></p>
                        </div>

                        <div id="campos-responsable" class="space-y-4">
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
                                        <option value="Contratista" {{ old('tipo_funcionario') == 'Contratista' ? 'selected' : '' }}>Contratista</option>
                                        <option value="Planta" {{ old('tipo_funcionario') == 'Planta' ? 'selected' : '' }}>Planta</option>
                                        <option value="Aprendiz" {{ old('tipo_funcionario') == 'Aprendiz' ? 'selected' : '' }}>Aprendiz</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Dependencia / Oficina</label>
                                <input type="text" id="dependencia" name="dependencia" value="{{ old('dependencia') }}" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3" placeholder="Ej: Oficina TIC">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Cargo</label>
                                <input type="text" id="cargo" name="cargo" value="{{ old('cargo') }}" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3" placeholder="Ej: Instructor">
                            </div>
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
                            <input type="text" id="input-sede" name="sede" list="listado-sedes" value="{{ old('sede') }}" placeholder="Seleccione o escriba la sede..." class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-green-500" required autocomplete="off">
                            <datalist id="listado-sedes">
                                <option value="Yopal">
                                <option value="Paz de Ariporo">
                                <option value="Monterrey">
                                <option value="Aguazul">
                                <option value="Villanueva">
                            </datalist>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Bloque</label>
                                <input type="text" name="bloque" value="{{ old('bloque') }}" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Ambiente / Aula *</label>
                                <input type="text" id="input-ambiente" name="ambiente" list="listado-ambientes" value="{{ old('ambiente') }}" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3" required>
                                <datalist id="listado-ambientes"></datalist>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 space-y-6">
                
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <div class="flex items-center mb-6 text-[#39A900] font-black uppercase text-xs tracking-widest border-b pb-2">
                        <i class="fas fa-desktop mr-2"></i> Identificación y Clasificación
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Placa SENA *</label>
                            <input type="text" name="placa" value="{{ old('placa') }}" class="w-full bg-white border-[#39A900] border-2 rounded-xl p-3 font-black text-xl text-[#39A900] shadow-inner" required>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Serial de Fábrica *</label>
                            <input type="text" name="serial" value="{{ old('serial') }}" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 font-mono uppercase" required>
                        </div>

                        <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-4 bg-gray-50 p-4 rounded-xl border border-gray-100">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Propietario</label>
                                <select name="propietario" class="w-full bg-white border-gray-200 rounded-lg p-2 text-sm font-bold">
                                    <option value="SENA" {{ old('propietario') == 'SENA' ? 'selected' : '' }}>SENA</option>
                                    <option value="TELEFONICA" {{ old('propietario') == 'TELEFONICA' ? 'selected' : '' }}>TELEFÓNICA</option>
                                    <option value="OTRO" {{ old('propietario') == 'OTRO' ? 'selected' : '' }}>OTRO</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Función</label>
                                <select name="funcion" class="w-full bg-white border-gray-200 rounded-lg p-2 text-sm font-bold">
                                    <option value="FORMACION" {{ old('funcion') == 'FORMACION' ? 'selected' : '' }}>FORMACIÓN</option>
                                    <option value="ADMINISTRATIVO" {{ old('funcion') == 'ADMINISTRATIVO' ? 'selected' : '' }}>ADMINISTRATIVO</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">¿Registrado en INTUNE?</label>
                                <select name="en_intune" class="w-full bg-white border-gray-200 rounded-lg p-2 text-sm font-bold">
                                    <option value="NO" {{ old('en_intune') == 'NO' ? 'selected' : '' }}>NO (Pendiente)</option>
                                    <option value="SI" {{ old('en_intune') == 'SI' ? 'selected' : '' }}>SÍ (Registrado)</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Marca</label>
                                <input type="text" name="marca" value="{{ old('marca') }}" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Modelo</label>
                                <input type="text" name="modelo" value="{{ old('modelo') }}" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Categoría</label>
                                <select name="categoria" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 font-medium">
                                    <option value="computo">Computadores</option>
                                    <option value="impresoras">Impresoras / Escáner</option>
                                    <option value="conectividad">Redes / Conectividad</option>
                                    <option value="energia">Energía (UPS/Reguladores)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Estado Físico</label>
                                <select name="estado_fisico" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 font-bold">
                                    <option value="Bueno" class="text-green-600">Bueno</option>
                                    <option value="Regular" class="text-yellow-600">Regular</option>
                                    <option value="Malo" class="text-red-600">Malo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <div class="flex items-center mb-6 text-[#39A900] font-black uppercase text-xs tracking-widest border-b pb-2">
                        <i class="fas fa-microchip mr-2"></i> Periféricos (Si aplica)
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach(['Monitor', 'Teclado', 'Mouse', 'Cargador'] as $periferico)
                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                            <h4 class="text-[10px] font-black text-gray-400 uppercase mb-3 flex items-center">
                                <span class="w-2 h-2 bg-[#39A900] rounded-full mr-2"></span> {{ $periferico }}
                            </h4>
                            <div class="grid grid-cols-2 gap-2">
                                <input type="text" name="perifericos[{{ $periferico }}][placa]" placeholder="Placa" class="text-[10px] p-2 rounded-lg border-gray-200">
                                <input type="text" name="perifericos[{{ $periferico }}][serial]" placeholder="Serial" class="text-[10px] p-2 rounded-lg border-gray-200">
                                <input type="text" name="perifericos[{{ $periferico }}][marca]" placeholder="Marca" class="text-[10px] p-2 rounded-lg border-gray-200">
                                <input type="text" name="perifericos[{{ $periferico }}][modelo]" placeholder="Modelo" class="text-[10px] p-2 rounded-lg border-gray-200">
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <div class="flex items-center mb-6 text-[#39A900] font-black uppercase text-xs tracking-widest border-b pb-2">
                        <i class="fas fa-server mr-2"></i> Especificaciones del Sistema
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Procesador</label>
                            <input type="text" name="procesador" value="{{ old('procesador') }}" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm" placeholder="Ej: Core i7 12va Gen">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Memoria RAM</label>
                            <input type="text" name="ram" value="{{ old('ram') }}" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm" placeholder="Ej: 16 GB DDR4">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Sistema Operativo</label>
                            <input type="text" name="so" value="{{ old('so') }}" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm" placeholder="Ej: Windows 11 Pro">
                        </div>
                        <div class="grid grid-cols-2 gap-3 md:col-span-2">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Tipo de Disco</label>
                                <input type="text" name="tipo_disco" value="{{ old('tipo_disco') }}" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm" placeholder="SSD / NVMe / HDD">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Capacidad Disco</label>
                                <input type="text" name="capacidad_disco" value="{{ old('capacidad_disco') }}" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm" placeholder="Ej: 512 GB">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">MAC Address</label>
                            <input type="text" name="mac_address" value="{{ old('mac_address') }}" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 font-mono text-sm uppercase" placeholder="00:00:00:00:00:00">
                        </div>
                        <div class="md:col-span-3">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Novedades u Observaciones</label>
                            <textarea name="observaciones" rows="3" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm">{{ old('observaciones') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit" class="bg-[#39A900] text-white px-16 py-5 rounded-2xl font-black uppercase tracking-widest shadow-2xl hover:scale-105 transition-transform active:scale-95 flex items-center">
                        <i class="fas fa-save mr-3 text-xl"></i> Registrar en Sistema
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function buscarResponsable() {
    const cedula = document.getElementById('cedula').value;
    const msg = document.getElementById('msg-responsable');
    if (!cedula) { msg.innerText = "Ingrese una cédula."; return; }
    msg.innerText = "Consultando...";
    fetch(`/responsables/buscar/${cedula}`)
        .then(response => response.json())
        .then(data => {
            if (data && data.id) {
                document.getElementById('nombre_responsable').value = data.nombre || '';
                document.getElementById('numero_de_celular').value = data.numero_de_celular || '';
                document.getElementById('tipo_funcionario').value = data.tipo_funcionario || 'Contratista';
                document.getElementById('dependencia').value = data.dependencia || '';
                document.getElementById('cargo').value = data.cargo || '';
                msg.innerText = "Responsable encontrado.";
                msg.className = "text-[10px] mt-1 text-green-600 font-black";
            } else {
                msg.innerText = "Nuevo responsable.";
                msg.className = "text-[10px] mt-1 text-orange-600 font-bold";
            }
        });
}
</script>
@endsection