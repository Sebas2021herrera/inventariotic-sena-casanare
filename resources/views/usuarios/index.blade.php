@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">

    {{-- Cabecera --}}
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-black text-gray-800 uppercase italic tracking-tighter">
                Gestión de <span class="text-[#39A900]">Usuarios</span>
            </h1>
            <p class="text-gray-500 font-bold text-sm italic">Acceso exclusivo para administradores</p>
        </div>
        <a href="{{ route('usuarios.create') }}"
           class="bg-[#39A900] text-white px-5 py-3 rounded-2xl font-black uppercase text-xs tracking-widest shadow-lg hover:bg-green-700 transition flex items-center gap-2">
            <i class="fas fa-user-plus"></i> Nuevo Usuario
        </a>
    </div>

    {{-- Alertas --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 rounded-2xl px-5 py-4 flex items-center gap-3">
            <i class="fas fa-check-circle text-green-500"></i>
            <span class="text-xs font-bold">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-2xl px-5 py-4 flex items-center gap-3">
            <i class="fas fa-exclamation-circle text-red-500"></i>
            <span class="text-xs font-bold">{{ session('error') }}</span>
        </div>
    @endif

    {{-- Tabla --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-400 font-black uppercase tracking-widest text-[10px]">
                    <th class="px-6 py-4 text-left">Nombre</th>
                    <th class="px-6 py-4 text-left">Correo</th>
                    <th class="px-6 py-4 text-left">Rol</th>
                    <th class="px-6 py-4 text-left">Registrado</th>
                    <th class="px-6 py-4 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($usuarios as $u)
                <tr class="hover:bg-gray-50 transition {{ $u->id === Auth::id() ? 'bg-green-50/40' : '' }}">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center font-black text-sm
                                {{ $u->role === 'admin' ? 'bg-[#39A900] text-white' : 'bg-gray-100 text-gray-500' }}">
                                {{ strtoupper(substr($u->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-black text-gray-800 text-xs">{{ $u->name }}</p>
                                @if($u->id === Auth::id())
                                    <p class="text-[10px] text-[#39A900] font-bold">Tú</p>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-xs font-bold text-gray-600">{{ $u->email }}</td>
                    <td class="px-6 py-4">
                        @if($u->role === 'admin')
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[10px] font-black uppercase bg-[#39A900]/10 text-[#39A900]">
                                <i class="fas fa-shield-alt text-[9px]"></i> Admin
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[10px] font-black uppercase bg-blue-50 text-blue-600">
                                <i class="fas fa-wrench text-[9px]"></i> Técnico
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-xs text-gray-400 font-bold">
                        {{ $u->created_at?->format('d/m/Y') ?? '—' }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        @if($u->id !== Auth::id())
                        <form action="{{ route('usuarios.destroy', $u) }}" method="POST"
                              onsubmit="return confirm('¿Eliminar al usuario {{ addslashes($u->name) }}?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="p-2 rounded-xl bg-red-50 text-red-400 hover:bg-red-500 hover:text-white transition">
                                <i class="fas fa-trash text-xs"></i>
                            </button>
                        </form>
                        @else
                            <span class="text-[10px] text-gray-300 font-bold italic">tu cuenta</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-gray-400 font-bold italic text-xs">
                        No hay usuarios registrados.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-3 bg-gray-50 border-t border-gray-100 text-right">
            <span class="text-[10px] font-bold text-gray-400">{{ $usuarios->count() }} usuario{{ $usuarios->count() !== 1 ? 's' : '' }} en total</span>
        </div>
    </div>

</div>
@endsection
