@php
use App\Models\EquipoEnergia;
$v = fn($campo, $def = null) => old($campo,
    $equipo?->$campo instanceof \Carbon\Carbon
        ? $equipo->$campo->format('Y-m-d')
        : ($equipo?->$campo ?? $def)
);
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

{{-- ── ESPECIFICACIONES NOMINALES ───────────────────────────────────────────── --}}
<div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
    <h2 class="text-[10px] font-black text-[#39A900] uppercase tracking-widest mb-5 border-b pb-2">
        <i class="fas fa-bolt mr-2"></i> Especificaciones Nominales
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
            <label class="lbl">Potencia nominal (VA)</label>
            <input type="number" step="0.01" name="potencia_va" value="{{ $v('potencia_va') }}" class="inp" placeholder="3000">
        </div>
        <div>
            <label class="lbl">Potencia nominal (W)</label>
            <input type="number" step="0.01" name="potencia_w" value="{{ $v('potencia_w') }}" class="inp" placeholder="2700">
        </div>
        <div>
            <label class="lbl">Voltaje de entrada (V)</label>
            <input type="number" name="voltaje_entrada" value="{{ $v('voltaje_entrada') }}" class="inp" placeholder="120 / 220">
        </div>
        <div>
            <label class="lbl">Voltaje de salida (V)</label>
            <input type="number" name="voltaje_salida" value="{{ $v('voltaje_salida') }}" class="inp" placeholder="120 / 220">
        </div>
        <div>
            <label class="lbl">Frecuencia (Hz)</label>
            <select name="frecuencia" class="inp">
                <option value="60" {{ $v('frecuencia','60') == '60' ? 'selected' : '' }}>60 Hz</option>
                <option value="50" {{ $v('frecuencia') == '50' ? 'selected' : '' }}>50 Hz</option>
            </select>
        </div>
        <div>
            <label class="lbl">Factor de potencia</label>
            <input type="number" step="0.01" min="0" max="1" name="factor_de_potencia"
                   value="{{ $v('factor_de_potencia') }}" class="inp" placeholder="0.9">
        </div>
        <div>
            <label class="lbl">Tecnología</label>
            <input type="text" name="tecnologia" value="{{ $v('tecnologia') }}" class="inp"
                   placeholder="Online (doble conversión), Line-Interactive...">
        </div>
    </div>
</div>

{{-- ── MEDICIONES ACTUALES ──────────────────────────────────────────────────── --}}
<div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
    <h2 class="text-[10px] font-black text-[#39A900] uppercase tracking-widest mb-5 border-b pb-2">
        <i class="fas fa-chart-bar mr-2"></i> Mediciones Actuales
    </h2>
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        <div>
            <label class="lbl">Carga actual (%)</label>
            <input type="number" step="0.01" min="0" max="100" name="carga_actual_pct"
                   value="{{ $v('carga_actual_pct') }}" class="inp" placeholder="8">
        </div>
        <div>
            <label class="lbl">Potencia activa actual (kW)</label>
            <input type="number" step="0.001" name="potencia_activa_actual_kw"
                   value="{{ $v('potencia_activa_actual_kw') }}" class="inp" placeholder="0.2">
        </div>
        <div>
            <label class="lbl">Potencia aparente actual (kVA)</label>
            <input type="number" step="0.001" name="potencia_aparente_actual_kva"
                   value="{{ $v('potencia_aparente_actual_kva') }}" class="inp" placeholder="0.2">
        </div>
        <div>
            <label class="lbl">Batería actual (%)</label>
            <input type="number" step="0.01" min="0" max="100" name="bateria_actual_pct"
                   value="{{ $v('bateria_actual_pct') }}" class="inp" placeholder="100">
        </div>
        <div>
            <label class="lbl">Autonomía estimada (min)</label>
            <input type="number" name="autonomia_estimada_min"
                   value="{{ $v('autonomia_estimada_min') }}" class="inp" placeholder="332">
        </div>
        <div>
            <label class="lbl">Estado operativo</label>
            <input type="text" name="estado_operativo" value="{{ $v('estado_operativo') }}" class="inp"
                   placeholder="SAI correcto, Falla de batería...">
        </div>
    </div>
    <p class="text-[10px] text-gray-400 mt-3 italic">
        <i class="fas fa-info-circle mr-1"></i>
        Los datos nominales indican la capacidad del equipo; las mediciones actuales muestran su condición en campo.
    </p>
</div>

{{-- ── BATERÍAS Y RESPALDO ─────────────────────────────────────────────────── --}}
<div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100" id="seccion-baterias">
    <h2 class="text-[10px] font-black text-[#39A900] uppercase tracking-widest mb-5 border-b pb-2">
        <i class="fas fa-battery-three-quarters mr-2"></i> Baterías y Respaldo
    </h2>
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div>
            <label class="lbl">Tipo de batería</label>
            <input type="text" name="tipo_bateria" value="{{ $v('tipo_bateria') }}" class="inp"
                   placeholder="VRLA sellada, Litio...">
        </div>
        <div>
            <label class="lbl">Capacidad por batería (Ah)</label>
            <input type="number" step="0.01" name="capacidad_baterias_ah"
                   value="{{ $v('capacidad_baterias_ah') }}" class="inp" placeholder="9">
        </div>
        <div>
            <label class="lbl">Baterías internas</label>
            <input type="number" id="baterias-internas" name="baterias_internas"
                   value="{{ $v('baterias_internas') }}" class="inp" placeholder="6"
                   oninput="calcularTotalBaterias()">
        </div>
        <div>
            <label class="lbl">Baterías banco externo</label>
            <input type="number" id="baterias-banco" name="baterias_banco_externo"
                   value="{{ $v('baterias_banco_externo') }}" class="inp" placeholder="12"
                   oninput="calcularTotalBaterias()">
        </div>
        <div>
            <label class="lbl">Total instalado</label>
            <input type="number" id="baterias-total" name="_total_baterias"
                   value="{{ ($v('baterias_internas',0) + $v('baterias_banco_externo',0)) ?: '' }}"
                   class="inp bg-gray-100 text-gray-500 cursor-default" readonly placeholder="Auto">
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
        <div>
            <label class="lbl">Modelo del banco</label>
            <input type="text" name="modelo_banco" value="{{ $v('modelo_banco') }}" class="inp font-mono"
                   placeholder="9PXEBM72RT">
        </div>
        <div>
            <label class="lbl">Serial del banco</label>
            <input type="text" name="serial_banco" value="{{ $v('serial_banco') }}" class="inp font-mono"
                   placeholder="PA32T47YEP">
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
function calcularTotalBaterias() {
    const internas = parseInt(document.getElementById('baterias-internas')?.value) || 0;
    const banco    = parseInt(document.getElementById('baterias-banco')?.value)    || 0;
    const total    = document.getElementById('baterias-total');
    if (total) total.value = (internas + banco) > 0 ? (internas + banco) : '';
}
document.addEventListener('DOMContentLoaded', calcularTotalBaterias);
</script>
