@php
use App\Models\EquipoEnergia;
$v = fn($campo, $def = null) => old($campo, $equipo?->$campo ?? $def);
@endphp

{{-- ── IDENTIFICACIÓN ──────────────────────────────────────────────────────── --}}
<div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
    <h2 class="text-[10px] font-black text-[#39A900] uppercase tracking-widest mb-5 border-b pb-2">
        <i class="fas fa-tag mr-2"></i> Identificación
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="lbl">Tipo de Equipo *</label>
            <select name="tipo" required id="select-tipo"
                    class="inp" onchange="toggleCamposUPS()">
                <option value="">— Selecciona —</option>
                @foreach(EquipoEnergia::TIPOS as $t)
                    <option value="{{ $t }}" {{ $v('tipo') === $t ? 'selected' : '' }}>{{ $t }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="lbl">Marca *</label>
            <input type="text" name="marca" value="{{ $v('marca') }}" required class="inp" placeholder="APC, Eaton, Socomec...">
        </div>
        <div>
            <label class="lbl">Modelo *</label>
            <input type="text" name="modelo" value="{{ $v('modelo') }}" required class="inp" placeholder="Smart-UPS 1500, etc.">
        </div>
        <div>
            <label class="lbl">Número de Serie</label>
            <input type="text" name="numero_serie" value="{{ $v('numero_serie') }}" class="inp font-mono" placeholder="S/N de fábrica">
        </div>
        <div>
            <label class="lbl">Placa SENA / Activo</label>
            <input type="text" name="placa" value="{{ $v('placa') }}" class="inp font-mono uppercase"
                   oninput="this.value=this.value.toUpperCase()" placeholder="Placa o código interno">
        </div>
        <div>
            <label class="lbl">Pertenece a</label>
            <input type="text" name="pertenece" value="{{ $v('pertenece','SENA') }}" class="inp" placeholder="SENA, Arrendado...">
        </div>
        <div>
            <label class="lbl">Estado *</label>
            <select name="estado" required class="inp">
                @foreach(['Bueno','Regular','Malo','En Mantenimiento','Dado de Baja'] as $est)
                    <option value="{{ $est }}" {{ $v('estado','Bueno') === $est ? 'selected' : '' }}>{{ $est }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-center gap-3 pt-5">
            <input type="checkbox" name="marquillado" id="marquillado" value="1"
                   {{ $v('marquillado') ? 'checked' : '' }} class="accent-[#39A900] w-4 h-4">
            <label for="marquillado" class="text-sm font-bold text-gray-700 cursor-pointer">¿Marquillado?</label>
        </div>
        <div>
            <label class="lbl">Proveedor</label>
            <input type="text" name="proveedor" value="{{ $v('proveedor') }}" class="inp" placeholder="Nombre del proveedor">
        </div>
    </div>
</div>

{{-- ── UBICACIÓN ────────────────────────────────────────────────────────────── --}}
<div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
    <h2 class="text-[10px] font-black text-[#39A900] uppercase tracking-widest mb-5 border-b pb-2">
        <i class="fas fa-map-marker-alt mr-2"></i> Ubicación Física
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="lbl">Sede</label>
            <select name="sede_id" class="inp">
                <option value="">— Sin sede —</option>
                @foreach($sedes as $id => $nombre)
                    <option value="{{ $id }}" {{ $v('sede_id') == $id ? 'selected' : '' }}>{{ $nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="md:col-span-2">
            <label class="lbl">Cuarto / Sala *</label>
            <input type="text" name="cuarto" value="{{ $v('cuarto') }}" required class="inp"
                   placeholder="Ej: Cuarto de comunicaciones, Data Center, IDF Bloque A">
        </div>
    </div>
</div>

{{-- ── ESPECIFICACIONES ELÉCTRICAS ─────────────────────────────────────────── --}}
<div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
    <h2 class="text-[10px] font-black text-[#39A900] uppercase tracking-widest mb-5 border-b pb-2">
        <i class="fas fa-bolt mr-2"></i> Especificaciones Eléctricas
    </h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div>
            <label class="lbl">Fase</label>
            <select name="fase" class="inp">
                <option value="">— N/A —</option>
                @foreach(['Monofásica','Bifásica','Trifásica'] as $f)
                    <option value="{{ $f }}" {{ $v('fase') === $f ? 'selected' : '' }}>{{ $f }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="lbl">Potencia (VA)</label>
            <input type="number" step="0.01" name="potencia_va" value="{{ $v('potencia_va') }}" class="inp" placeholder="1500">
        </div>
        <div>
            <label class="lbl">Potencia (W)</label>
            <input type="number" step="0.01" name="potencia_w" value="{{ $v('potencia_w') }}" class="inp" placeholder="1050">
        </div>
        <div>
            <label class="lbl">Voltaje Entrada (V)</label>
            <input type="number" name="voltaje_entrada" value="{{ $v('voltaje_entrada') }}" class="inp" placeholder="120 / 220">
        </div>
        <div>
            <label class="lbl">Voltaje Salida (V)</label>
            <input type="number" name="voltaje_salida" value="{{ $v('voltaje_salida') }}" class="inp" placeholder="120 / 220">
        </div>
        <div>
            <label class="lbl">Capacidad Salida (VA)</label>
            <input type="number" step="0.01" name="capacidad_va" value="{{ $v('capacidad_va') }}" class="inp" placeholder="1500">
        </div>
        <div>
            <label class="lbl">Capacidad Salida (W)</label>
            <input type="number" step="0.01" name="capacidad_w" value="{{ $v('capacidad_w') }}" class="inp" placeholder="1050">
        </div>
        <div>
            <label class="lbl">Capacidad (A)</label>
            <input type="number" step="0.01" name="capacidad_a" value="{{ $v('capacidad_a') }}" class="inp" placeholder="10">
        </div>
        <div>
            <label class="lbl">Cap. Conmutación (A)</label>
            <input type="number" step="0.01" name="capacidad_conmutacion_a" value="{{ $v('capacidad_conmutacion_a') }}" class="inp" placeholder="40">
        </div>
        <div>
            <label class="lbl">Frecuencia (Hz)</label>
            <select name="frecuencia" class="inp">
                <option value="60" {{ $v('frecuencia','60') == '60' ? 'selected' : '' }}>60 Hz</option>
                <option value="50" {{ $v('frecuencia') == '50' ? 'selected' : '' }}>50 Hz</option>
            </select>
        </div>
    </div>
</div>

{{-- ── BATERÍA Y RESPALDO (solo UPS / Planta) ──────────────────────────────── --}}
<div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100" id="seccion-baterias">
    <h2 class="text-[10px] font-black text-[#39A900] uppercase tracking-widest mb-5 border-b pb-2">
        <i class="fas fa-battery-three-quarters mr-2"></i> Batería y Respaldo
    </h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div>
            <label class="lbl">Capacidad Batería (Ah)</label>
            <input type="number" step="0.01" name="capacidad_baterias_ah" value="{{ $v('capacidad_baterias_ah') }}" class="inp" placeholder="7.2">
        </div>
        <div>
            <label class="lbl">Número de Baterías</label>
            <input type="number" name="numero_baterias" value="{{ $v('numero_baterias') }}" class="inp" placeholder="1">
        </div>
        <div>
            <label class="lbl">Tiempo Respaldo Nominal (min)</label>
            <input type="number" name="tiempo_respaldo_min" value="{{ $v('tiempo_respaldo_min') }}" class="inp" placeholder="8">
        </div>
        <div>
            <label class="lbl">Tiempo Respaldo Verificado (min)</label>
            <input type="number" name="tiempo_respaldo_verificado_min" value="{{ $v('tiempo_respaldo_verificado_min') }}" class="inp" placeholder="6">
        </div>
        <div id="campo-tecnologia-ups" class="{{ $v('tipo') === 'UPS' ? '' : 'hidden' }}">
            <label class="lbl">Tecnología UPS</label>
            <select name="tecnologia_ups" class="inp">
                <option value="">— Selecciona —</option>
                @foreach(['Online (doble conversión)','Offline (standby)','Line-Interactive'] as $tech)
                    <option value="{{ $tech }}" {{ $v('tecnologia_ups') === $tech ? 'selected' : '' }}>{{ $tech }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

{{-- ── FECHAS Y GESTIÓN ─────────────────────────────────────────────────────── --}}
<div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
    <h2 class="text-[10px] font-black text-[#39A900] uppercase tracking-widest mb-5 border-b pb-2">
        <i class="fas fa-calendar-alt mr-2"></i> Fechas y Gestión
    </h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div>
            <label class="lbl">Fecha de Instalación</label>
            <input type="date" name="fecha_instalacion" value="{{ $v('fecha_instalacion') }}" class="inp">
        </div>
        <div>
            <label class="lbl">Último Mantenimiento</label>
            <input type="date" name="fecha_ultimo_mantenimiento" value="{{ $v('fecha_ultimo_mantenimiento') }}" class="inp">
        </div>
        <div>
            <label class="lbl">Próximo Mantenimiento</label>
            <input type="date" name="proximo_mantenimiento" value="{{ $v('proximo_mantenimiento') }}" class="inp">
        </div>
        <div>
            <label class="lbl">Garantía Hasta</label>
            <input type="date" name="garantia_hasta" value="{{ $v('garantia_hasta') }}" class="inp">
        </div>
    </div>
</div>

{{-- ── OBSERVACIONES ────────────────────────────────────────────────────────── --}}
<div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
    <h2 class="text-[10px] font-black text-[#39A900] uppercase tracking-widest mb-3 border-b pb-2">
        <i class="fas fa-comment-alt mr-2"></i> Observaciones
    </h2>
    <textarea name="observaciones" rows="4"
              class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-[#39A900] resize-none"
              placeholder="Estado de las baterías, novedades, historial relevante...">{{ $v('observaciones') }}</textarea>
</div>

<style>
.lbl { display:block; font-size:.625rem; font-weight:900; color:#9ca3af; text-transform:uppercase; margin-bottom:.25rem; }
.inp { width:100%; background:#f9fafb; border:1px solid #e5e7eb; border-radius:.75rem; padding:.625rem .75rem; font-size:.875rem; outline:none; }
.inp:focus { ring: 2px solid #39A900; }
</style>

<script>
function toggleCamposUPS() {
    const tipo = document.getElementById('select-tipo')?.value;
    const campoTech = document.getElementById('campo-tecnologia-ups');
    if (campoTech) {
        campoTech.classList.toggle('hidden', tipo !== 'UPS');
    }
}
document.addEventListener('DOMContentLoaded', toggleCamposUPS);
</script>
