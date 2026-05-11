<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Autenticación de la API mediante tokens Sanctum.
 *
 * Flujo:
 *   1. El desarrollador hace POST /api/v1/auth/token con email + password
 *   2. Recibe un token personal (Bearer)
 *   3. Incluye ese token en todas las requests: Authorization: Bearer <token>
 *   4. Al terminar, hace DELETE /api/v1/auth/token para revocar
 */
class AuthApiController extends Controller
{
    /**
     * POST /api/v1/auth/token
     * Genera un token de acceso personal.
     */
    public function generarToken(Request $request)
    {
        $request->validate([
            'email'       => ['required', 'email'],
            'password'    => ['required'],
            'device_name' => ['required', 'string', 'max:100'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Credenciales incorrectas.',
                'data'    => null,
            ], 401);
        }

        $token = $user->createToken($request->device_name)->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Token generado correctamente.',
            'data'    => [
                'token'      => $token,
                'token_type' => 'Bearer',
                'user'       => [
                    'name'  => $user->name,
                    'email' => $user->email,
                    'role'  => $user->role,
                ],
            ],
        ], 201);
    }

    /**
     * DELETE /api/v1/auth/token
     * Revoca el token actual (logout de API).
     */
    public function revocarToken(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Token revocado correctamente.',
            'data'    => null,
        ]);
    }

    /**
     * GET /api/v1/auth/me
     * Devuelve el usuario autenticado.
     */
    public function me(Request $request)
    {
        return response()->json([
            'success' => true,
            'data'    => [
                'name'  => $request->user()->name,
                'email' => $request->user()->email,
                'role'  => $request->user()->role,
            ],
        ]);
    }
}
