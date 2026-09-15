<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Dispositivo;

// 1. Encontrar todos los hostnames duplicados
$dups = DB::select("
    SELECT hostname, COUNT(*) as cnt
    FROM dispositivos
    WHERE hostname IS NOT NULL
    GROUP BY hostname
    HAVING COUNT(*) > 1
    ORDER BY hostname
");

if (empty($dups)) {
    echo "No hay duplicados. Nada que hacer.\n";
    exit(0);
}

echo "Hostnames duplicados encontrados: " . count($dups) . "\n\n";

foreach ($dups as $dup) {
    $hostname = $dup->hostname;

    // Tomar los que tienen este hostname, ordenados por ID (el menor es el "original")
    $dispositivos = Dispositivo::where('hostname', $hostname)
        ->orderBy('id')
        ->get();

    // El primero (menor ID) se queda como está. Los siguientes se renombran.
    $primero = $dispositivos->shift();
    echo "ORIGINAL  [{$primero->id}] placa={$primero->placa} → hostname={$hostname} (se conserva)\n";

    $prefijo   = substr($hostname, 0, -3);
    $longTotal = strlen($hostname);

    foreach ($dispositivos as $d) {
        // Buscar el siguiente número libre
        $ultimo = Dispositivo::where('hostname', 'LIKE', "{$prefijo}%")
            ->whereRaw('LENGTH(hostname) = ?', [$longTotal])
            ->orderByRaw("CAST(RIGHT(hostname, 3) AS INTEGER) DESC")
            ->value('hostname');

        $siguiente = $ultimo ? ((int) substr($ultimo, -3)) + 1 : 1;
        $nuevoHostname = $prefijo . str_pad($siguiente, 3, '0', STR_PAD_LEFT);

        $d->hostname = $nuevoHostname;
        $d->save();

        echo "RENOMBRADO [{$d->id}] placa={$d->placa} → {$hostname} → {$nuevoHostname}\n";
    }
    echo "\n";
}

echo "✓ Duplicados resueltos.\n";
