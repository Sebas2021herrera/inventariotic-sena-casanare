<?php
/**
 * Script de actualización masiva de dispositivos de conectividad
 * Fuente: inventario HUAWEI Despacho / Paz de Ariporo / Monterrey
 */

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Dispositivo;
use App\Models\Sede;
use App\Models\Ubicacion;
use Illuminate\Support\Facades\DB;

// ── Mapeo SEDE del Excel → nombre en la BD ─────────────────────────────────
$sedeMap = [
    'DESPACHO'       => 'Yopal',
    'PAZ DE ARIPORO' => 'Paz De Ariporo',
    'MONTERREY'      => 'Monterrey',
];

// ── Descripción técnica genérica por modelo ────────────────────────────────
$descAP5760 = 'AP INDOOR AIRENGINE5760-51 (11AX INDOOR, 2+4 DUAL BANDS, SMART ANTENNA, USB, IOT SLOT, BLE)';
$descAP6760 = 'AP OUTDOOR AIRENGINE6760R-51 (11AX OUTDOOR, 4+4 DUAL BANDS, SMART ANTENNA, BLE)';

// ── Datos del Excel ────────────────────────────────────────────────────────
// Campos: [placa, serial, marca, modelo, descripcion_tecnica, mac, puertos, ubicacion, sede, ap_conectado_a, puerto_origen]
// Para dispositivos sin placa se usa el serial como placa (prefijado con S-)
$datos = [
 // ── DESPACHO (Yopal) ──────────────────────────────────────────────────────
 ['92101044895','1024B6108737','HUAWEI','S6730-H48X6C-V2','SWITCH CORE 48P S6730-H48X6C-V2 (48*10GE SFP+, 6*40GE QSFP28). PART NUMBER: 02354HHT','N/A',48,'MDF','DESPACHO','',''],
 ['92101045858','4E24A0095717','HUAWEI','S5735-S48PN4XE-V2','ID170A_IDF1_SW1_S5735_48','6C71D262DE10',48,'IDF1','DESPACHO','ID170A_IDF1_SW1_S5735_48',''],
 ['AP-CO-26','1024A7204684','HUAWEI','AIRENGINE5760-51',$descAP5760,'6C71D265E6E0',null,'COCINA','DESPACHO','ID170A_MDF_SW1_S5735_24',''],
 ['AP-C101-006','1024A7677227','HUAWEI','AIRENGINE5760-51',$descAP5760,'EC7C2C07CBB0',null,'AULA C101','DESPACHO','ID170A_IDF1_SW1_S5735_48','43'],
 ['AP-C104-31','1024A7677165','HUAWEI','AIRENGINE5760-51',$descAP5760,'EC7C2C07BC30',null,'AULA C104','DESPACHO','ID170A_IDF1_SW1_S5735_48','42'],
 ['AP-CL-007','1024A7677220','HUAWEI','AIRENGINE5760-51',$descAP5760,'EC7C2C07C9F0',null,'COMPETENCIAS LABORALES','DESPACHO','ID170A_IDF1_SW1_S5735_48','48'],
 ['AP-D101-011','1024A7676858','HUAWEI','AIRENGINE5760-51',$descAP5760,'EC7C2C076F70',null,'AULA D101','DESPACHO','ID170A_IDF1_SW1_S5735_48','47'],
 ['AP-D102-27','1024A7204685','HUAWEI','AIRENGINE5760-51',$descAP5760,'6C71D265E720',null,'AULA D102','DESPACHO','ID170A_IDF1_SW1_S5735_48','45'],
 ['AP-D105-30','1024A7204691','HUAWEI','AIRENGINE5760-51',$descAP5760,'6C71D265E8A0',null,'AULA D105','DESPACHO','ID170A_IDF1_SW1_S5735_48','46'],
 ['AP-D104-29','1024A7204598','HUAWEI','AIRENGINE5760-51',$descAP5760,'6C71D265D160',null,'AULA D104','DESPACHO','ID170A_IDF1_SW1_S5735_48','41'],
 ['AP-E105-35','1024A7677164','HUAWEI','AIRENGINE5760-51',$descAP5760,'EC7C2C07BBF0',null,'AULA E105','DESPACHO','ID170A_MDF_SW1_S5735_24',''],
 ['AP-CA-009','1024A7677230','HUAWEI','AIRENGINE5760-51',$descAP5760,'EC7C2C07CC70',null,'COORDINACION ACADEMICA','DESPACHO','ID170A_IDF2_SW1_S5735_48',''],
 ['92101045117','4E2460014953','HUAWEI','S5735-S24PN4XE-V2','ID170A_IDF3_SW1_S5735_24','1C3CD411D5B0',24,'IDF3 - ENFERMERIA','DESPACHO','ID170A_IDF3_SW1_S5735_24',''],
 ['AP-F101-25','1024A7204720','HUAWEI','AIRENGINE5760-51',$descAP5760,'6C71D265EFE0',null,'F101 - DEPORTES','DESPACHO','ID170A_MDF_SW1_S5735_48','PP6-D08-SW1-PTO47'],
 ['AP-F102-24','1024A7677027','HUAWEI','AIRENGINE5760-51',$descAP5760,'EC7C2C0799B0',null,'F102','DESPACHO','ID170A_IDF3_SW1_S5735_24','23'],
 ['AP-G101-23','1024A7677222','HUAWEI','AIRENGINE5760-51',$descAP5760,'EC7C2C07CA70',null,'G101 - ENFERMERIA','DESPACHO','ID170A_IDF3_SW1_S5735_24','22'],
 ['AP-AGRO-35','1024A7677169','HUAWEI','AIRENGINE5760-51',$descAP5760,'EC7C2C07BD30',null,'AGROINDUSTRIA','DESPACHO','ID170A_IDF3_SW1_S5735_24','21'],
 ['92101045370','4E2440154191','HUAWEI','S5735-S24PN4XE-V2','ID170A_IDF4_SW1_S5735_24','148C4A0F4320',24,'IDF4 - ELECTRONICA BLOQUE I','DESPACHO','ID170A_IDF4_SW1_S5735_24',''],
 ['AP-I-013','1024A7677231','HUAWEI','AIRENGINE5760-51',$descAP5760,'EC7C2C07CCB0',null,'AULA I101','DESPACHO','ID170A_IDF4_SW1_S5735_24','22'],
 ['AP-BLI-010','1024A7677030','HUAWEI','AIRENGINE5760-51',$descAP5760,'EC7C2C079A70',null,'AULA I102','DESPACHO','ID170A_IDF4_SW1_S5735_24','20'],
 ['AP-H101-28','1024A7204597','HUAWEI','AIRENGINE5760-51',$descAP5760,'6C71D265D120',null,'H101','DESPACHO','ID170A_IDF4_SW1_S5735_24','21'],
 ['AP-K101-32','1024A7677170','HUAWEI','AIRENGINE5760-51',$descAP5760,'EC7C2C07BD70',null,'AULA K101','DESPACHO','ID170A_IDF4_SW1_S5735_24','24'],
 ['AP-K102-33','1024A7677166','HUAWEI','AIRENGINE5760-51',$descAP5760,'EC7C2C07BC70',null,'AULA K102','DESPACHO','ID170A_IDF4_SW1_S5735_24','23'],
 ['AP-SOL-34','1024A7677171','HUAWEI','AIRENGINE5760-51',$descAP5760,'EC7C2C07BDB0',null,'SOLDADURA - METALMECANICA','DESPACHO','ID170A_IDF4_SW1_S5735_24','13 / PP1-D13'],
 ['92101045904','4E2460008409','HUAWEI','S5735-S48PN4XE-V2','ID170A_IDF5_SW1_S5735_48','1C3CD4E05EB0',48,'IDF5 - AUDITORIO','DESPACHO','ID170A_IDF5_SW1_S5735_48',''],
 ['AP-AUD-002','1024A7677213','HUAWEI','AIRENGINE5760-51',$descAP5760,'EC7C2C07C830',null,'AUDITORIO','DESPACHO','ID170A_IDF5_SW1_S5735_48','46'],
 ['AP-APE-003','1024A7677229','HUAWEI','AIRENGINE5760-51',$descAP5760,'EC7C2C07CC30',null,'APE','DESPACHO','ID170A_IDF5_SW1_S5735_48','48'],
 ['AP-FE-004','1024A7677228','HUAWEI','AIRENGINE5760-51',$descAP5760,'EC7C2C07CBF0',null,'FONDO EMPRENDER','DESPACHO','ID170A_IDF5_SW1_S5735_48','47'],
 ['AP-AC-005','1024A7677216','HUAWEI','AIRENGINE5760-51',$descAP5760,'EC7C2C07C8F0',null,'ATENCION AL CIUDADANO','DESPACHO','ID170A_IDF5_SW1_S5735_48','45'],
 ['92101045463','4E2450211587','HUAWEI','S5735-S24PN4XE-V2','ID170A_IDF6_SW1_S5735_24','6881E0AA20C0',24,'IDF6 - BIBLIOTECA','DESPACHO','ID170A_IDF6_SW1_S5735_24',''],
 ['AP-BIB-012','1024A7677028','HUAWEI','AIRENGINE5760-51',$descAP5760,'EC7C2C0799F0',null,'BIBLIOTECA','DESPACHO','ID170A_IDF6_SW1_S5735_24','24'],
 ['92101045856','4E24A0095745','HUAWEI','S5735-S48PN4XE-V2','ID170A_IDF7_SW1_S5735_48','6C71D262DDE0',48,'IDF7 - BLOQUE M','DESPACHO','ID170A_IDF7_SW1_S5735_48',''],
 ['AP-M101-15','1024A7205492','HUAWEI','AIRENGINE5760-51',$descAP5760,'6C71D26582D0',null,'M101','DESPACHO','ID170A_IDF7_SW1_S5735_48','41'],
 ['AP-M102-16','1024A7205491','HUAWEI','AIRENGINE5760-51',$descAP5760,'6C71D2658290',null,'M102','DESPACHO','ID170A_IDF7_SW1_S5735_48','42'],
 ['AP-M103-17','1024A7205487','HUAWEI','AIRENGINE5760-51',$descAP5760,'6C71D2658190',null,'M103','DESPACHO','ID170A_IDF7_SW1_S5735_48','46'],
 ['AP-M104-18','1024A7205488','HUAWEI','AIRENGINE5760-51',$descAP5760,'6C71D26581D0',null,'M104','DESPACHO','ID170A_IDF7_SW1_S5735_48','45'],
 ['AP-M105-19','1024A7205485','HUAWEI','AIRENGINE5760-51',$descAP5760,'6C71D2658110',null,'M105','DESPACHO','ID170A_IDF7_SW1_S5735_48','44'],
 ['AP-M106-20','1024A7204717','HUAWEI','AIRENGINE5760-51',$descAP5760,'6C71D265EF20',null,'M106','DESPACHO','ID170A_IDF7_SW1_S5735_48','48'],
 ['AP-M107-21','1024A7204707','HUAWEI','AIRENGINE5760-51',$descAP5760,'6C71D265ECA0',null,'M107','DESPACHO','ID170A_IDF7_SW1_S5735_48','47'],
 ['AP-M108-22','1024A7205486','HUAWEI','AIRENGINE5760-51',$descAP5760,'6C71D2658150',null,'M108','DESPACHO','ID170A_IDF7_SW1_S5735_48','43'],
 ['92101045890','4E2450268484','HUAWEI','S5735-S48PN4XE-V2','SWITCH ACCESO 48P S5735-S48PN4XE-V2 (48*10/100/1000/2.5G PoE+, 4*10GE SFP+). PART NUMBER: 98012399','1C3CD4024860',48,'IDF2 - ADMINISTRACION','DESPACHO','',''],
 ['92101045900','4E2460008322','HUAWEI','S5735-S48PN4XE-V2','SWITCH ACCESO 48P S5735-S48PN4XE-V2 (48*10/100/1000/2.5G PoE+, 4*10GE SFP+). PART NUMBER: 98012399','1C3CD4E05F10',48,'MDF','DESPACHO','',''],
 ['AP-ADM-001','1024A7677221','HUAWEI','AIRENGINE5760-51',$descAP5760,'EC7C2C07CA30',null,'ADMINISTRACION','DESPACHO','',''],
 ['AP-ALM-008','1024A7677026','HUAWEI','AIRENGINE5760-51',$descAP5760,'EC7C2C079970',null,'ALMACEN','DESPACHO','',''],
 ['AP-HC-014','1024A7677223','HUAWEI','AIRENGINE5760-51',$descAP5760,'EC7C2C07CAB0',null,'SALA INSTRUCTORES - HIDROCARBUROS','DESPACHO','',''],
 // Sin placa → usar serial prefijado
 ['SIN-1024A7677168','1024A7677168','HUAWEI','AIRENGINE5760-51',$descAP5760,'EC7C2C07BCF0',null,'','MONTERREY','',''],
 ['SIN-1024A7677167','1024A7677167','HUAWEI','AIRENGINE5760-51',$descAP5760,'EC7C2C07BCB0',null,'','MONTERREY','',''],

 // ── PAZ DE ARIPORO ─────────────────────────────────────────────────────────
 ['92101045520','4E2460015222','HUAWEI','S5735-S24PN4XE-V2','SWITCH ACCESO 24P S5735-S24PN4XE-V2 (24*10/100/1000/2.5G PoE+, 4*10GE SFP+). PART NUMBER: 98012400','1C3CD411EAB0',24,'IDF2','PAZ DE ARIPORO','',''],
 ['AP-PZ-001','1024A7204902','HUAWEI','AIRENGINE5760-51',$descAP5760,'6C71D2661D60',null,'','PAZ DE ARIPORO','',''],
 ['AP-PZ-002','1024A7204909','HUAWEI','AIRENGINE5760-51',$descAP5760,'6C71D2661F20',null,'','PAZ DE ARIPORO','',''],
 ['AP-PZ-003','1024A7204908','HUAWEI','AIRENGINE5760-51',$descAP5760,'6C71D2661EE0',null,'','PAZ DE ARIPORO','',''],
 ['SIN-1024A7205324','1024A7205324','HUAWEI','AIRENGINE5760-51',$descAP5760,'6C71D26558D0',null,'AGROINDUSTRIA','PAZ DE ARIPORO','',''],
 ['SIN-1024A7006994','1024A7006994','HUAWEI','AIRENGINE5760-51',$descAP5760,'6C71D23016C0',null,'MUSICA','PAZ DE ARIPORO','',''],
 ['SIN-1024A7006930','1024A7006930','HUAWEI','AIRENGINE5760-51',$descAP5760,'6C71D23006C0',null,'OPERACION TURISTICA','PAZ DE ARIPORO','',''],
 ['SIN-1024A7007005','1024A7007005','HUAWEI','AIRENGINE5760-51',$descAP5760,'6C71D2301980',null,'ATENCION AL CIUDADANO','PAZ DE ARIPORO','',''],
 ['SIN-1024A7007001','1024A7007001','HUAWEI','AIRENGINE5760-51',$descAP5760,'6C71D2301880',null,'BLOQUE A105','PAZ DE ARIPORO','',''],
 ['SIN-1024A7007009','1024A7007009','HUAWEI','AIRENGINE5760-51',$descAP5760,'6C71D2301A80',null,'LABORATORIO AGROINDUSTRIAL 101','PAZ DE ARIPORO','',''],
 ['SIN-1024A7007038','1024A7007038','HUAWEI','AIRENGINE5760-51',$descAP5760,'6C71D23021C0',null,'BLOQUE H104','PAZ DE ARIPORO','',''],
 ['SIN-1024A7007035','1024A7007035','HUAWEI','AIRENGINE5760-51',$descAP5760,'6C71D2302100',null,'SIMULADORES SOLARES H-101','PAZ DE ARIPORO','',''],
 ['SIN-1024A7007036','1024A7007036','HUAWEI','AIRENGINE5760-51',$descAP5760,'6C71D2302140',null,'LABORATORIO AMBIENTAL','PAZ DE ARIPORO','P1-MDF-R1-PP2-D05-SW1-D11',''],
 ['SIN-1024A7007039','1024A7007039','HUAWEI','AIRENGINE5760-51',$descAP5760,'6C71D2302200',null,'ULTIMO SALON','PAZ DE ARIPORO','',''],
 ['92101052155','6R2460010385','HUAWEI','AIRENGINE6760R-51',$descAP6760,'D86D17BB2D20',null,'AUDITORIO','PAZ DE ARIPORO','',''],
 ['92101052171','6R2460010352','HUAWEI','AIRENGINE6760R-51',$descAP6760,'D86D17BB2900',null,'CARNICOS','PAZ DE ARIPORO','',''],
 ['92101052174','6R2460010291','HUAWEI','AIRENGINE6760R-51',$descAP6760,'D86D17BB2160',null,'BLOQUE H - SIMULADORES','PAZ DE ARIPORO','PP2-D06-SW1-D02-ID2',''],
 ['92101045216','4E2450161004','HUAWEI','S5735-S24PN4XE-V2','','9490106877D0',24,'IDF1','PAZ DE ARIPORO','',''],
 ['92101046256','4E2450268787','HUAWEI','S5735-S48PN4XE-V2','','1C3CD4025560',48,'MDF','PAZ DE ARIPORO','',''],
 ['92101045207','4E2460014918','HUAWEI','S5735-S24PN4XE-V2','','1C3CD411DA90',24,'IDF2','PAZ DE ARIPORO','',''],
 ['92101045198','4E2460015288','HUAWEI','S5735-S24PN4XE-V2','','1C3CD411E810',24,'IDF3','PAZ DE ARIPORO','',''],

 // ── MONTERREY ──────────────────────────────────────────────────────────────
 ['92101045223','4E2450160914','HUAWEI','S5735-S24PN4XE-V2','','949010688C60',24,'','MONTERREY','',''],
 ['92101044970','6R24B0002073','HUAWEI','S6730-H24X6C-V2','','78DD33DE60A0',24,'','MONTERREY','',''],
 ['92101045234','4E2460015223','HUAWEI','S5735-S24PN4XE-V2','','1C3CD411E370',24,'','MONTERREY','',''],
 ['92101045233','4E2460023167','HUAWEI','S5735-S24PN4XE-V2','','1C3CD40B37F0',24,'','MONTERREY','',''],
 ['92101045241','4E2460015280','HUAWEI','S5735-S24PN4XE-V2','','1C3CD411E900',24,'','MONTERREY','',''],
 ['92101052175','6R2460010389','HUAWEI','AIRENGINE6760R-51',$descAP6760,'D86D17BB2DA0',null,'','MONTERREY','',''],
 ['92101052345','6R2450002257','HUAWEI','AIRENGINE6760R-51',$descAP6760,'98F08315E420',null,'','MONTERREY','',''],
 ['92101052180','6R2460010386','HUAWEI','AIRENGINE6760R-51',$descAP6760,'D86D17BB2D40',null,'','MONTERREY','',''],
];

