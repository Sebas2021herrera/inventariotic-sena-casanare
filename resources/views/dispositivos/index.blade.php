@extends('layouts.app')

@section('content')

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6 border-b-4 border-blue-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                <i class="fas fa-laptop text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 uppercase font-bold">Total Equipos</p>
                <h3 class="text-2xl font-black">{{ $stats['total'] ?? 0 }}</h3>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border-b-4 border-green-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                <i class="fas fa-check-circle text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 uppercase font-bold">Estado Bueno</p>
                <h3 class="text-2xl font-black">{{ $stats['buenos'] ?? 0 }}</h3>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border-b-4 border-orange-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-orange-100 text-orange-600 mr-4">
                <i class="fas fa-exclamation-triangle text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 uppercase font-bold">Con Novedades</p>
                <h3 class="text-2xl font-black">{{ $stats['criticos'] ?? 0 }}</h3>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border-b-4 border-cyan-600">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-cyan-100 text-cyan-600 mr-4">
                <i class="fas fa-network-wired text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 uppercase font-bold">Equipos de Red</p>
                <h3 class="text-2xl font-black">
                    {{ \App\Models\Dispositivo::where('categoria', 'conectividad')->count() }}
                </h3>
            </div>
        </div>
    </div>
</div>

<div class="flex flex-col lg:flex-row gap-8">
    
    <div class="w-full lg:w-1/4">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex flex-col mb-4">
                <h2 class="font-bold text-gray-700 flex items-center">
                    <i class="fas fa-file-import mr-2 sena-text"></i> Carga Masiva
                </h2>
                <a href="{{ route('dispositivos.plantilla') }}" class="text-[10px] text-blue-600 hover:text-blue-800 font-bold mt-1 flex items-center transition">
                    <i class="fas fa-download mr-1"></i> Descargar plantilla base (.xlsx)
                </a>
            </div>
            
            <form action="{{ route('dispositivos.importar') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Seleccionar Archivo</label>
                    <input type="file" name="archivo" class="w-full text-xs text-gray-500 border border-dashed border-gray-200 p-2 rounded-xl cursor-pointer" required>
                </div>
                
                <button type="submit" class="w-full sena-bg text-white font-black uppercase text-xs tracking-widest py-3 rounded-xl hover:bg-green-700 transition shadow-lg flex justify-center items-center">
                    <i class="fas fa-upload mr-2"></i> Procesar Inventario
                </button>
            </form>

            @if(session('success'))
                <div class="mt-4 p-3 bg-green-100 text-green-700 rounded-xl text-xs flex items-center border border-green-200">
                    <i class="fas fa-check-circle mr-2 text-lg"></i> 
                    <div>{{ session('success') }}</div>
                </div>
            @endif
        </div>
    </div>

    <div class="w-full lg:w-3/4">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <h2 class="font-bold text-gray-700 uppercase tracking-wider">Inventario de Equipos</h2>
                <a href="{{ route('dispositivos.create') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg text-xs font-bold hover:bg-green-700 transition shadow">
                    <i class="fas fa-plus mr-1"></i> Agregar Manualmente
                </a>
            </div>

            <table class="w-full text-left">
                <thead class="bg-gray-50 text-gray-400 text-xs uppercase font-bold">
                    <tr>
                        <th class="px-6 py-4">Placa / Serial</th>
                        <th class="px-6 py-4">Equipo</th>
                        <th class="px-6 py-4">Responsable</th>
                        <th class="px-6 py-4">Ubicación</th>
                        <th class="px-6 py-4">Estado</th>
                        <th class="px-6 py-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($dispositivos as $dispositivo)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-800">{{ $dispositivo->placa }}</div>
                            <div class="text-xs text-gray-400">{{ $dispositivo->serial }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-gray-700">{{ $dispositivo->marca }} {{ $dispositivo->modelo }}</div>
                            <div class="flex gap-1 mt-1">
                                @if($dispositivo->categoria == 'conectividad')
                                    <span class="bg-blue-100 text-blue-700 text-[8px] px-1.5 py-0.5 rounded font-black uppercase">Red</span>
                                @else
                                    <span class="bg-gray-100 text-gray-600 text-[8px] px-1.5 py-0.5 rounded font-black uppercase">PC</span>
                                @endif
                                @if($dispositivo->en_intune == 'SI')
                                    <span class="bg-cyan-100 text-cyan-700 text-[8px] px-1.5 py-0.5 rounded font-black uppercase">Intune</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-700">{{ $dispositivo->responsable->nombre }}</div>
                            <div class="text-xs text-gray-400 italic">{{ $dispositivo->responsable->cargo }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-xs text-gray-600">{{ $dispositivo->ubicacion->sede }}</div>
                            <div class="text-xs font-bold text-green-600">Amb: {{ $dispositivo->ubicacion->ambiente }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $estado = strtoupper(trim($dispositivo->estado_fisico));
                                $color = match($estado) {
                                    'BUENO' => 'bg-green-100 text-green-700',
                                    'REGULAR' => 'bg-orange-100 text-orange-700',
                                    'MALO' => 'bg-red-100 text-red-700',
                                    default => 'bg-gray-100 text-gray-700'
                                };
                            @endphp
                            <span class="px-2 py-1 text-[10px] font-black uppercase rounded-full {{ $color }}">
                                {{ $dispositivo->estado_fisico }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center items-center space-x-4">
                                <a href="{{ route('dispositivos.show', $dispositivo) }}" class="text-blue-500 hover:text-blue-700 transition" title="Ver detalles">
                                    <i class="fas fa-eye text-lg"></i>
                                </a>

                                <a href="{{ route('dispositivos.edit', $dispositivo) }}" 
                                        class="p-2 bg-orange-50 text-orange-600 rounded-lg hover:bg-orange-600 hover:text-white transition shadow-sm"
                                        title="Editar equipo">
                                        <i class="fas fa-pen text-sm"></i>
                                 </a>
                                <form action="{{ route('dispositivos.destroy', $dispositivo) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Eliminar equipo?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 transition">
                                        <i class="fas fa-trash-alt text-lg"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="p-4 bg-gray-50 border-t border-gray-100">
                {{ $dispositivos->links() }}
            </div>
        </div>
    </div>
</div>
@endsection