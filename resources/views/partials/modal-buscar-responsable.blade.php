{{-- ── Modal búsqueda de responsable por nombre ────────────────────────────── --}}
<div id="modal-responsable"
     class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4"
     onclick="if(event.target===this) cerrarModalResponsable()">

    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg flex flex-col"
         style="max-height:90vh;">

        {{-- Cabecera --}}
        <div class="sena-bg px-6 py-4 rounded-t-3xl flex justify-between items-center flex-shrink-0">
            <div>
                <h2 class="text-white font-black text-base uppercase tracking-tight flex items-center gap-2">
                    <i class="fas fa-users text-sm opacity-80"></i> Buscar Responsable
                </h2>
                <p class="text-white/70 text-[10px] font-bold mt-0.5">Busca por nombre o número de cédula</p>
            </div>
            <button type="button" onclick="cerrarModalResponsable()"
                    class="text-white/60 hover:text-white transition text-xl leading-none">
                <i class="fas fa-times"></i>
            </button>
        </div>

        {{-- Input de búsqueda --}}
        <div class="px-5 py-4 border-b border-gray-100 flex-shrink-0">
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-3.5 text-gray-300 text-sm"></i>
                <input type="text" id="modal-input-busqueda"
                       placeholder="Ej: María López o 1000123456"
                       oninput="debounceBuscarResponsable(this.value)"
                       autocomplete="off"
                       class="w-full bg-gray-50 rounded-2xl py-3 pl-9 pr-4 text-sm outline-none focus:ring-2 focus:ring-[#39A900] transition">
            </div>
        </div>

        {{-- Resultados --}}
        <div id="modal-resultados-container" class="overflow-y-auto flex-1">

            <div id="modal-estado-inicial" class="py-12 text-center text-gray-400">
                <i class="fas fa-search text-3xl mb-3 block opacity-20"></i>
                <p class="text-xs font-bold uppercase tracking-widest">Escribe al menos 2 caracteres</p>
            </div>

            <div id="modal-spinner" class="hidden py-10 flex items-center justify-center gap-2">
                <div class="w-5 h-5 border-2 border-[#39A900] border-t-transparent rounded-full animate-spin"></div>
                <span class="text-xs font-bold text-gray-400 uppercase">Buscando...</span>
            </div>

            <div id="modal-sin-resultados" class="hidden py-12 text-center text-gray-400">
                <i class="fas fa-user-slash text-3xl mb-3 block opacity-20"></i>
                <p class="text-xs font-bold uppercase tracking-widest">Sin coincidencias</p>
                <p class="text-[10px] text-gray-400 mt-1">Verifica el nombre o registra el responsable primero</p>
            </div>

            <ul id="modal-lista-resultados" class="divide-y divide-gray-50"></ul>

        </div>

        {{-- Pie --}}
        <div class="px-5 py-3 bg-gray-50 rounded-b-3xl flex-shrink-0 border-t border-gray-100">
            <p class="text-[10px] text-gray-400 font-bold">
                <i class="fas fa-info-circle mr-1"></i>
                Haz clic en un resultado para seleccionar el responsable
            </p>
        </div>
    </div>
</div>

<script>
const _urlBuscarNombre = "{{ route('responsables.buscar-nombre') }}";
let _debounceTimer = null;

function abrirModalResponsable() {
    const modal = document.getElementById('modal-responsable');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    setTimeout(() => document.getElementById('modal-input-busqueda').focus(), 80);
    _resetModal();
}

function cerrarModalResponsable() {
    const modal = document.getElementById('modal-responsable');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.getElementById('modal-input-busqueda').value = '';
    _resetModal();
}

function _resetModal() {
    _mostrarEstado('inicial');
    document.getElementById('modal-lista-resultados').innerHTML = '';
}

function _mostrarEstado(estado) {
    ['modal-estado-inicial','modal-spinner','modal-sin-resultados','modal-lista-resultados']
        .forEach(id => document.getElementById(id)?.classList.add('hidden'));

    if (estado === 'inicial')     document.getElementById('modal-estado-inicial')?.classList.remove('hidden');
    if (estado === 'cargando')    document.getElementById('modal-spinner')?.classList.remove('hidden');
    if (estado === 'vacio')       document.getElementById('modal-sin-resultados')?.classList.remove('hidden');
    if (estado === 'resultados')  document.getElementById('modal-lista-resultados')?.classList.remove('hidden');
}

function debounceBuscarResponsable(q) {
    clearTimeout(_debounceTimer);
    if (q.trim().length < 2) { _resetModal(); return; }
    _mostrarEstado('cargando');
    _debounceTimer = setTimeout(() => _ejecutarBusqueda(q.trim()), 300);
}

function _ejecutarBusqueda(q) {
    fetch(`${_urlBuscarNombre}?q=${encodeURIComponent(q)}`)
        .then(r => r.json())
        .then(data => {
            const lista = document.getElementById('modal-lista-resultados');
            lista.innerHTML = '';

            if (!data.length) { _mostrarEstado('vacio'); return; }

            data.forEach(r => {
                const li = document.createElement('li');
                li.innerHTML = `
                    <button type="button"
                            onclick="_elegirResponsable(${JSON.stringify(r).replace(/"/g,'&quot;')})"
                            class="w-full text-left px-5 py-3 hover:bg-green-50 transition group">
                        <div class="font-black text-gray-800 text-sm group-hover:text-[#39A900] transition">${r.nombre}</div>
                        <div class="text-[10px] font-mono text-gray-400 mt-0.5">
                            CC ${r.cedula}
                            <span class="text-gray-300 mx-1">·</span>
                            ${r.cargo ?? ''}
                            ${r.dependencia ? `<span class="text-gray-300 mx-1">·</span>${r.dependencia}` : ''}
                        </div>
                    </button>`;
                lista.appendChild(li);
            });

            _mostrarEstado('resultados');
        })
        .catch(() => _mostrarEstado('vacio'));
}

function _elegirResponsable(resp) {
    if (typeof window.seleccionarDesdeModal === 'function') {
        window.seleccionarDesdeModal(resp);
    }
    cerrarModalResponsable();
}
</script>
