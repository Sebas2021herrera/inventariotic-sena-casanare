<?php

namespace App\Http\Controllers;

use App\Models\Dispositivo;
use App\Models\Mantenimiento;
use App\Models\EquipoEnergia;
use App\Models\AreaSegura;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'dispositivos'   => Dispositivo::count(),
            'mantenimientos' => Mantenimiento::where('finalizado', false)->count(),
            'energia'        => EquipoEnergia::count(),
            'areas'          => AreaSegura::count(),
        ];

        return view('dashboard', compact('stats'));
    }
}
