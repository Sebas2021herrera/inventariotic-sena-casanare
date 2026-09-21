<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Módulo de Juegos — SGSPI · SENA Regional Casanare</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;700;800;900&display=swap');
        body { font-family: 'Inter', sans-serif; background: #f1f5f9; }
        @media print {
            .no-print  { display: none !important; }
            body       { background: white; }
            .card      { box-shadow: none !important; border: 1px solid #e5e7eb !important; }
            .page-break{ page-break-after: always; }
        }
    </style>
</head>
<body class="min-h-screen p-4 md:p-8">

    {{-- Barra de acciones (no se imprime) --}}
    <div class="no-print flex items-center justify-center gap-3 mb-6">
        <button onclick="window.print()"
            class="flex items-center gap-2 bg-[#39A900] text-white font-black px-5 py-2.5 rounded-xl text-sm uppercase tracking-widest shadow hover:bg-green-700 transition">
            <i class="fas fa-print"></i> Imprimir
        </button>
        <a href="{{ route('sgspi.index') }}"
            class="flex items-center gap-2 bg-white text-gray-700 font-black px-5 py-2.5 rounded-xl text-sm uppercase tracking-widest shadow hover:bg-gray-50 transition border border-gray-200">
            <i class="fas fa-arrow-left"></i> Volver al módulo
        </a>
        <span class="text-xs text-gray-400 font-bold hidden md:block">El QR apunta a: <strong>{{ $url }}</strong></span>
    </div>

    {{-- ══ TARJETA PRINCIPAL ══════════════════════════════════════════════════ --}}
    <div class="card bg-white rounded-3xl shadow-2xl overflow-hidden w-full max-w-2xl mx-auto">

        {{-- Header SENA --}}
        <div class="bg-[#39A900] px-8 py-5 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="bg-white/20 rounded-xl p-2.5">
                    <i class="fas fa-shield-alt text-white text-2xl"></i>
                </div>
                <div>
                    <p class="font-black text-white text-xl uppercase tracking-tight leading-none">SGSPI</p>
                    <p class="text-white/70 text-[10px] font-bold uppercase tracking-widest">Seguridad de la Información</p>
                </div>
            </div>
            <div class="text-right">
                <p class="font-black text-white text-sm uppercase tracking-tight">SENA</p>
                <p class="text-white/70 text-[10px] font-bold uppercase">Regional Casanare</p>
            </div>
        </div>

        <div class="px-8 py-7">

            {{-- Título + QR --}}
            <div class="flex flex-col md:flex-row items-center gap-7 mb-7">

                {{-- Info --}}
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-12 h-12 bg-[#39A900]/10 rounded-2xl flex items-center justify-center">
                            <i class="fas fa-gamepad text-[#39A900] text-xl"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl font-black text-gray-800 uppercase tracking-tight leading-tight">Módulo de<br>Juegos</h1>
                        </div>
                    </div>
                    <p class="text-xs font-bold text-gray-500 leading-relaxed">
                        Actividades interactivas de sensibilización en ciberseguridad para funcionarios, aprendices y contratistas del SENA Regional Casanare.
                    </p>
                    <div class="flex flex-wrap gap-2 mt-3">
                        <span class="bg-green-50 text-green-700 text-[10px] font-black px-2.5 py-1 rounded-xl uppercase">🎮 2 juegos</span>
                        <span class="bg-blue-50 text-blue-700 text-[10px] font-black px-2.5 py-1 rounded-xl uppercase">🆓 Acceso libre</span>
                        <span class="bg-purple-50 text-purple-700 text-[10px] font-black px-2.5 py-1 rounded-xl uppercase">📱 Desde celular</span>
                    </div>
                </div>

                {{-- QR --}}
                <div class="flex flex-col items-center gap-2 shrink-0">
                    <div id="qr-code" class="p-3 bg-white border-2 border-gray-100 rounded-2xl shadow-sm"></div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Escanea con tu celular</p>
                    <p class="text-[11px] font-black text-[#39A900] text-center break-all max-w-[180px]">{{ $url }}</p>
                </div>
            </div>

            {{-- Divisor --}}
            <div class="flex items-center gap-3 mb-5">
                <div class="flex-1 h-px bg-gray-100"></div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest whitespace-nowrap">Juegos disponibles</p>
                <div class="flex-1 h-px bg-gray-100"></div>
            </div>

            {{-- Los 2 juegos --}}
            <div class="grid md:grid-cols-2 gap-4 mb-6">

                {{-- Buscaminas --}}
                <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100">
                    <div class="flex items-center gap-2.5 mb-3">
                        <div class="w-9 h-9 bg-[#39A900] rounded-xl flex items-center justify-center">
                            <i class="fas fa-bomb text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="font-black text-gray-800 text-sm uppercase tracking-tight">Buscaminas</p>
                            <p class="text-[10px] text-gray-400 font-bold">Preguntas de seguridad</p>
                        </div>
                    </div>
                    <div class="space-y-1.5 text-[11px] text-gray-600 font-bold">
                        <p><i class="fas fa-th text-[#39A900] mr-1.5 w-4"></i>Tablero de 25 celdas</p>
                        <p><i class="fas fa-question-circle text-blue-400 mr-1.5 w-4"></i>20 preguntas aleatorias</p>
                        <p><i class="fas fa-star text-yellow-500 mr-1.5 w-4"></i>+10 pts por respuesta correcta</p>
                        <p><i class="fas fa-trophy text-yellow-600 mr-1.5 w-4"></i>Máximo 200 puntos</p>
                    </div>
                </div>

                {{-- Phishing --}}
                <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100">
                    <div class="flex items-center gap-2.5 mb-3">
                        <div class="w-9 h-9 bg-[#1e3a5f] rounded-xl flex items-center justify-center relative">
                            <i class="fas fa-fish text-white text-sm"></i>
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[8px] font-black px-1 rounded-full">!</span>
                        </div>
                        <div>
                            <p class="font-black text-gray-800 text-sm uppercase tracking-tight">Detector Phishing</p>
                            <p class="text-[10px] text-gray-400 font-bold">Identifica emails falsos</p>
                        </div>
                    </div>
                    <div class="space-y-1.5 text-[11px] text-gray-600 font-bold">
                        <p><i class="fas fa-layer-group text-[#1e3a5f] mr-1.5 w-4"></i>4 niveles progresivos</p>
                        <p><i class="fas fa-envelope text-blue-400 mr-1.5 w-4"></i>20 escenarios reales</p>
                        <p><i class="fas fa-star text-yellow-500 mr-1.5 w-4"></i>+10 pts acierto · +5 pts bonus</p>
                        <p><i class="fas fa-trophy text-yellow-600 mr-1.5 w-4"></i>Máximo 280 puntos</p>
                    </div>
                </div>

            </div>

            {{-- Pasos para participar --}}
            <div class="flex items-center gap-3 mb-4">
                <div class="flex-1 h-px bg-gray-100"></div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest whitespace-nowrap">¿Cómo participar?</p>
                <div class="flex-1 h-px bg-gray-100"></div>
            </div>

            <ol class="space-y-3 mb-6">
                @php
                $pasos = [
                    ['fa-qrcode',      'bg-[#39A900]',  'Escanea el código QR con tu celular o ingresa el enlace en cualquier navegador.'],
                    ['fa-gamepad',     'bg-[#1e3a5f]',  'Elige el juego que quieres jugar: Buscaminas o Detector de Phishing.'],
                    ['fa-id-card',     'bg-blue-500',   'Regístrate con tu nombre completo, número de documento y área o dependencia.'],
                    ['fa-play-circle', 'bg-purple-500', 'Juega respondiendo preguntas o identificando emails falsos según el juego elegido.'],
                    ['fa-award',       'bg-yellow-500', 'Al finalizar, recibe tu calificación y comparte lo aprendido con tu equipo.'],
                ];
                @endphp
                @foreach($pasos as $i => [$icon,$bg,$texto])
                <li class="flex items-start gap-3">
                    <div class="flex-shrink-0 w-7 h-7 {{ $bg }} rounded-xl flex items-center justify-center">
                        <i class="fas {{ $icon }} text-white text-xs"></i>
                    </div>
                    <div class="flex-1 pt-0.5">
                        <span class="text-[10px] font-black text-gray-300 uppercase mr-1">{{ $i+1 }}.</span>
                        <span class="text-xs font-bold text-gray-700">{{ $texto }}</span>
                    </div>
                </li>
                @endforeach
            </ol>

            {{-- Temas evaluados --}}
            <div class="bg-gray-50 rounded-2xl p-4">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Temas de seguridad cubiertos</p>
                <div class="flex flex-wrap gap-1.5">
                    @foreach(['🔐 Contraseñas','📧 Phishing','🎣 Ing. Social','🦠 Malware','☁️ Backups','🔑 Privacidad','💾 USB','✉️ Correo electrónico','💼 BEC / Fraude'] as $t)
                        <span class="px-2 py-1 bg-white border border-gray-200 text-gray-600 text-[10px] font-bold rounded-lg">{{ $t }}</span>
                    @endforeach
                </div>
            </div>

        </div>

        {{-- Footer --}}
        <div class="bg-gray-50 border-t border-gray-100 px-8 py-4 flex items-center justify-between">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">SENA Regional Casanare</p>
            <p class="text-[10px] font-bold text-gray-400 italic">Gestión TIC &mdash; SGSPI &mdash; ISO 27001</p>
        </div>

    </div>

    <script>
        new QRCode(document.getElementById("qr-code"), {
            text: "{{ $url }}",
            width: 160,
            height: 160,
            colorDark: "#1f2937",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });
    </script>
</body>
</html>
