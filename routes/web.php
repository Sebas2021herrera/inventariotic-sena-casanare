<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DispositivoController;
use App\Http\Controllers\ResponsableController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MantenimientoController;
use App\Http\Controllers\ReporteController;
// 1. RAIZ DEL ALIAS: Cuando el técnico entra a .../gitic/
Route::get('/', function () {
    return auth()->check() 
        ? redirect()->route('dispositivos.index') 
        : redirect()->route('login');
});

// 2. RUTAS DE AUTENTICACIÓN (Sin prefijo gitic porque Apache ya lo da)
Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// 3. RUTAS PROTEGIDAS
Route::middleware(['auth'])->group(function () {
    
    // Recursos principales
    Route::resource('dispositivos', DispositivoController::class);
    Route::resource('responsables', ResponsableController::class);

    // 2. ESTA LÍNEA ES LA QUE CREA LA RUTA 'mantenimientos.create'
    Route::resource('mantenimientos', MantenimientoController::class);
    
    // Funcionalidades de Inventario SENA
    Route::post('importar-inventario', [DispositivoController::class, 'importar'])->name('dispositivos.importar');
    Route::get('descargar-plantilla', [DispositivoController::class, 'descargarPlantilla'])->name('dispositivos.plantilla');

    // Búsquedas dinámicas
    Route::get('/responsables/buscar/{cedula}', [ResponsableController::class, 'buscar'])->name('responsables.buscar');
    Route::get('/dispositivos/verificar-placa/{placa}', [DispositivoController::class, 'verificarPlaca'])->name('dispositivos.verificar');
    Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
    Route::get('/reportes/exportar', [ReporteController::class, 'exportar'])->name('reportes.exportar');

    });