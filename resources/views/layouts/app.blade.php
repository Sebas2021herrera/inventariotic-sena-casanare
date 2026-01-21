<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario Tecnológico - SENA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sena-bg { background-color: #39A900; }
        .sena-text { color: #39A900; }
    </style>
</head>
<body class="bg-gray-100 font-sans">

    <nav class="sena-bg text-white shadow-md">
        <div class="container mx-auto px-6 py-3 flex justify-between items-center">
            <div class="flex items-center">
                <i class="fas fa-boxes-stacked text-2xl mr-3"></i>
                <span class="font-bold text-xl tracking-wider">SENA | Regional Casanare</span>
            </div>
            <div class="space-x-4">
                <a href="{{ route('dispositivos.index') }}" class="hover:bg-white hover:text-green-700 px-3 py-2 rounded transition font-medium">
                    <i class="fas fa-desktop mr-1"></i> Principal
                </a>
            </div>
        </div>
    </nav>

    <main class="container mx-auto px-6 py-8">
        @yield('content')
    </main>

    <footer class="text-center py-6 text-gray-500 text-sm">
        &copy; {{ date('Y') }} - Gestión de Inventario Tecnológico SENA - Sebastian Herrera 
    </footer>

</body>
</html>