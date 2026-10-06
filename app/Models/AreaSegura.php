<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AreaSegura extends Model
{
    protected $table = 'areas_seguras';

    protected $fillable = [
        'sede_id', 'codigo', 'nombre_dependencia',
        'nivel_sena', 'nivel_criticidad', 'tipo_area',
        'fecha_inventario',
        'responsable_cargo', 'responsable_nombre', 'responsable_contacto',
        'bloque', 'piso', 'numero_oficina',
        'perimetro_seguridad',
        'controles_acceso', 'controles_monitoreo',
        'horario_acceso',
        'estado_area',
        'historico_cambios',
        'clasificacion_informacion',
        'zonificacion',
        'descripcion',
        'activa',
        'created_by', 'updated_by',
    ];

    // GIL: Nivel 1 = bajo riesgo, Nivel 2 = medio, Nivel 3 = alto riesgo
    const NIVELES_SENA = [
        'Nivel 1' => [
            'color'       => 'blue',
            'icono'       => 'fa-building',
            'acceso'      => 'Acceso Controlado',
            'descripcion' => 'Áreas de bajo riesgo con acceso controlado',
            'ejemplos'    => ['Oficinas administrativas', 'Salas de reuniones', 'Áreas de atención al público'],
        ],
        'Nivel 2' => [
            'color'       => 'orange',
            'icono'       => 'fa-network-wired',
            'acceso'      => 'Acceso Restringido',
            'descripcion' => 'Áreas de riesgo medio con acceso restringido',
            'ejemplos'    => ['Cuartos de telecomunicaciones', 'MDF / IDF', 'Salas técnicas de equipos'],
        ],
        'Nivel 3' => [
            'color'       => 'red',
            'icono'       => 'fa-server',
            'acceso'      => 'Alta Seguridad',
            'descripcion' => 'Áreas de alto riesgo con seguridad máxima',
            'ejemplos'    => ['Centros de Datos (Data Center)', 'SOC / NOC', 'Archivo Central', 'Bóveda de títulos y certificaciones'],
        ],
    ];

    const ZONAS = [
        'Zona 0 - Pública'        => 'Recepción, acceso libre al público',
        'Zona 1 - Controlada'     => 'Oficinas, requiere identificación',
        'Zona 2 - Restringida'    => 'Telecom, acceso solo personal autorizado',
        'Zona 3 - Alta Seguridad' => 'Data center, NOC, bóvedas',
    ];

    const CLASIFICACIONES_INFO = [
        'Pública',
        'Pública Clasificada',
        'Pública Reservada',
    ];

    // GIL-F-102 Bloque 2: 15 controles de acceso físico (con descripción exacta del formato)
    const CONTROLES_ACCESO_GIL = [
        ['codigo' => '5.4.1',  'item' => 'Designación del responsable',
         'descripcion' => 'Se tiene definida una o varias personas debidamente formalizadas y notificadas como responsables de las áreas seguras'],
        ['codigo' => '5.4.2',  'item' => 'Perímetros de Seguridad física',
         'descripcion' => 'Se cuenta con barreras físicas como Puertas con cerradura, Control de acceso electrónico, Cerramientos, Señalización visible, Vigilancia física'],
        ['codigo' => '5.4.2',  'item' => 'Mecanismos de Autenticación de Acceso',
         'descripcion' => 'Se usan tarjetas de acceso (proximidad) o sistemas biométricos para restringir el ingreso a áreas críticas (Data Centers, Archivos), llaves controladas entre otros.'],
        ['codigo' => '5.4.3',  'item' => 'Inventario',
         'descripcion' => 'Se cuenta con registro actualizado que detalle: ubicación, nombre del área, responsable directo y tipo de mecanismo de acceso implementado.'],
        ['codigo' => '5.4.4',  'item' => 'Gestión de horarios',
         'descripcion' => 'Se establecen y publican los horarios autorizados para el ingreso, diferenciando entre personal vinculado, proveedores y visitantes externos'],
        ['codigo' => '5.4.5',  'item' => 'Personal Autorizado',
         'descripcion' => 'Se cuenta y mantiene actualizado el Listado de Personal Autorizado por área segura, gestionar las listas de acceso justificadas y revocación inmediata de permisos al finalizar la vinculación contractual o laboral.'],
        ['codigo' => '5.4.6',  'item' => 'Verificación de accesos',
         'descripcion' => 'Se cuenta con planillas de registro (física o digital) obligatoria en los puntos de control para todo ingreso (con la información mínima indicada), asegurar el almacenamiento, digitalización y custodia de las planillas completadas.'],
        ['codigo' => '5.4.7',  'item' => 'Gestión de solicitudes y autorizaciones',
         'descripcion' => 'Se tienen evidencias de los trámites de solicitudes de acceso, verificando la "necesidad de saber" y la vigencia del solicitante antes de autorizar.'],
        ['codigo' => '5.4.8',  'item' => 'Retiro de activos',
         'descripcion' => 'Se cuenta con evidencias de salidas temporales o definitivas de cualquier equipo, elemento o activo de información desde un área segura deberá contar con autorización previa y expresa del responsable del área segura.'],
        ['codigo' => '5.4.9',  'item' => 'Protocolos para visitantes y personal externo',
         'descripcion' => 'El acceso de visitantes o personal externo a las áreas seguras es previamente autorizado, registrado y verificado, con identificación visible y acompañamiento permanente. Su ingreso se limita únicamente a las zonas y tiempo autorizados, dentro del horario establecido.'],
        ['codigo' => '5.4.10', 'item' => 'Infraestructura de Monitoreo (videovigilancia)',
         'descripcion' => 'Se tienen implementados mecanismos de videovigilancia que cubran la totalidad de entradas y salidas de las áreas seguras. Deberán mantenerse en operación permanente y en condiciones adecuadas de funcionamiento.'],
        ['codigo' => '5.4.10', 'item' => 'Infraestructura de Monitoreo (almacenamiento de grabaciones)',
         'descripcion' => 'Se tienen definidos e implementados lineamientos y mecanismos de almacenamiento y disposición seguras de las grabaciones'],
        ['codigo' => '5.4.11', 'item' => 'Trabajo en áreas seguras',
         'descripcion' => 'Se realiza supervisión y/o acompañamiento en las actividades realizadas en las áreas seguras, se tienen publicados los protocolos de emergencia, se restringe el acceso a elementos de equipos de grabación, fotografía, audio'],
        ['codigo' => '5.4.12', 'item' => 'Protocolos de demarcación y señalización',
         'descripcion' => 'Se tiene identificación clara de accesos, zonas restringidas, equipos críticos y cableado, mediante avisos visibles, demarcación física y códigos de color que faciliten el control y mantenimiento.'],
        ['codigo' => '5.4.13', 'item' => 'Cifrado y Protección de Datos',
         'descripcion' => 'Se cuenta con registro de pérdidas de equipos de las áreas seguras, se gestionan los protocolos ante pérdida de elementos'],
    ];

    // GIL-F-102 Bloque 3: Controles medioambientales (categoría + nombre + descripción exactos del formato)
    const CONTROLES_MEDIOAMBIENTALES = [
        '5.5.1 Centros de Datos y Cableado' => [
            ['codigo'=>'5.5.1.a','categoria'=>'Climatización',    'nombre'=>'Control de Temperatura y Climatización',   'descripcion'=>'Se cuenta con aire de precisión (Data Centers) o ventilación forzada (Cableado) para mantener temperatura estable (22 a 25°C).'],
            ['codigo'=>'5.5.1.b','categoria'=>'Ambiental',        'nombre'=>'Control de Humedad',                       'descripcion'=>'Se mantiene la humedad relativa entre 40% y 60% para evitar estática (baja) o corrosión/condensación (alta).'],
            ['codigo'=>'5.5.1.c','categoria'=>'Energía',          'nombre'=>'Suministro Eléctrico Ininterrumpido (UPS)','descripcion'=>'Se usan UPS en áreas críticas para proteger contra picos de voltaje y cortes repentinos.'],
            ['codigo'=>'5.5.1.d','categoria'=>'Energía',          'nombre'=>'Plantas Eléctricas de Respaldo',           'descripcion'=>'Se cuenta con generadores de energía alternos con mantenimiento periódico probado (para sedes principales).'],
            ['codigo'=>'5.5.1.e','categoria'=>'Contra Incendios', 'nombre'=>'Control de Protección Contra Incendios',   'descripcion'=>'Se tiene sensores de humo y calor conectados a un sistema de monitoreo central.'],
            ['codigo'=>'5.5.1.f','categoria'=>'Contra Incendios', 'nombre'=>'Extinción con Agentes Limpios',            'descripcion'=>'Se usan gases (FM-200 o Novec) en lugar de agua para no dañar equipos electrónicos.'],
            ['codigo'=>'5.5.1.g','categoria'=>'Contra Incendios', 'nombre'=>'Red Contraincendios',                      'descripcion'=>'Cuenta con red contraincendios funcional y con mantenimiento al día.'],
            ['codigo'=>'5.5.1.h','categoria'=>'Infraestructura',  'nombre'=>'Materiales Ignífugos',                     'descripcion'=>'Uso de materiales resistentes al fuego en puertas y paredes perimetrales.'],
        ],
        '5.5.2 Archivo de Gestión y Central' => [
            ['codigo'=>'5.5.2.a','categoria'=>'Preservación',    'nombre'=>'Control de Luz Solar',                        'descripcion'=>'Se evita la incidencia directa de luz solar sobre documentos para prevenir envejecimiento y decoloración.'],
            ['codigo'=>'5.5.2.b','categoria'=>'Preservación',    'nombre'=>'Control de Humedad (Documental)',              'descripcion'=>'Se realiza regulación ambiental para evitar proliferación de hongos y bacterias en el papel.'],
            ['codigo'=>'5.5.2.c','categoria'=>'Mantenimiento',   'nombre'=>'Control de Plagas',                            'descripcion'=>'Se cuenta con un cronograma periódico de fumigación contra roedores e insectos xilófagos.'],
            ['codigo'=>'5.5.2.d','categoria'=>'Infraestructura', 'nombre'=>'Control de Prevención de Inundaciones',        'descripcion'=>'Prohibición de ubicar archivos en sótanos inundables o bajo tuberías de agua/desagües.'],
            ['codigo'=>'5.5.2.e','categoria'=>'Infraestructura', 'nombre'=>'Mitigar riesgo de Incendios (tableros)',       'descripcion'=>'Se tiene prohibido el uso de tableros eléctricos al interior de espacios de custodia documental.'],
            ['codigo'=>'5.5.2.f','categoria'=>'Infraestructura', 'nombre'=>'Mitigar riesgo de Incendios (detección)',      'descripcion'=>'Implementar sistemas de detección de incendios por aspiración y el uso de extintores automáticos.'],
            ['codigo'=>'5.5.2.g','categoria'=>'Infraestructura', 'nombre'=>'Mitigar riesgo de Incendios (espacios)',       'descripcion'=>'Se prohíbe que los espacios de trabajo compartan el mismo espacio con los de custodia.'],
            ['codigo'=>'5.5.2.h','categoria'=>'Infraestructura', 'nombre'=>'Mitigar riesgo de caída de estantes',          'descripcion'=>'Se prohíbe que los estantes no estén asegurados al piso.'],
            ['codigo'=>'5.5.2.i','categoria'=>'Infraestructura', 'nombre'=>'Elevación de Estantería',                      'descripcion'=>'La estantería debe estar elevada del piso mínimo 10 cm para proteger contra anegaciones superficiales.'],
            ['codigo'=>'5.5.2.j','categoria'=>'Infraestructura', 'nombre'=>'Control Seguridad del Cableado Eléctrico y de Datos','descripcion'=>'Cableado organizado, canalizado y protegido para evitar desconexiones y riesgos eléctricos (Cumplimiento RETIE).'],
        ],
        '5.5.3 Oficinas y Áreas de Trabajo' => [
            ['codigo'=>'5.5.3.a','categoria'=>'Seguridad Física',    'nombre'=>'Política de Escritorio Limpio y Pantalla Limpia','descripcion'=>'Validar que no existan documentos sensibles expuestos ni contraseñas (post-its) pegadas en monitores.'],
            ['codigo'=>'5.5.3.b','categoria'=>'Seguridad de Activos','nombre'=>'Protección de Equipos de Usuario Final',           'descripcion'=>'Se usan guayas de seguridad para portátiles en áreas de atención al público o alto tráfico.'],
            ['codigo'=>'5.5.3.c','categoria'=>'Privacidad',          'nombre'=>'Ubicación Estratégica de Pantallas',                'descripcion'=>'Se posicionan los monitores para evitar la visualización por terceros no autorizados (Anti shoulder surfing).'],
        ],
    ];

    // Controles de acceso disponibles para el inventario (GIL-G-027)
    const CONTROLES_ACCESO_OPCIONES = [
        'Biométrico (huella dactilar)',
        'Tarjeta de proximidad (RFID)',
        'Clave / PIN de acceso',
        'Llave física / cerradura',
        'Guardia de seguridad',
        'Registro manual (libro de visitantes)',
        'Sistema de video portero',
    ];

    // Controles de monitoreo disponibles
    const CONTROLES_MONITOREO_OPCIONES = [
        'Cámaras CCTV internas',
        'Cámaras CCTV externas',
        'Grabación continua 24/7',
        'Sistema de alarma perimetral',
        'Sensor de movimiento',
        'Sensor de humo / calor',
        'Registro digital de accesos',
        'Registro físico (bitácora)',
    ];

    protected $casts = [
        'controles_acceso'      => 'array',
        'controles_monitoreo'   => 'array',
        'historico_cambios'     => 'array',
        'fecha_inventario'      => 'date',
        'activa'                => 'boolean',
    ];

    public function sede(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Sede::class);
    }

    public function verificaciones(): HasMany
    {
        return $this->hasMany(AreaSeguraVerificacion::class, 'area_segura_id')
                    ->orderBy('fecha_verificacion', 'desc');
    }

    public function ultimaVerificacion()
    {
        return $this->hasOne(AreaSeguraVerificacion::class, 'area_segura_id')
                    ->latestOfMany('fecha_verificacion');
    }

    public function creador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function editor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function agregarHistorico(string $cambio, string $usuario): void
    {
        $hist   = $this->historico_cambios ?? [];
        $hist[] = [
            'fecha'   => now()->format('d/m/Y H:i'),
            'cambio'  => $cambio,
            'usuario' => $usuario,
        ];
        $this->historico_cambios = $hist;
        $this->save();
    }
}
