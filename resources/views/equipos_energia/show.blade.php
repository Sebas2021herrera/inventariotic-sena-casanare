@extends('layouts.app')

@section('content')
@php
use App\Models\EquipoEnergia;
$ic = EquipoEnergia::TIPOS_ICONO[$equipo->tipo] ?? ['fa-bolt','gray'];
$ec = EquipoEnergia::ESTADOS_COLOR[$equipo->estado] ?? 'bg-gray-100 text-gray-600';
@endphp

<div class="max-w-4xl mx-auto space-y-6">

    {{-- Cabecera --}}
    <div class="bg-[#1e3a5f] rounded-3xl p-7 text-white">
        <div class="flex flex-col md:flex-row justify-between items-start gap-4">
            <div class="flex items-center gap-4">
                <div class="p-4 bg-white/10 rounded-2xl">
                    <i class="fas {{ $ic[0] }} text-3xl"></i>
                </div>
                <div>
                    <p class="text-white/60 text-[10px] font-black uppercase tracking-widest">{{ $equipo->tipo }}</p>
                    <h1 class="text-2xl font-black uppercase tracking-tight">{{ $equipo->marca }} {{ $equipo->modelo }}</h1>
                    <p class="text-white/70 font-mono mt-1">
                        {{ $equipo->placa ? 'Placa: '.$equipo->placa.' · ' : '' }}
                        {{ $equipo->numero_serie ? 'S/N: '.$equipo->numero_serie : '' }}
                    </p>
                </div>
            </div>
            <div class="flex flex-col items-end gap-2">
                <span class="px-4 py-2 rounded-2xl text-sm font-black {{ $ec }}">{{ $equipo->estado }}</span>
                @if($equipo->marquillado)
                    <span class="text-[10px] bg-blue-500 text-white px-3 py-1 rounded-full font-bold">Marquillado</span>
                @endif
                <div class="flex gap-2 mt-1">
                    <a href="{{ route('equipos-energia.edit', $equipo) }}"
                       class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition flex items-center gap-1">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 rounded-2xl px-5 py-4 flex items-center gap-3">
            <i class="fas fa-check-circle text-green-500"></i>
            <span class="text-xs font-bold">{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- Identificación y Ubicación --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
            <h3 class="text-[10px] font-black text-[#39A900] uppercase tracking-widest border-b pb-2">
                <i class="fas fa-tag mr-2"></i>Identificación y Ubicación
            </h3>
            @php
            $filas = [
                'Sede'         => $equipo->sede->nombre ?? '—',
                'Cuarto / Sala'=> $equipo->cuarto,
                'Pertenece'    => $equipo->pertenece ?? '—',
                'Proveedor'    => $equipo->proveedor ?? '—',
                'Marquillado'  => $equipo->marquillado ? 'Sí' : 'No',
            ];
            @endphp
            @foreach($filas as $lbl => $val)
            <div class="flex justify-between">
                <span class="text-[10px] font-black text-gray-400 uppercase">{{ $lbl }}</span>
                <span class="text-xs font-bold text-gray-700">{{ $val }}</span>
            </div>
            @endforeach
        </div>

        {{-- Especificaciones Eléctricas --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-3">
            <h3 class="text-[10px] font-black text-[#39A900] uppercase tracking-widest border-b pb-2">
                <i class="fas fa-bolt mr-2"></i>Especificaciones Eléctricas
            </h3>
            @php
            $specs = [
                'Fase'               => $equipo->fase,
                'Potencia'           => ($equipo->potencia_va ? number_format($equipo->potencia_va).' VA' : '') . ($equipo->potencia_w ? ' / '.number_format($equipo->potencia_w).' W' : ''),
                'Capacidad Salida'   => ($equipo->capacidad_va ? number_format($equipo->capacidad_va).' VA' : '') . ($equipo->capacidad_w ? ' / '.number_format($equipo->capacidad_w).' W' : ''),
                'Capacidad (A)'      => $equipo->capacidad_a ? $equipo->capacidad_a.' A' : null,
                'Cap. Conmutación'   => $equipo->capacidad_conmutacion_a ? $equipo->capacidad_conmutacion_a.' A' : null,
                'Voltaje E / S'      => ($equipo->voltaje_entrada ? $equipo->voltaje_entrada.'V' : '') . ($equipo->voltaje_salida ? ' → '.$equipo->voltaje_salida.'V' : ''),
                'Frecuencia'         => $equipo->frecuencia.' Hz',
            ];
            if ($equipo->tipo === 'UPS') $specs['Tecnología UPS'] = $equipo->tecnologia_ups;
            @endphp
            @foreach($specs as $lbl => $val)
            @if($val)
            <div class="flex justify-between">
                <span class="text-[10px] font-black text-gray-400 uppercase">{{ $lbl }}</span>
                <span class="text-xs font-bold text-gray-700">{{ $val }}</span>
            </div>
            @endif
            @endforeach
        </div>

        {{-- Batería y Respaldo --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-3">
            <h3 class="text-[10px] font-black text-[#39A900] uppercase tracking-widest border-b pb-2">
                <i class="fas fa-battery-three-quarters mr-2"></i>Batería y Respaldo
            </h3>
            @php
            $bat = [
                'Capacidad Batería'       => $equipo->capacidad_baterias_ah ? $equipo->capacidad_baterias_ah.' Ah' : null,
                'Número de Baterías'      => $equipo->numero_baterias,
                'Respaldo Nominal'        => $equipo->tiempo_respaldo_min ? $equipo->tiempo_respaldo_min.' min' : null,
                'Respaldo Verificado'     => $equipo->tiempo_respaldo_verificado_min ? $equipo->tiempo_respaldo_verificado_min.' min' : null,
            ];
            @endphp
            @foreach($bat as $lbl => $val)
            @if($val)
            <div class="flex justify-between">
                <span class="text-[10px] font-black text-gray-400 uppercase">{{ $lbl }}</span>
                <span class="text-xs font-bold {{ $lbl === 'Respaldo Verificado' ? 'text-[#39A900]' : 'text-gray-700' }}">{{ $val }}</span>
            </div>
            @endif
            @endforeach
            @if(!$equipo->capacidad_baterias_ah && !$equipo->tiempo_respaldo_min)
                <p class="text-[10px] text-gray-400 italic">No aplica o sin datos de batería.</p>
            @endif
        </div>

        {{-- Fechas --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-3">
            <h3 class="text-[10px] font-black text-[#39A900] uppercase tracking-widest border-b pb-2">
                <i class="fas fa-calendar-alt mr-2"></i>Fechas y Gestión
            </h3>
            @php
            $fechas = [
                'Instalación'        => $equipo->fecha_instalacion?->format('d/m/Y'),
                'Último Mant.'       => $equipo->fecha_ultimo_mantenimiento?->format('d/m/Y'),
                'Próximo Mant.'      => $equipo->proximo_mantenimiento?->format('d/m/Y'),
                'Garantía Hasta'     => $equipo->garantia_hasta?->format('d/m/Y'),
            ];
            @endphp
            @foreach($fechas as $lbl => $val)
            @if($val)
            @php
                $esVencido = ($lbl === 'Próximo Mant.' && $equipo->proximo_mantenimiento?->isPast());
                $esProx    = ($lbl === 'Próximo Mant.' && $equipo->proximo_mantenimiento?->isFuture() && $equipo->proximo_mantenimiento->diffInDays(now()) <= 30);
            @endphp
            <div class="flex justify-between">
                <span class="text-[10px] font-black text-gray-400 uppercase">{{ $lbl }}</span>
                <span class="text-xs font-bold {{ $esVencido ? 'text-red-600' : ($esProx ? 'text-orange-500' : 'text-gray-700') }}">
                    {{ $val }}
                    @if($esVencido) <i class="fas fa-exclamation-triangle ml-1"></i> @endif
                </span>
            </div>
            @endif
            @endforeach
            <p class="text-[9px] text-gray-400 mt-2 italic">
                Registrado por {{ $equipo->creador->name ?? 'Sistema' }} ·
                {{ $equipo->created_at->format('d/m/Y') }}
            </p>
        </div>
    </div>

    {{-- Observaciones --}}
    @if($equipo->observaciones)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-[10px] font-black text-[#39A900] uppercase tracking-widest mb-3">
            <i class="fas fa-comment-alt mr-2"></i>Observaciones
        </h3>
        <p class="text-sm text-gray-600 leading-relaxed">{{ $equipo->observaciones }}</p>
    </div>
    @endif

</div>
@endsection
