@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-black text-gray-800 uppercase italic tracking-tighter">
                Editar <span class="text-[#39A900]">Equipo</span>
            </h1>
            <p class="text-gray-500 text-sm font-bold italic">{{ $equipo->marca }} {{ $equipo->modelo }}</p>
        </div>
        <a href="{{ route('equipos-energia.show', $equipo) }}"
           class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-5 py-2 rounded-xl font-bold transition flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Cancelar
        </a>
    </div>

    @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-xl">
            <ul class="text-red-700 text-sm list-disc list-inside space-y-1">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('equipos-energia.update', $equipo) }}" method="POST" class="space-y-6">
        @csrf @method('PUT')
        @include('equipos_energia._form', ['equipo' => $equipo])
        <div class="flex gap-3 justify-end pt-2">
            <a href="{{ route('equipos-energia.show', $equipo) }}" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-2xl font-black text-xs uppercase tracking-widest transition">Cancelar</a>
            <button type="submit" class="px-8 py-3 bg-[#39A900] hover:bg-green-700 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg transition">
                <i class="fas fa-save mr-2"></i> Guardar Cambios
            </button>
        </div>
    </form>
</div>
@endsection
