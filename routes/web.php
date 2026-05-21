<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DispositivoController;
use App\Http\Controllers\ResponsableController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MantenimientoController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\ConceptoTecnicoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AreaSeguraController;
use App\Http\Controllers\EquipoEnergiaController;
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
    
    // Rutas estáticas ANTES de los resources (evitan que los wildcards las capturen)
    Route::get('/dispositivos/generar-hostname', [DispositivoController::class, 'generarHostname'])->name('dispositivos.generar-hostname');
    Route::get('/responsables/buscar-nombre', [ResponsableController::class, 'buscarPorNombre'])->name('responsables.buscar-nombre');
    Route::get('/responsables/buscar/{cedula}', [ResponsableController::class, 'buscar'])->name('responsables.buscar');
    Route::get('/responsables/{responsable}/reporte-pdf', [ResponsableController::class, 'reportePDF'])->name('responsables.reporte-pdf');
    Route::get('/dispositivos/verificar-placa/{placa}', [DispositivoController::class, 'verificarPlaca'])->name('dispositivos.verificar');

    // Recursos principales
    Route::resource('dispositivos', DispositivoController::class);
    Route::resource('responsables', ResponsableController::class);

    Route::resource('mantenimientos', MantenimientoController::class);
    Route::get('/mantenimientos/{mantenimiento}/pdf', [MantenimientoController::class, 'exportarPDF'])->name('mantenimientos.pdf');

    // Funcionalidades de Inventario SENA
    Route::post('importar-inventario', [DispositivoController::class, 'importar'])->name('dispositivos.importar');
    Route::get('descargar-plantilla', [DispositivoController::class, 'descargarPlantilla'])->name('dispositivos.plantilla');
    Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
    Route::get('/reportes/ubicacion-stats', [ReporteController::class, 'ubicacionStats'])->name('reportes.ubicacion-stats');
    Route::get('/reportes/exportar', [ReporteController::class, 'exportar'])->name('reportes.exportar')->middleware('admin');

    Route::get('/dispositivos/{dispositivo}/concepto/nuevo', [ConceptoTecnicoController::class, 'create'])->name('conceptos.create');
    Route::post('/conceptos/guardar', [ConceptoTecnicoController::class, 'store'])->name('conceptos.store');
    Route::get('/dispositivos/{dispositivo}/hoja-vida-pdf', [DispositivoController::class, 'hojaVidaPDF'])->name('dispositivos.hoja-vida-pdf');
    Route::get('/conceptos/{id}/editar', [ConceptoTecnicoController::class, 'edit'])->name('conceptos.edit');
    Route::put('/conceptos/{id}', [ConceptoTecnicoController::class, 'update'])->name('conceptos.update');
    Route::get('/conceptos/{id}/pdf', [ConceptoTecnicoController::class, 'exportarPDF'])->name('conceptos.pdf');

    Route::get('/perfil/cambiar-clave', [AuthController::class, 'showCambiarClave'])->name('perfil.cambiar-clave');
    Route::post('/perfil/cambiar-clave', [AuthController::class, 'cambiarClave']);

    // Energía Regulada
    Route::resource('equipos-energia', EquipoEnergiaController::class);

    // Áreas Seguras — ISO 27001:2022
    Route::resource('areas-seguras', AreaSeguraController::class);
    Route::get('areas-seguras/{areasSegura}/verificacion', [AreaSeguraController::class, 'crearVerificacion'])->name('areas-seguras.verificacion.create');
    Route::post('areas-seguras/{areasSegura}/verificacion', [AreaSeguraController::class, 'guardarVerificacion'])->name('areas-seguras.verificacion.store');

    // Solo admin
    Route::middleware('admin')->group(function () {
        Route::resource('usuarios', UsuarioController::class)->only(['index', 'create', 'store', 'destroy']);
    });

    });