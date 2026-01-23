<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DispositivoController;
use App\Http\Controllers\ResponsableController;
use App\Http\Controllers\AuthController;

// Redirección externa: si alguien entra a dominio.com/ lo mandamos a /gitic
Route::get('/', function () {
    return redirect('/gitic');
});

Route::prefix('gitic')->group(function () {

    // Rutas de Autenticación
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // Rutas Protegidas
    Route::middleware(['auth'])->group(function () {
        
        // Redirección interna: de /gitic a /gitic/dispositivos
        Route::get('/', function () {
            return redirect()->route('dispositivos.index');
        });

        Route::resource('dispositivos', DispositivoController::class);
        Route::resource('responsables', ResponsableController::class);
        
        // Funcionalidades adicionales
        Route::post('importar-inventario', [DispositivoController::class, 'importar'])->name('dispositivos.importar');
        Route::get('descargar-plantilla', [DispositivoController::class, 'descargarPlantilla'])->name('dispositivos.plantilla');

        Route::get('/responsables/buscar/{cedula}', [ResponsableController::class, 'buscar'])->name('responsables.buscar');
        // Verificación de placa en tiempo real
        Route::get('/dispositivos/verificar-placa/{placa}', [DispositivoController::class, 'verificarPlaca'])->name('dispositivos.verificar');
    });
});