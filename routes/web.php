<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DispositivoController;
use App\Http\Controllers\ResponsableController;

Route::get('/', function () {
    return view('welcome');

});// Rutas para el Inventario
Route::resource('dispositivos', DispositivoController::class);
Route::resource('responsables', ResponsableController::class);

// Ruta especial para la importación masiva del archivo CSV
Route::post('importar-inventario', [DispositivoController::class, 'importar'])->name('dispositivos.importar');

Route::get('/responsables/buscar/{cedula}', function($cedula) {
    $responsable = \App\Models\Responsable::where('cedula', $cedula)->first();
    return response()->json($responsable);
})->name('responsables.buscar');

Route::get('/descargar-plantilla', [DispositivoController::class, 'descargarPlantilla'])->name('dispositivos.plantilla');
Route::delete('/dispositivos/{dispositivo}', [DispositivoController::class, 'destroy'])->name('dispositivos.destroy');