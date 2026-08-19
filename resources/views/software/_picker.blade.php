@php $p = $prefix ?? 'sw'; @endphp

<div class="relative mb-3">
    <input type="text" id="{{ $p }}-search" autocomplete="off"
           placeholder="Escribe para buscar software del catálogo DG..."
           oninput="swSearch('{{ $p }}', this.value)"
           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-bold focus:outline-none focus:border-indigo-400">
    <div id="{{ $p }}-dropdown"
         class="absolute top-full left-0 right-0 bg-white border border-gray-200 rounded-xl shadow-xl z-20 hidden max-h-56 overflow-y-auto divide-y divide-gray-50 mt-1">
    </div>
</div>
<p class="text-[10px] text-gray-400 mb-3">Solo software licenciado y libre del catálogo autorizado DG. Puedes agregar varios antes de registrar.</p>

<div id="{{ $p }}-list" class="hidden mb-4">
    <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">
        <i class="fas fa-check-double text-indigo-400 mr-1"></i> Software seleccionado:
    </p>
    <div id="{{ $p }}-items" class="space-y-1.5"></div>
</div>

<div>
    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Fecha de Instalación</label>
    <input type="date" id="{{ $p }}-fecha" name="fecha_instalacion"
           value="{{ date('Y-m-d') }}"
           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-bold focus:outline-none focus:border-indigo-400">
</div>
<div class="mt-4">
    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">
        Versión / Notas <span class="text-gray-300 font-normal normal-case">(opcional — aplica a todos los seleccionados)</span>
    </label>
    <input type="text" id="{{ $p }}-notas" name="version_notas"
           placeholder="Ej: v2025.1 — Ambiente 101..."
           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-bold focus:outline-none focus:border-indigo-400">
</div>
