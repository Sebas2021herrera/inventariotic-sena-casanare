<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instrucciones — Buscaminas SGSPI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;700;800;900&display=swap');
        body { font-family: 'Inter', sans-serif; background: #f8fafc; }
        @media print {
            .no-print { display: none !important; }
            body { background: white; }
            .print-shadow { box-shadow: none !important; border: 1px solid #e5e7eb !important; }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col items-center justify-center p-6">

    {{-- Botones de acción (no se imprimen) --}}
    <div class="no-print flex gap-3 mb-6">
        <button onclick="window.print()"
            class="flex items-center gap-2 bg-[#39A900] text-white font-black px-5 py-2.5 rounded-xl text-sm uppercase tracking-widest shadow hover:bg-green-700 transition">
            <i class="fas fa-print"></i> Imprimir
        </button>
        <a href="{{ route('sgspi.index') }}"
            class="flex items-center gap-2 bg-gray-100 text-gray-700 font-black px-5 py-2.5 rounded-xl text-sm uppercase tracking-widest hover:bg-gray-200 transition">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    {{-- TARJETA PRINCIPAL --}}
    <div class="bg-white rounded-3xl shadow-2xl print-shadow overflow-hidden w-full max-w-lg">

        {{-- Header SENA --}}
        <div class="bg-[#39A900] text-white px-8 py-6 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="bg-white/20 rounded-xl p-2.5">
                    <i class="fas fa-shield-alt text-2xl"></i>
                </div>
                <div>
                    <p class="font-black text-xl uppercase tracking-tight leading-none">SGSPI</p>
                    <p class="text-white/70 text-[10px] font-bold uppercase tracking-widest">Seguridad de la Información</p>
                </div>
            </div>
            <div class="text-right">
                <p class="font-black text-sm uppercase tracking-tight leading-none">SENA</p>
                <p class="text-white/70 text-[10px] font-bold uppercase">Regional Casanare</p>
            </div>
        </div>

        <div class="px-8 py-7 space-y-6">

            {{-- Título --}}
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-14 h-14 bg-[#39A900]/10 rounded-2xl mb-3">
                    <i class="fas fa-bomb text-[#39A900] text-2xl"></i>
                </div>
                <h1 class="text-2xl font-black text-gray-800 uppercase tracking-tight">Buscaminas SGSPI</h1>
                <p class="text-gray-400 text-xs font-bold mt-1">Actividad de Sensibilización — Juego Interactivo</p>
            </div>

            {{-- QR + URL --}}
            <div class="flex flex-col items-center gap-3">
                <div id="qr-code" class="p-3 bg-white border-2 border-gray-100 rounded-2xl shadow-sm"></div>
                <div class="text-center">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Escanea o ingresa a</p>
                    <p class="font-black text-[#39A900] text-sm tracking-tight">{{ $url }}</p>
                </div>
            </div>

            {{-- Divisor --}}
            <div class="flex items-center gap-3">
                <div class="flex-1 h-px bg-gray-100"></div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">¿Cómo participar?</p>
                <div class="flex-1 h-px bg-gray-100"></div>
            </div>

            {{-- Pasos --}}
            <ol class="space-y-3">
                @php
                $pasos = [
                    ['icon' => 'fa-qrcode',       'color' => 'bg-[#39A900]',  'texto' => 'Escanea el código QR con tu celular o ingresa al enlace desde cualquier navegador.'],
                    ['icon' => 'fa-id-card',       'color' => 'bg-blue-500',   'texto' => 'Regístrate con tu nombre completo, número de documento y área o dependencia.'],
                    ['icon' => 'fa-hand-pointer',  'color' => 'bg-purple-500', 'texto' => 'Haz clic en cualquier celda del tablero para revelarla.'],
                    ['icon' => 'fa-question-circle','color'=> 'bg-orange-400', 'texto' => 'Responde la pregunta de seguridad que aparece. Selecciona la opción que consideres correcta.'],
                    ['icon' => 'fa-trophy',        'color' => 'bg-yellow-500', 'texto' => 'Al terminar, revisa tu calificación y el porcentaje de aciertos.'],
                ];
                @endphp

                @foreach($pasos as $i => $paso)
                <li class="flex items-start gap-3">
                    <div class="flex-shrink-0 w-8 h-8 {{ $paso['color'] }} rounded-xl flex items-center justify-center">
                        <i class="fas {{ $paso['icon'] }} text-white text-xs"></i>
                    </div>
                    <div class="flex-1 pt-1">
                        <span class="text-[10px] font-black text-gray-300 uppercase mr-1">Paso {{ $i + 1 }}</span>
                        <span class="text-xs font-bold text-gray-700">{{ $paso['texto'] }}</span>
                    </div>
                </li>
                @endforeach
            </ol>

            {{-- Puntuación --}}
            <div class="bg-gray-50 rounded-2xl p-4 grid grid-cols-3 gap-3 text-center">
                <div>
                    <i class="fas fa-check-circle text-green-500 text-lg mb-1"></i>
                    <p class="text-[10px] font-black text-gray-400 uppercase leading-tight">Respuesta<br>correcta</p>
                    <p class="font-black text-[#39A900] text-sm mt-1">+10 pts</p>
                </div>
                <div>
                    <i class="fas fa-times-circle text-red-400 text-lg mb-1"></i>
                    <p class="text-[10px] font-black text-gray-400 uppercase leading-tight">Respuesta<br>incorrecta</p>
                    <p class="font-black text-red-400 text-sm mt-1">0 pts</p>
                </div>
                <div>
                    <i class="fas fa-shield-alt text-blue-400 text-lg mb-1"></i>
                    <p class="text-[10px] font-black text-gray-400 uppercase leading-tight">Zona<br>segura</p>
                    <p class="font-black text-blue-400 text-sm mt-1">Sin pregunta</p>
                </div>
            </div>

            {{-- Temas --}}
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Temas evaluados</p>
                <div class="flex flex-wrap gap-1.5">
                    @foreach(['🔐 Contraseñas','📧 Phishing','🦠 Malware','☁️ Copias de Seguridad','🔑 Privacidad','💾 USB','✉️ Correo Electrónico'] as $tema)
                        <span class="px-2.5 py-1 bg-gray-100 text-gray-600 text-[10px] font-bold rounded-lg">{{ $tema }}</span>
                    @endforeach
                </div>
            </div>

        </div>

        {{-- Footer --}}
        <div class="bg-gray-50 border-t border-gray-100 px-8 py-4 flex items-center justify-between">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">SENA Regional Casanare</p>
            <p class="text-[10px] font-bold text-gray-400 italic">Gestión de TIC &mdash; SGSPI</p>
        </div>

    </div>

    <script>
        new QRCode(document.getElementById("qr-code"), {
            text: "{{ $url }}",
            width: 180,
            height: 180,
            colorDark: "#1f2937",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });
    </script>
</body>
</html>
