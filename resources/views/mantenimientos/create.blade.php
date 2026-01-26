@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto pb-12">
    <div class="mb-6 flex items-center">
        <a href="{{ route('dispositivos.show', $dispositivo->id) }}" class="text-gray-500 hover:text-[#39A900] font-bold transition">
            <i class="fas fa-arrow-left mr-2"></i> Cancelar y Volver
        </a>
    </div>

    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-gray-100">
        <div class="sena-bg p-6 text-white">
            <h2 class="text-2xl font-black uppercase italic tracking-tighter">Registrar Mantenimiento</h2>
            <p class="text-white/80 text-xs font-bold uppercase italic">
                Equipo: {{ $dispositivo->marca }} {{ $dispositivo->modelo }} 
                | Placa: {{ $dispositivo->placa }}
            </p>
        </div>

        <form action="{{ route('mantenimientos.store') }}" method="POST" class="p-8 space-y-6">
            @csrf
            <input type="hidden" name="dispositivo_id" value="{{ $dispositivo->id }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Fecha de Intervención</label>
                    <input type="date" name="fecha" value="{{ old('fecha', date('Y-m-d')) }}" required
                           class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 font-bold text-gray-700 focus:ring-2 focus:ring-[#39A900] outline-none transition">
                </div>

                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Tipo de Mantenimiento</label>
                    <select name="tipo" required class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 font-bold text-gray-700 outline-none">
                        <option value="Preventivo" {{ old('tipo') == 'Preventivo' ? 'selected' : '' }}>PREVENTIVO</option>
                        <option value="Correctivo" {{ old('tipo') == 'Correctivo' ? 'selected' : '' }}>CORRECTIVO</option>
                    </select>
                </div>
            </div>

            <div class="p-4 rounded-2xl border-2 border-yellow-100 bg-yellow-50 flex items-center transition-all hover:border-[#39A900]/30">
                <input type="checkbox" name="finalizado" value="1" id="finalizado" 
                       {{ old('finalizado') ? 'checked' : '' }}
                       class="w-6 h-6 text-[#39A900] border-gray-300 rounded focus:ring-[#39A900] cursor-pointer">
                <label for="finalizado" class="ml-3 cursor-pointer">
                    <span class="block text-sm font-black text-gray-800 uppercase leading-none">¿Mantenimiento Finalizado?</span>
                    <span class="text-[10px] font-bold text-gray-500 uppercase">Si NO se marca y es Correctivo, el equipo pasará a "EN REPARACIÓN"</span>
                </label>
            </div>

            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Técnico Responsable</label>
                <input type="text" name="tecnico_encargado" value="{{ old('tecnico_encargado') }}" placeholder="Nombre del técnico que interviene" required
                       class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 font-bold text-gray-700 focus:ring-2 focus:ring-[#39A900] outline-none">
            </div>

            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Tareas Realizadas</label>
                <textarea name="tareas_realizadas" rows="4" placeholder="Describa el trabajo técnico..." required
                          class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 font-medium text-gray-700 focus:ring-2 focus:ring-[#39A900] outline-none">{{ old('tareas_realizadas') }}</textarea>
            </div>

            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Observaciones / Pendientes</label>
                <input type="text" name="observaciones" value="{{ old('observaciones') }}" placeholder="Ej: Queda pendiente cambio de disco"
                       class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 font-medium text-gray-700 outline-none">
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full sena-bg text-white font-black py-4 rounded-2xl shadow-xl hover:opacity-90 transition transform hover:-translate-y-1">
                    <i class="fas fa-save mr-2"></i> GUARDAR REGISTRO Y ACTUALIZAR ESTADO
                </button>
            </div>
        </form>
    </div>
</div>
@endsection