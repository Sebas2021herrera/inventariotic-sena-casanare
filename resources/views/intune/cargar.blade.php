@extends('layouts.app')
@section('title', 'Cargar Cuentas Intune')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    {{-- Encabezado --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('intune.index') }}"
           class="text-gray-400 hover:text-gray-700 transition">
            <i class="fas fa-arrow-left text-lg"></i>
        </a>
        <div>
            <h1 class="text-2xl font-black text-gray-800 uppercase tracking-tight">Cargar Cuentas Intune</h1>
            <p class="text-gray-400 text-xs font-bold mt-0.5">Pega la lista de cuentas enviada por Dirección General</p>
        </div>
    </div>

    {{-- Errores --}}
    @if($errors->any())
    <div class="bg-red-50 border border-red-200 rounded-xl p-4">
        <ul class="text-red-700 text-xs font-bold space-y-1">
            @foreach($errors->all() as $e)
            <li><i class="fas fa-exclamation-circle mr-1"></i>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Info formato --}}
    <div class="bg-blue-50 border border-blue-100 rounded-2xl p-5">
        <p class="text-[10px] font-black text-blue-500 uppercase tracking-widest mb-2">Formato esperado</p>
        <p class="text-xs font-bold text-blue-800 font-mono">170_95191024258@senaedu.edu.co</p>
        <p class="text-[11px] text-blue-600 font-bold mt-1">
            <span class="font-black">170</span> = IdSede &nbsp;·&nbsp;
            <span class="font-black">95191024258</span> = Placa SENA del equipo
        </p>
        <p class="text-[10px] text-blue-400 mt-2">Puedes pegar múltiples cuentas separadas por saltos de línea, comas o punto y coma.</p>
    </div>

    {{-- Formulario --}}
    <form method="POST" action="{{ route('intune.procesar') }}"
          class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-5">
        @csrf

        <div>
            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">
                Lista de Cuentas *
            </label>
            <textarea name="cuentas" rows="10"
                      placeholder="170_95191024258@senaedu.edu.co&#10;170_95191024259@senaedu.edu.co&#10;..."
                      class="w-full border border-gray-200 rounded-xl px-4 py-3 text-xs font-mono focus:outline-none focus:border-blue-400 resize-y"
                      required>{{ old('cuentas') }}</textarea>
        </div>

        <div>
            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">
                Fecha del Reporte
            </label>
            <input type="date" name="fecha_reporte" value="{{ old('fecha_reporte') }}"
                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-bold focus:outline-none focus:border-blue-400">
            <p class="text-[10px] text-gray-400 font-bold mt-1">El estado se asigna automáticamente: <span class="text-green-600">Activa</span> si el equipo ya está enrolado en Intune, <span class="text-orange-500">Pendiente por asignar</span> si no.</p>
        </div>
        {{-- Estado oculto: siempre pendiente al cargar, se actualiza al enrolar --}}
        <input type="hidden" name="estado" value="pendiente">

        <div class="flex gap-3 pt-2">
            <button type="submit"
                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-black py-3 rounded-xl text-sm uppercase tracking-widest transition shadow-md">
                <i class="fas fa-upload mr-2"></i> Procesar Cuentas
            </button>
            <a href="{{ route('intune.index') }}"
               class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-600 font-black rounded-xl text-sm uppercase tracking-widest transition flex items-center gap-2">
                Cancelar
            </a>
        </div>
    </form>

    {{-- Ayuda --}}
    <div class="bg-gray-50 rounded-2xl border border-gray-100 p-5 space-y-2">
        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">¿Qué hace esta carga?</p>
        <ul class="text-xs text-gray-500 font-bold space-y-1.5">
            <li><i class="fas fa-check text-green-500 mr-2"></i>Registra cada cuenta nueva en la base de datos</li>
            <li><i class="fas fa-check text-green-500 mr-2"></i>Si la cuenta ya existe, actualiza su estado y fecha</li>
            <li><i class="fas fa-check text-green-500 mr-2"></i>Marca automáticamente el campo <span class="font-black text-blue-600">en_intune = SI</span> en el inventario para la placa correspondiente</li>
            <li><i class="fas fa-times text-red-400 mr-2"></i>Las líneas con formato inválido se ignoran (verás el conteo al final)</li>
        </ul>
    </div>

</div>
@endsection
