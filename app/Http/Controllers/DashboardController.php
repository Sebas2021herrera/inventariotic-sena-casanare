<?php

namespace App\Http\Controllers;

use App\Models\Dispositivo;
use App\Models\Mantenimiento;
use App\Models\EquipoEnergia;
use App\Models\AreaSegura;
use App\Models\IntuneCuenta;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'dispositivos'   => Dispositivo::count(),
            'mantenimientos' => Mantenimiento::where('finalizado', false)->count(),
            'energia'        => EquipoEnergia::count(),
            'areas'          => AreaSegura::count(),
            'intune'         => IntuneCuenta::count(),
        ];

        return view('dashboard', compact('stats'));
    }
}