// ── Responsable por defecto (el que ya usan los existentes) ─────────────────
$defaultResponsableId = 8;
$defaultCreatedBy     = 1;

// ── Ejecutar ────────────────────────────────────────────────────────────────
$creados     = 0;
$actualizados = 0;
$errores     = [];

DB::transaction(function() use (
    $datos, $sedeMap, $defaultResponsableId, $defaultCreatedBy,
    &$creados, &$actualizados, &$errores
) {
    foreach ($datos as $row) {
        [$placa, $serial, $marca, $modelo, $descTecnica, $mac, $puertos,
         $ubicNombre, $sedeExcel, $apConectado, $puertoOrigen] = $row;

        try {
            // Sede
            $sedeNombre = $sedeMap[$sedeExcel] ?? $sedeExcel;
            $sede = Sede::firstOrCreate(['nombre' => $sedeNombre]);

            // Ubicacion
            $ambiente = mb_strtoupper(trim($ubicNombre)) ?: 'GENERAL';
            $ubicacion = Ubicacion::firstOrCreate(
                ['sede_id' => $sede->id, 'bloque' => '', 'ambiente' => $ambiente]
            );

            // Dispositivo
            $existe = Dispositivo::where('placa', $placa)->first();

            $campos = [
                'serial'            => $serial,
                'marca'             => $marca,
                'modelo'            => $modelo,
                'categoria'         => 'conectividad',
                'mac_address'       => ($mac && $mac !== 'N/A') ? $mac : null,
                'puertos'           => $puertos,
                'descripcion_tecnica' => $descTecnica ?: null,
                'ap_conectado_a'    => $apConectado ?: null,
                'puerto_origen'     => $puertoOrigen ?: null,
                'ubicacion_id'      => $ubicacion->id,
                'estado_fisico'     => 'Bueno',
                'estado_logico'     => 'Bueno',
                'propietario'       => 'SENA',
                'funcion'           => 'FORMACION',
                'en_intune'         => 'NO',
            ];

            if ($existe) {
                $existe->update($campos);
                $actualizados++;
            } else {
                Dispositivo::create(array_merge($campos, [
                    'placa'          => $placa,
                    'responsable_id' => $defaultResponsableId,
                    'created_by'     => $defaultCreatedBy,
                ]));
                $creados++;
            }
        } catch (\Exception $e) {
            $errores[] = "[{$placa}] " . $e->getMessage();
        }
    }
});

echo "✓ Actualizados: {$actualizados}\n";
echo "✓ Creados:      {$creados}\n";
echo "Total procesados: " . ($actualizados + $creados) . " / " . count($datos) . "\n";

if ($errores) {
    echo "\n⚠ Errores (" . count($errores) . "):\n";
    foreach ($errores as $e) echo "  {$e}\n";
} else {
    echo "\n✓ Sin errores.\n";
}
