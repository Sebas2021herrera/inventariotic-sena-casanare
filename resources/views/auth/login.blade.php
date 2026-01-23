@extends('layouts.app')

@section('content')
<div class="min-h-[80vh] flex flex-col justify-center items-center">
    <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl overflow-hidden border border-gray-100">
        <div class="sena-bg p-8 text-center text-white">
            <h1 class="text-4xl font-black italic tracking-tighter uppercase">SOFTHARD</h1>
            <p class="text-white/70 text-xs font-bold uppercase tracking-widest mt-2">Acceso Equipo Técnico</p>
        </div>

        <form action="{{ route('login') }}" method="POST" class="p-8 space-y-6">
            @csrf
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Correo Institucional</label>
                <div class="relative">
                    <i class="fas fa-envelope absolute left-4 top-4 text-gray-300"></i>
                    <input type="email" name="email" class="w-full bg-gray-50 border-none rounded-2xl p-4 pl-12 text-sm focus:ring-2 focus:ring-[#39A900] transition" placeholder="usuario@sena.edu.co" required>
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Contraseña</label>
                <div class="relative">
                    <i class="fas fa-lock absolute left-4 top-4 text-gray-300"></i>
                    <input type="password" name="password" class="w-full bg-gray-50 border-none rounded-2xl p-4 pl-12 text-sm focus:ring-2 focus:ring-[#39A900] transition" placeholder="••••••••" required>
                </div>
            </div>

            @if($errors->any())
                <p class="text-red-500 text-[10px] font-bold italic text-center">{{ $errors->first() }}</p>
            @endif

            <button type="submit" class="w-full sena-bg text-white font-black py-4 rounded-2xl shadow-lg hover:scale-[1.02] transition-transform active:scale-95 uppercase tracking-widest text-xs">
                Entrar al Sistema
            </button>
        </form>
    </div>
    <p class="mt-8 text-gray-400 text-[10px] font-bold uppercase tracking-widest">SENA Regional Casanare © 2026</p>
</div>
@endsection