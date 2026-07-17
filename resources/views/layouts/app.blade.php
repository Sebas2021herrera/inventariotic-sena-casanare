<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'GITIC') }} - Regional Casanare</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap');
        
        body { font-family: 'Inter', sans-serif; }
        .sena-bg { background-color: #39A900; }
        .sena-text { color: #39A900; }
        .nav-link:hover { background-color: rgba(255, 255, 255, 0.15); }
        /* Clase para resaltar el link activo */
        .nav-active { background-color: rgba(255, 255, 255, 0.25); border: 1px solid rgba(255, 255, 255, 0.3); }
    </style>
<script>
/**
 * Normalización de campos en el front-end.
 * Complementa los mutators del modelo — primera línea de defensa.
 */

// MAYÚSCULAS: bloque, ambiente
function normalizarMayusculas(input) {
    const pos = input.selectionStart;
    input.value = input.value.toUpperCase();
    input.setSelectionRange(pos, pos);
}

// Title Case: sede (Yopal, Paz De Ariporo)
function normalizarTitleCase(input) {
    const pos = input.selectionStart;
    input.value = input.value
        .toLowerCase()
        .replace(/(?:^|\s)\S/g, c => c.toUpperCase());
    input.setSelectionRange(pos, pos);
}

// Tarjetas de nivel SENA (radio cards) — actualiza estilos al cambiar selección
function actualizarNivelSena(radio) {
    const grupo = radio.closest('#grupo-nivel-sena');
    if (!grupo) return;

    const colores = { red:'red', orange:'orange', blue:'blue', gray:'gray' };
    const activoClases   = (c) => [`border-${c}-400`, `bg-${c}-50`];
    const inactivoClases = ['border-gray-200', 'bg-white', 'hover:border-gray-300'];

    grupo.querySelectorAll('.nivel-card').forEach(function (card) {
        const c = card.dataset.color || 'gray';
        // Quita clases activas anteriores
        activoClases(c).forEach(cls => card.classList.remove(cls));
        inactivoClases.forEach(cls => card.classList.remove(cls));
        inactivoClases.forEach(cls => card.classList.add(cls));
    });

    // Activa la tarjeta seleccionada
    const cardActiva = radio.closest('.nivel-card');
    if (cardActiva) {
        const c = cardActiva.dataset.color || 'gray';
        inactivoClases.forEach(cls => cardActiva.classList.remove(cls));
        activoClases(c).forEach(cls => cardActiva.classList.add(cls));
    }
}

// Normaliza todos los campos marcados antes de enviar cualquier form
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('form').forEach(function (form) {
        form.addEventListener('submit', function () {
            form.querySelectorAll('[data-normalize="upper"]').forEach(function (el) {
                el.value = el.value.toUpperCase().trim();
            });
            form.querySelectorAll('[data-normalize="title"]').forEach(function (el) {
                el.value = el.value.toLowerCase()
                    .replace(/(?:^|\s)\S/g, c => c.toUpperCase()).trim();
            });
        });
    });
});
</script>
</head>
<body class="bg-gray-50 text-gray-800">

    <nav class="sena-bg text-white shadow-lg border-b border-white/10">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            
            <div class="flex items-center space-x-3">
                <div class="bg-white p-2 rounded-lg shadow-inner">
                    <i class="fas fa-network-wired sena-text text-xl"></i>
                </div>
                <div class="flex flex-col leading-none">
                    <span class="font-black text-2xl tracking-tighter italic uppercase">GITIC</span>
                    <span class="text-[10px] font-bold opacity-80 uppercase tracking-widest">Regional Casanare</span>
                </div>
            </div>

            <div class="flex items-center space-x-2">
                @auth
                    <a href="{{ route('dashboard') }}" class="nav-link px-4 py-2 rounded-xl transition text-xs font-black uppercase tracking-widest flex items-center {{ request()->routeIs('dashboard') ? 'nav-active' : '' }}">
                        <i class="fas fa-home mr-2"></i> Inicio
                    </a>
                    <a href="{{ route('dispositivos.index') }}" class="nav-link px-4 py-2 rounded-xl transition text-xs font-black uppercase tracking-widest flex items-center {{ request()->routeIs('dispositivos.*') ? 'nav-active' : '' }}">
                        <i class="fas fa-layer-group mr-2"></i> Inventario
                    </a>

                    <a href="{{ route('reportes.index') }}" class="nav-link px-4 py-2 rounded-xl transition text-xs font-black uppercase tracking-widest flex items-center {{ request()->routeIs('reportes.*') ? 'nav-active' : '' }}">
                        <i class="fas fa-chart-pie mr-2"></i> Reportes
                    </a>

                    <a href="{{ route('equipos-energia.index') }}" class="nav-link px-4 py-2 rounded-xl transition text-xs font-black uppercase tracking-widest flex items-center {{ request()->routeIs('equipos-energia.*') ? 'nav-active' : '' }}">
                        <i class="fas fa-bolt mr-2"></i> Energía
                    </a>
                    <a href="{{ route('areas-seguras.index') }}" class="nav-link px-4 py-2 rounded-xl transition text-xs font-black uppercase tracking-widest flex items-center {{ request()->routeIs('areas-seguras.*') ? 'nav-active' : '' }}">
                        <i class="fas fa-shield-alt mr-2"></i> ISO 27001
                    </a>

                    @if(Auth::user()->role === 'admin')
                    <a href="{{ route('usuarios.index') }}" class="nav-link px-4 py-2 rounded-xl transition text-xs font-black uppercase tracking-widest flex items-center {{ request()->routeIs('usuarios.*') ? 'nav-active' : '' }}">
                        <i class="fas fa-users-cog mr-2"></i> Usuarios
                    </a>
                    @endif

                    <div class="h-6 w-[1px] bg-white/20 mx-2"></div>

                    <div class="flex items-center space-x-4">
                        <div class="text-right hidden md:block">
                            <p class="text-[10px] font-black uppercase opacity-70 leading-none">Usuario</p>
                            <p class="text-xs font-bold">{{ Auth::user()->name }}</p>
                        </div>

                        <a href="{{ route('perfil.cambiar-clave') }}"
                            title="Cambiar contraseña"
                            class="bg-white/10 hover:bg-white/25 text-white px-3 py-2 rounded-xl transition shadow-sm flex items-center {{ request()->routeIs('perfil.*') ? 'nav-active' : '' }}">
                            <i class="fas fa-key text-sm"></i>
                        </a>

                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="bg-white/10 hover:bg-red-500 text-white px-3 py-2 rounded-xl transition shadow-sm flex items-center group">
                                <i class="fas fa-power-off group-hover:scale-110 transition-transform"></i>
                            </button>
                        </form>
                    </div>
                @else
                    <span class="text-[10px] font-black uppercase tracking-widest opacity-60">
                        Acceso Restringido
                    </span>
                @endauth
            </div>
        </div>
    </nav>

    <main class="container mx-auto px-6 py-10 min-h-[85vh]">
        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 rounded-2xl px-5 py-4 flex items-center gap-3">
                <i class="fas fa-exclamation-circle text-red-500 flex-shrink-0"></i>
                <span class="text-xs font-bold">{{ session('error') }}</span>
            </div>
        @endif
        @yield('content')
    </main>

    <footer class="bg-white border-t border-gray-100 py-8">
        <div class="container mx-auto px-6 flex flex-col md:flex-row justify-between items-center text-gray-400">
            <div class="text-[10px] font-black uppercase tracking-widest text-center md:text-left">
                &copy; {{ date('Y') }} - {{ config('app.name') }} | Gestión de Infraestructura TIC
            </div>
            <div class="mt-4 md:mt-0 text-[10px] font-bold opacity-60 italic">
                SENA Regional Casanare - Sebastian Herrera
            </div>
        </div>
    </footer>

</body>
</html>