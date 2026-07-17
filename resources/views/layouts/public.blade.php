<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SGSPI — SENA Regional Casanare')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800;900&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .sena-bg  { background-color: #39A900; }
        .sena-text{ color: #39A900; }
    </style>
    @stack('head')
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen flex flex-col">

    <header class="sena-bg text-white shadow-lg">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="bg-white p-2 rounded-xl shadow-inner">
                    <i class="fas fa-shield-alt sena-text text-xl"></i>
                </div>
                <div class="leading-none">
                    <p class="font-black text-xl tracking-tight italic uppercase">SGSPI</p>
                    <p class="text-[10px] font-bold opacity-75 uppercase tracking-widest">SENA Regional Casanare</p>
                </div>
            </div>
            <div class="text-[10px] font-black uppercase tracking-widest opacity-60">
                Sensibilización en Seguridad de la Información
            </div>
        </div>
    </header>

    <main class="flex-1 container mx-auto px-4 py-8">
        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 rounded-2xl px-5 py-4 flex items-center gap-3">
                <i class="fas fa-exclamation-circle text-red-500"></i>
                <span class="text-xs font-bold">{{ session('error') }}</span>
            </div>
        @endif
        @yield('content')
    </main>

    <footer class="bg-white border-t border-gray-100 py-5 mt-auto">
        <div class="container mx-auto px-6 text-center text-[10px] font-bold text-gray-400 uppercase tracking-widest">
            &copy; {{ date('Y') }} SENA Regional Casanare &mdash; Sistema de Gestión de Seguridad de la Información
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
