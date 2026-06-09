@extends('layouts.app')

@section('content')
<div class="min-h-[80vh] flex flex-col justify-center items-center">
    <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl overflow-hidden border border-gray-100">

        <div class="sena-bg p-8 text-center text-white">
            <div class="flex justify-center mb-3">
                <div class="bg-white/20 rounded-2xl p-3">
                    <i class="fas fa-user-edit text-2xl"></i>
                </div>
            </div>
            <h1 class="text-2xl font-black uppercase tracking-tight">Editar Usuario</h1>
            <p class="text-white/70 text-xs font-bold uppercase tracking-widest mt-1">{{ $usuario->name }}</p>
        </div>

        <form action="{{ route('usuarios.update', $usuario) }}" method="POST" class="p-8 space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Nombre Completo</label>
                <div class="relative">
                    <i class="fas fa-user absolute left-4 top-4 text-gray-300"></i>
                    <input type="text" name="name" value="{{ old('name', $usuario->name) }}"
                        class="w-full bg-gray-50 border-none rounded-2xl p-4 pl-12 text-sm focus:ring-2 focus:ring-[#39A900] transition @error('name') ring-2 ring-red-400 @enderror"
                        placeholder="Nombre y apellido" required autofocus>
                </div>
                @error('name')
                    <p class="text-red-500 text-[10px] font-bold mt-1 ml-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Correo Institucional</label>
                <div class="relative">
                    <i class="fas fa-envelope absolute left-4 top-4 text-gray-300"></i>
                    <input type="email" name="email" value="{{ old('email', $usuario->email) }}"
                        class="w-full bg-gray-50 border-none rounded-2xl p-4 pl-12 text-sm focus:ring-2 focus:ring-[#39A900] transition @error('email') ring-2 ring-red-400 @enderror"
                        placeholder="usuario@sena.edu.co" required>
                </div>
                @error('email')
                    <p class="text-red-500 text-[10px] font-bold mt-1 ml-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Rol</label>
                <div class="relative">
                    <i class="fas fa-id-badge absolute left-4 top-4 text-gray-300"></i>
                    <select name="role"
                        class="w-full bg-gray-50 border-none rounded-2xl p-4 pl-12 text-sm focus:ring-2 focus:ring-[#39A900] transition @error('role') ring-2 ring-red-400 @enderror appearance-none">
                        <option value="tecnico" {{ old('role', $usuario->role) === 'tecnico' ? 'selected' : '' }}>Técnico</option>
                        <option value="admin"   {{ old('role', $usuario->role) === 'admin'   ? 'selected' : '' }}>Administrador</option>
                    </select>
                    <i class="fas fa-chevron-down absolute right-4 top-4 text-gray-300 pointer-events-none"></i>
                </div>
                @error('role')
                    <p class="text-red-500 text-[10px] font-bold mt-1 ml-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="border-t border-gray-100 pt-4">
                <p class="text-[10px] font-bold text-gray-400 uppercase mb-3">Nueva Contraseña <span class="text-gray-300 font-normal normal-case">(dejar en blanco para no cambiar)</span></p>
                <div class="space-y-4">
                    <div>
                        <div class="relative">
                            <i class="fas fa-lock absolute left-4 top-4 text-gray-300"></i>
                            <input type="password" name="password"
                                class="w-full bg-gray-50 border-none rounded-2xl p-4 pl-12 text-sm focus:ring-2 focus:ring-[#39A900] transition @error('password') ring-2 ring-red-400 @enderror"
                                placeholder="Mínimo 8 caracteres">
                        </div>
                        @error('password')
                            <p class="text-red-500 text-[10px] font-bold mt-1 ml-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-4 top-4 text-gray-300"></i>
                        <input type="password" name="password_confirmation"
                            class="w-full bg-gray-50 border-none rounded-2xl p-4 pl-12 text-sm focus:ring-2 focus:ring-[#39A900] transition"
                            placeholder="Repite la nueva contraseña">
                    </div>
                </div>
            </div>

            <div class="pt-2 flex gap-3">
                <a href="{{ route('usuarios.index') }}"
                    class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-600 font-black py-4 rounded-2xl transition text-xs uppercase tracking-widest">
                    Cancelar
                </a>
                <button type="submit"
                    class="flex-1 sena-bg text-white font-black py-4 rounded-2xl shadow-lg hover:scale-[1.02] transition-transform active:scale-95 uppercase tracking-widest text-xs">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
