@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto pb-20">
    @if ($errors->any())
        <div class="bg-red-50 border-l-8 border-red-500 p-4 mb-8 rounded-r-xl shadow-md">
            <div class="flex items-center mb-2">
                <i class="fas fa-exclamation-triangle text-red-500 mr-2 text-xl"></i>
                <h3 class="text-red-800 font-black uppercase text-sm">Campos obligatorios faltantes:</h3>
            </div>
            <ul class="text-red-700 text-[10px] list-disc list-inside font-bold">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="flex flex-col md:flex-row justify-between items-center mb-8 bg-white p-6 rounded-3xl shadow-sm border-l-8 border-[#39A900] gap-4">
        <div class="flex items-center">
            <div class="bg-[#39A900] p-3 rounded-2xl mr-4">
                <i class="fas fa-file-signature text-white text-2xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-black text-gray-800 uppercase italic tracking-tighter">
                    Reporte Técnico <span class="text-[#39A900]">GTI-F-132</span>
                </h1>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Versión 05 - Gestión de Tecnologías de la Información</p>
            </div>
        </div>
        <div class="text-center md:text-right">
            <p class="text-[10px] font-black text-gray-400 uppercase">Sede Actual</p>
            <p class="text-sm font-bold text-gray-700">{{ $dispositivo->ubicacion->sede }} - {{ $dispositivo->ubicacion->ambiente }}</p>
        </div>
    </div>

    <form action="{{ route('conceptos.store') }}" method="POST" id="form-gti">
        @csrf
        <input type="hidden" name="dispositivo_id" value="{{ $dispositivo->id }}">

        <div class="grid grid-cols-1 gap-8">
            
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="md:col-span-1">
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-3">Tipo de Equipo</label>
                        <div class="flex gap-4">
                            <label class="flex items-center text-xs font-bold text-gray-700 cursor-pointer">
                                <input type="radio" name="tipo_equipo" value="Administrativo" class="mr-2 text-[#39A900]" checked> Admin.
                            </label>
                            <label class="flex items-center text-xs font-bold text-gray-700 cursor-pointer">
                                <input type="radio" name="tipo_equipo" value="Formación" class="mr-2 text-[#39A900]"> Formación
                            </label>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Hostname (SENA-PLACA)</label>
                        <input type="text" name="hostname" value="{{ old('hostname', $sugerenciaHostname) }}" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 font-mono text-sm uppercase font-bold text-blue-700" required>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Fecha del Reporte *</label>
                        <input type="date" name="fecha_reporte" value="{{ old('fecha_reporte', date('Y-m-d')) }}" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm font-bold" required>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">N° INC / WO</label>
                        <input type="text" name="num_incidente" value="{{ old('num_incidente') }}" placeholder="INC / WO" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm">
                    </div>
                </div>
            </div>

            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                <h3 class="text-xs font-black text-[#39A900] uppercase mb-6 border-b pb-2 tracking-widest flex items-center">
                    <i class="fas fa-info-circle mr-2"></i> 1. Datos Básicos
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100">
                        <p class="text-[9px] font-black text-gray-400 uppercase mb-1">Funcionario / Usuario</p>
                        <p class="text-sm font-bold text-gray-800">{{ $dispositivo->responsable->nombre }}</p>
                        <p class="text-[10px] text-gray-500 font-medium">{{ $dispositivo->responsable->cedula }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Nombre Jefe Inmediato *</label>
                        <input type="text" name="jefe_inmediato" value="{{ old('jefe_inmediato') }}" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm" placeholder="Nombre completo del jefe de área" required>
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Descripción de la Solicitud *</label>
                        <textarea name="descripcion_solicitud" rows="2" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm" placeholder="Ej: Equipo presenta falla en... " required>{{ old('descripcion_solicitud') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                <h3 class="text-xs font-black text-[#39A900] uppercase mb-6 border-b pb-2 tracking-widest">2. Datos del Equipo Reportado</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-[10px] text-left">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 uppercase">
                                <th class="p-3">Componente</th>
                                <th class="p-3">Marca</th>
                                <th class="p-3">Modelo</th>
                                <th class="p-3">Serial</th>
                                <th class="p-3">Placa SENA</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr class="font-bold">
                                <td class="p-3 text-blue-700 uppercase">{{ $dispositivo->modelo }} (PC)</td>
                                <td class="p-3">{{ $dispositivo->marca }}</td>
                                <td class="p-3">{{ $dispositivo->modelo }}</td>
                                <td class="p-3 font-mono text-xs">{{ $dispositivo->serial }}</td>
                                <td class="p-3">{{ $dispositivo->placa }}</td>
                            </tr>
                            @foreach($dispositivo->perifericos as $p)
                            <tr class="text-gray-600">
                                <td class="p-3 uppercase">{{ $p->tipo }}</td>
                                <td class="p-3">{{ $p->marca ?? 'N/A' }}</td>
                                <td class="p-3">{{ $p->modelo ?? 'N/A' }}</td>
                                <td class="p-3 font-mono text-[9px]">{{ $p->serial ?? 'N/A' }}</td>
                                <td class="p-3">{{ $p->placa ?? 'N/A' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                <h3 class="text-xs font-black text-[#39A900] uppercase mb-6 border-b pb-2 tracking-widest">3. Software Base del Equipo</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @php
                        $softwares = [
                            'so' => ['label' => 'Edición S.O', 'default' => 'Windows 11 Pro', 'ver' => '24H2'],
                            'office' => ['label' => 'Edición Office', 'default' => 'Office 365 E3', 'ver' => '2025'],
                            'antivirus' => ['label' => 'Antivirus', 'default' => 'Windows Defender', 'ver' => 'Actualizado'],
                            'adobe' => ['label' => 'Adobe Reader', 'default' => 'Acrobat Reader DC', 'ver' => '24.0']
                        ];
                    @endphp
                    @foreach($softwares as $key => $s)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-2xl border border-gray-100">
                        <div class="w-1/3">
                            <p class="text-[9px] font-black text-gray-400 uppercase">{{ $s['label'] }}</p>
                            <input type="text" name="software_base[{{$key}}][nombre]" value="{{ old("software_base.$key.nombre", $s['default']) }}" class="text-xs font-bold bg-transparent border-none p-0 w-full focus:ring-0">
                        </div>
                        <div class="w-1/3 px-2">
                            <p class="text-[9px] font-black text-gray-400 uppercase text-center">Versión</p>
                            <input type="text" name="software_base[{{$key}}][version]" value="{{ old("software_base.$key.version", $s['ver']) }}" class="text-xs font-bold bg-transparent border-none p-0 w-full text-center focus:ring-0">
                        </div>
                        <div class="w-1/4 text-right">
                            <p class="text-[9px] font-black text-gray-400 uppercase">Licencia</p>
                            <select name="software_base[{{$key}}][licencia]" class="text-[10px] font-bold bg-transparent border-none text-[#39A900] focus:ring-0">
                                <option value="SI" {{ old("software_base.$key.licencia") == 'SI' ? 'selected' : '' }}>SI</option>
                                <option value="NO" {{ old("software_base.$key.licencia") == 'NO' ? 'selected' : '' }}>NO</option>
                            </select>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 border-b pb-2 gap-4">
                    <h3 class="text-xs font-black text-[#39A900] uppercase tracking-widest">4. Detalle de la Solicitud</h3>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" onclick="inyectarTexto('baja')" class="px-3 py-1 bg-red-50 text-red-600 rounded-lg text-[9px] font-black border border-red-100 hover:bg-red-100 transition uppercase">Baja</button>
                        <button type="button" onclick="inyectarTexto('preventivo')" class="px-3 py-1 bg-green-50 text-green-600 rounded-lg text-[9px] font-black border border-green-100 hover:bg-green-100 transition uppercase">Mtto</button>
                        <button type="button" onclick="inyectarTexto('hardware')" class="px-3 py-1 bg-orange-50 text-orange-600 rounded-lg text-[9px] font-black border border-orange-100 hover:bg-orange-100 transition uppercase">Hardware</button>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Flujo de Solicitud *</label>
                        <select name="flujo_solicitud" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 font-bold text-sm" required>
                            <option value="Concepto Técnico" {{ old('flujo_solicitud') == 'Concepto Técnico' ? 'selected' : '' }}>4. Concepto Técnico</option>
                            <option value="Garantía" {{ old('flujo_solicitud') == 'Garantía' ? 'selected' : '' }}>2. Garantía</option>
                            <option value="Repuesto" {{ old('flujo_solicitud') == 'Repuesto' ? 'selected' : '' }}>1. Repuesto</option>
                            <option value="Siniestro" {{ old('flujo_solicitud') == 'Siniestro' ? 'selected' : '' }}>3. Siniestro</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Tipo de Trámite *</label>
                        <select name="concepto_tipo" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 font-bold text-sm" required>
                            <option value="Asignación" {{ old('concepto_tipo') == 'Asignación' ? 'selected' : '' }}>Asignación</option>
                            <option value="Reasignación" {{ old('concepto_tipo') == 'Reasignación' ? 'selected' : '' }}>Reasignación</option>
                            <option value="Baja" {{ old('concepto_tipo') == 'Baja' ? 'selected' : '' }}>Baja / Renovación</option>
                            <option value="Reparación" {{ old('concepto_tipo') == 'Reparación' ? 'selected' : '' }}>Reparación</option>
                        </select>
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Diagnóstico Técnico Detallado *</label>
                        <textarea id="diagnostico_tecnico" name="diagnostico_tecnico" rows="5" class="w-full bg-gray-50 border-gray-200 rounded-xl p-4 text-sm font-mono leading-relaxed" required>{{ old('diagnostico_tecnico') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Causas del Daño / Falla *</label>
                            <textarea name="causas_daño" rows="3" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm" placeholder="Origen de la falla..." required>{{ old('causas_daño') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Recomendaciones Técnicas *</label>
                            <textarea name="recomendacion" rows="3" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm" placeholder="Sugerencias técnicas..." required>{{ old('recomendacion') }}</textarea>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Nombre Técnico Responsable</label>
                            <input type="text" name="tecnico_nombre" value="{{ Auth::user()->name }}" class="w-full bg-gray-100 border-gray-200 rounded-xl p-3 text-sm font-bold shadow-inner" readonly>
                        </div>
                        <div class="flex items-center">
                            <label class="flex items-center cursor-pointer bg-gray-50 p-3 rounded-2xl border border-gray-100 w-full mt-4">
                                <input type="checkbox" name="requiere_contingencia" class="w-5 h-5 text-[#39A900]" {{ old('requiere_contingencia') ? 'checked' : '' }}>
                                <span class="ml-3 text-[10px] font-black text-gray-600 uppercase">¿Requiere Contingencia?</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end items-center gap-6">
                <a href="{{ route('dispositivos.show', $dispositivo) }}" class="text-xs font-black text-gray-400 uppercase hover:text-gray-600 transition">Cancelar</a>
                <button type="submit" class="bg-[#39A900] text-white px-16 py-5 rounded-2xl font-black uppercase text-xs tracking-widest shadow-2xl hover:scale-105 transition-all flex items-center">
                    <i class="fas fa-save mr-3 text-xl"></i> GUARDAR Y GENERAR REPORTE
                </button>
            </div>
        </div>
    </form>
</div>



<script>
    function inyectarTexto(tipo) {
        const textarea = document.getElementById('diagnostico_tecnico');
        const plantillas = {
            'baja': `SE REALIZA REVISIÓN TÉCNICA AL EQUIPO E IDENTIFICACIÓN DE COMPONENTES.\nDIAGNÓSTICO: EL DISPOSITIVO PRESENTA OBSOLESCENCIA TECNOLÓGICA DE ACUERDO CON LOS LINEAMIENTOS DE LA ENTIDAD.\nHARDWARE: PROCESADOR Y MEMORIA RAM LIMITADOS PARA LAS APLICACIONES INSTITUCIONALES ACTUALES.`,
            'preventivo': `SE REALIZA MANTENIMIENTO PREVENTIVO AL EQUIPO:\n1. LIMPIEZA INTERNA DE VENTILADORES Y COMPONENTES.\n2. OPTIMIZACIÓN LÓGICA Y ACTUALIZACIÓN DE SOFTWARE BASE.`,
            'hardware': `SE DETECTA FALLA FÍSICA EN [ESPECIFICAR PARTE].\nPRUEBAS: SE VERIFICA QUE EL COMPONENTE NO RESPONDE A PRUEBAS DE POST Y BIOS.`
        };
        if (textarea.value.trim() !== "" && !confirm("¿Reemplazar diagnóstico?")) return;
        textarea.value = plantillas[tipo];
    }
</script>
@endsection