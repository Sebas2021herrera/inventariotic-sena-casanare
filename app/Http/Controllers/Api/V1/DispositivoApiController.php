<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\DispositivoResource;
use App\Models\Dispositivo;
use Illuminate\Http\Request;

/**
 * API de Dispositivos — v1
 *
 * Todos los endpoints requieren: Authorization: Bearer <token>
 */
class DispositivoApiController extends Controller
{
    /**
     * GET /api/v1/dispositivos
     *
     * Parámetros de query (todos opcionales):
     *   ?search=     busca en placa, serial, hostname
     *   ?estado=     Bueno | Regular | Malo | En Reparación
     *   ?categoria=  computo | conectividad | impresora | servidor
     *   ?intune=     SI | NO
     *   ?sede=       nombre de la sede
     *   ?per_page=   registros por página (máx 100, def 15)
     */
    public function index(Request $request)
    {
        $query = Dispositivo::with(['responsable', 'ubicacion.sede']);

        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('placa',    'ILIKE', "%{$search}%")
                  ->orWhere('serial',   'ILIKE', "%{$search}%")
                  ->orWhere('hostname', 'ILIKE', "%{$search}%");
            });
        }

        if ($request->estado)    $query->where('estado_fisico', $request->estado);
        if ($request->categoria) $query->where('categoria', $request->categoria);
        if ($request->intune)    $query->where('en_intune', $request->intune);
        if ($request->sede) {
            $query->whereHas('ubicacion.sede',
                fn($q) => $q->where('nombre', 'ILIKE', "%{$request->sede}%")
            );
        }

        $perPage = min((int) ($request->per_page ?? 15), 100);
        $paginator = $query->orderBy('placa')->paginate($perPage);

        return DispositivoResource::collection($paginator)
            ->additional([
                'success' => true,
                'message' => 'OK',
            ]);
    }

    /**
     * GET /api/v1/dispositivos/{placa}
     *
     * Busca por placa SENA. Incluye responsable, ubicación y especificaciones.
     */
    public function show(string $placa)
    {
        $dispositivo = Dispositivo::with([
                'responsable',
                'ubicacion.sede',
                'especificaciones',
            ])
            ->where('placa', $placa)
            ->first();

        if (!$dispositivo) {
            return response()->json([
                'success' => false,
                'message' => "Dispositivo con placa '{$placa}' no encontrado.",
                'data'    => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'OK',
            'data'    => new DispositivoResource($dispositivo),
        ]);
    }
}
