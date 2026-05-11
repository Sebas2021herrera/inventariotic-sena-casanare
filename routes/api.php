<?php

/**
 * ╔══════════════════════════════════════════════════════════════╗
 * ║  GITIC API — v1                                              ║
 * ║  Base URL: https://gitic.softhardtec.org/api/v1             ║
 * ║                                                              ║
 * ║  Autenticación:                                              ║
 * ║    1. POST /api/v1/auth/token  → obtener token Bearer        ║
 * ║    2. Añadir header en cada request:                         ║
 * ║       Authorization: Bearer <token>                          ║
 * ║    3. DELETE /api/v1/auth/token  → revocar token             ║
 * ╚══════════════════════════════════════════════════════════════╝
 *
 * Respuesta estándar:
 * {
 *   "success": true | false,
 *   "message": "descripción",
 *   "data": { ... } | [ ... ] | null
 * }
 *
 * Códigos HTTP usados:
 *   200 OK             → consulta exitosa
 *   201 Created        → recurso creado (token generado)
 *   401 Unauthorized   → sin token o token inválido
 *   403 Forbidden      → token válido pero sin permisos
 *   404 Not Found      → recurso no existe
 *   422 Unprocessable  → validación fallida
 *   429 Too Many Req.  → rate limit superado
 */

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthApiController;
use App\Http\Controllers\Api\V1\DispositivoApiController;
use App\Http\Controllers\Api\V1\EstadisticasApiController;

// ── Versión 1 ─────────────────────────────────────────────────────────────────
Route::prefix('v1')->name('api.v1.')->group(function () {

    // ── Autenticación (pública — sin token requerido) ──────────────────────────
    Route::prefix('auth')->name('auth.')->group(function () {
        Route::post('token',  [AuthApiController::class, 'generarToken'])->name('token');
    });

    // ── Endpoints protegidos — requieren Bearer token ──────────────────────────
    Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {

        // Sesión
        Route::get ('auth/me',    [AuthApiController::class, 'me'])->name('auth.me');
        Route::delete('auth/token', [AuthApiController::class, 'revocarToken'])->name('auth.revoke');

        // Dispositivos
        Route::get('dispositivos',        [DispositivoApiController::class, 'index'])->name('dispositivos.index');
        Route::get('dispositivos/{placa}', [DispositivoApiController::class, 'show'])->name('dispositivos.show');

        // Estadísticas
        Route::get('estadisticas', [EstadisticasApiController::class, 'index'])->name('estadisticas');
    });
});
