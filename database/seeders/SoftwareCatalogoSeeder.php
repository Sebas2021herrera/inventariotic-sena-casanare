<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SoftwareCatalogoSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('software_catalogo')->count() > 0) {
            $this->command->info('El catálogo ya tiene datos. Use --force para re-sembrar.');
            return;
        }

        $now = now();

        $items = [
            // ================================================================
            // LICENCIADO — Listado de Productos Autorizados (Dirección General)
            // ================================================================

            // Adobe Creative Cloud 2025 (ETLA)
            ['nombre' => 'Adobe Creative Cloud 2025 (ETLA)', 'subproducto' => 'Photoshop',           'tipo' => 'licenciado', 'vigencia_hasta' => '2025-12-31'],
            ['nombre' => 'Adobe Creative Cloud 2025 (ETLA)', 'subproducto' => 'Illustrator',          'tipo' => 'licenciado', 'vigencia_hasta' => '2025-12-31'],
            ['nombre' => 'Adobe Creative Cloud 2025 (ETLA)', 'subproducto' => 'Premiere Pro',         'tipo' => 'licenciado', 'vigencia_hasta' => '2025-12-31'],
            ['nombre' => 'Adobe Creative Cloud 2025 (ETLA)', 'subproducto' => 'After Effects',        'tipo' => 'licenciado', 'vigencia_hasta' => '2025-12-31'],
            ['nombre' => 'Adobe Creative Cloud 2025 (ETLA)', 'subproducto' => 'InDesign',             'tipo' => 'licenciado', 'vigencia_hasta' => '2025-12-31'],
            ['nombre' => 'Adobe Creative Cloud 2025 (ETLA)', 'subproducto' => 'Acrobat Pro DC',       'tipo' => 'licenciado', 'vigencia_hasta' => '2025-12-31'],
            ['nombre' => 'Adobe Creative Cloud 2025 (ETLA)', 'subproducto' => 'Lightroom Classic',    'tipo' => 'licenciado', 'vigencia_hasta' => '2025-12-31'],
            ['nombre' => 'Adobe Creative Cloud 2025 (ETLA)', 'subproducto' => 'Dreamweaver',          'tipo' => 'licenciado', 'vigencia_hasta' => '2025-12-31'],
            ['nombre' => 'Adobe Creative Cloud 2025 (ETLA)', 'subproducto' => 'Audition',             'tipo' => 'licenciado', 'vigencia_hasta' => '2025-12-31'],
            ['nombre' => 'Adobe Creative Cloud 2025 (ETLA)', 'subproducto' => 'Animate',              'tipo' => 'licenciado', 'vigencia_hasta' => '2025-12-31'],
            ['nombre' => 'Adobe Creative Cloud 2025 (ETLA)', 'subproducto' => 'Adobe XD',             'tipo' => 'licenciado', 'vigencia_hasta' => '2025-12-31'],
            ['nombre' => 'Adobe Creative Cloud 2025 (ETLA)', 'subproducto' => 'Substance 3D Painter', 'tipo' => 'licenciado', 'vigencia_hasta' => '2025-12-31'],
            ['nombre' => 'Adobe Creative Cloud 2025 (ETLA)', 'subproducto' => 'Adobe Firefly',        'tipo' => 'licenciado', 'vigencia_hasta' => '2025-12-31'],

            // Altium
            ['nombre' => 'Altium Designer 24', 'subproducto' => null, 'tipo' => 'licenciado', 'vigencia_hasta' => '2026-06-30'],

            // ArcGIS (Esri)
            ['nombre' => 'ArcGIS', 'subproducto' => 'ArcGIS Pro',    'tipo' => 'licenciado', 'vigencia_hasta' => '2026-06-30'],
            ['nombre' => 'ArcGIS', 'subproducto' => 'ArcGIS Online', 'tipo' => 'licenciado', 'vigencia_hasta' => '2026-06-30'],
            ['nombre' => 'ArcGIS', 'subproducto' => 'ArcMap',        'tipo' => 'licenciado', 'vigencia_hasta' => '2026-06-30'],

            // Autodesk
            ['nombre' => 'Autodesk AutoCAD 2025',                    'subproducto' => null,                    'tipo' => 'licenciado', 'vigencia_hasta' => '2026-06-30'],
            ['nombre' => 'Autodesk AutoCAD Civil 3D 2025',           'subproducto' => null,                    'tipo' => 'licenciado', 'vigencia_hasta' => '2026-06-30'],
            ['nombre' => 'Autodesk Revit 2025',                      'subproducto' => null,                    'tipo' => 'licenciado', 'vigencia_hasta' => '2026-06-30'],
            ['nombre' => 'Autodesk 3ds Max 2025',                    'subproducto' => null,                    'tipo' => 'licenciado', 'vigencia_hasta' => '2026-06-30'],
            ['nombre' => 'Autodesk Inventor Professional 2025',      'subproducto' => null,                    'tipo' => 'licenciado', 'vigencia_hasta' => '2026-06-30'],
            ['nombre' => 'Autodesk Maya 2025',                       'subproducto' => null,                    'tipo' => 'licenciado', 'vigencia_hasta' => '2026-06-30'],
            ['nombre' => 'Autodesk Fusion 360',                      'subproducto' => null,                    'tipo' => 'licenciado', 'vigencia_hasta' => '2026-06-30'],
            ['nombre' => 'Autodesk Infrastructure Design Suite',     'subproducto' => null,                    'tipo' => 'licenciado', 'vigencia_hasta' => '2026-06-30'],

            // Audaces
            ['nombre' => 'Audaces', 'subproducto' => 'Audaces Idea',    'tipo' => 'licenciado', 'vigencia_hasta' => '2026-06-30'],
            ['nombre' => 'Audaces', 'subproducto' => 'Audaces Moldes',  'tipo' => 'licenciado', 'vigencia_hasta' => '2026-06-30'],
            ['nombre' => 'Audaces', 'subproducto' => 'Audaces Encaixe', 'tipo' => 'licenciado', 'vigencia_hasta' => '2026-06-30'],

            // Corel
            ['nombre' => 'CorelDRAW Technical Suite 2025', 'subproducto' => 'CorelDRAW',                'tipo' => 'licenciado', 'vigencia_hasta' => '2026-06-30'],
            ['nombre' => 'CorelDRAW Technical Suite 2025', 'subproducto' => 'Corel PHOTO-PAINT',        'tipo' => 'licenciado', 'vigencia_hasta' => '2026-06-30'],
            ['nombre' => 'CorelDRAW Technical Suite 2025', 'subproducto' => 'Corel Designer',           'tipo' => 'licenciado', 'vigencia_hasta' => '2026-06-30'],

            // Accesibilidad
            ['nombre' => 'JAWS for Windows (Job Access With Speech)', 'subproducto' => null, 'tipo' => 'licenciado', 'vigencia_hasta' => '2026-06-30'],
            ['nombre' => 'ZoomText', 'subproducto' => null, 'tipo' => 'licenciado', 'vigencia_hasta' => '2026-06-30'],

            // Microsoft 365 A5 (Suite completa SENA)
            ['nombre' => 'Microsoft 365 A5', 'subproducto' => 'Microsoft Word',        'tipo' => 'licenciado', 'vigencia_hasta' => '2026-12-31'],
            ['nombre' => 'Microsoft 365 A5', 'subproducto' => 'Microsoft Excel',       'tipo' => 'licenciado', 'vigencia_hasta' => '2026-12-31'],
            ['nombre' => 'Microsoft 365 A5', 'subproducto' => 'Microsoft PowerPoint',  'tipo' => 'licenciado', 'vigencia_hasta' => '2026-12-31'],
            ['nombre' => 'Microsoft 365 A5', 'subproducto' => 'Microsoft Outlook',     'tipo' => 'licenciado', 'vigencia_hasta' => '2026-12-31'],
            ['nombre' => 'Microsoft 365 A5', 'subproducto' => 'Microsoft Teams',       'tipo' => 'licenciado', 'vigencia_hasta' => '2026-12-31'],
            ['nombre' => 'Microsoft 365 A5', 'subproducto' => 'OneDrive for Business', 'tipo' => 'licenciado', 'vigencia_hasta' => '2026-12-31'],
            ['nombre' => 'Microsoft 365 A5', 'subproducto' => 'SharePoint Online',     'tipo' => 'licenciado', 'vigencia_hasta' => '2026-12-31'],
            ['nombre' => 'Microsoft 365 A5', 'subproducto' => 'Microsoft OneNote',     'tipo' => 'licenciado', 'vigencia_hasta' => '2026-12-31'],
            ['nombre' => 'Microsoft 365 A5', 'subproducto' => 'Exchange Online',       'tipo' => 'licenciado', 'vigencia_hasta' => '2026-12-31'],
            ['nombre' => 'Microsoft 365 A5', 'subproducto' => 'Azure Active Directory', 'tipo' => 'licenciado', 'vigencia_hasta' => '2026-12-31'],
            ['nombre' => 'Microsoft 365 A5', 'subproducto' => 'Microsoft Forms',       'tipo' => 'licenciado', 'vigencia_hasta' => '2026-12-31'],
            ['nombre' => 'Microsoft 365 A5', 'subproducto' => 'Microsoft Whiteboard',  'tipo' => 'licenciado', 'vigencia_hasta' => '2026-12-31'],
            ['nombre' => 'Microsoft 365 A5', 'subproducto' => 'Microsoft Planner',     'tipo' => 'licenciado', 'vigencia_hasta' => '2026-12-31'],

            // Microsoft Intune y OS
            ['nombre' => 'Microsoft Intune',             'subproducto' => null, 'tipo' => 'licenciado', 'vigencia_hasta' => '2026-12-31'],
            ['nombre' => 'Microsoft Windows 10 Education', 'subproducto' => null, 'tipo' => 'licenciado', 'vigencia_hasta' => null],
            ['nombre' => 'Microsoft Windows 11 Education', 'subproducto' => null, 'tipo' => 'licenciado', 'vigencia_hasta' => null],
            ['nombre' => 'Microsoft Office 2021 LTSC Professional Plus', 'subproducto' => null, 'tipo' => 'licenciado', 'vigencia_hasta' => null],

            // Ingeniería
            ['nombre' => 'Proteus Professional 8.x',   'subproducto' => null, 'tipo' => 'licenciado', 'vigencia_hasta' => '2026-06-30'],
            ['nombre' => 'Optitex',                     'subproducto' => null, 'tipo' => 'licenciado', 'vigencia_hasta' => '2026-06-30'],
            ['nombre' => 'SolidWorks 2025', 'subproducto' => 'SolidWorks Standard',    'tipo' => 'licenciado', 'vigencia_hasta' => '2026-06-30'],
            ['nombre' => 'SolidWorks 2025', 'subproducto' => 'SolidWorks Premium',     'tipo' => 'licenciado', 'vigencia_hasta' => '2026-06-30'],
            ['nombre' => 'SolidWorks 2025', 'subproducto' => 'SolidWorks Simulation',  'tipo' => 'licenciado', 'vigencia_hasta' => '2026-06-30'],
            ['nombre' => 'SolidWorks 2025', 'subproducto' => 'SolidWorks PDM Professional', 'tipo' => 'licenciado', 'vigencia_hasta' => '2026-06-30'],
            ['nombre' => 'Mastercam 2025',              'subproducto' => null, 'tipo' => 'licenciado', 'vigencia_hasta' => '2026-06-30'],
            ['nombre' => 'Geomagic Design X',           'subproducto' => null, 'tipo' => 'licenciado', 'vigencia_hasta' => '2026-06-30'],

            // MATLAB
            ['nombre' => 'MATLAB R2025a', 'subproducto' => 'MATLAB Base',                    'tipo' => 'licenciado', 'vigencia_hasta' => '2026-06-30'],
            ['nombre' => 'MATLAB R2025a', 'subproducto' => 'Simulink',                       'tipo' => 'licenciado', 'vigencia_hasta' => '2026-06-30'],
            ['nombre' => 'MATLAB R2025a', 'subproducto' => 'Signal Processing Toolbox',      'tipo' => 'licenciado', 'vigencia_hasta' => '2026-06-30'],
            ['nombre' => 'MATLAB R2025a', 'subproducto' => 'Image Processing Toolbox',       'tipo' => 'licenciado', 'vigencia_hasta' => '2026-06-30'],
            ['nombre' => 'MATLAB R2025a', 'subproducto' => 'Control System Toolbox',         'tipo' => 'licenciado', 'vigencia_hasta' => '2026-06-30'],
            ['nombre' => 'MATLAB R2025a', 'subproducto' => 'Deep Learning Toolbox',          'tipo' => 'licenciado', 'vigencia_hasta' => '2026-06-30'],

            // Contabilidad / Gestión
            ['nombre' => 'Helisa Contabilidad',              'subproducto' => null, 'tipo' => 'licenciado', 'vigencia_hasta' => '2026-06-30'],
            ['nombre' => 'SIIGO NUBE (Contabilidad)',        'subproducto' => null, 'tipo' => 'licenciado', 'vigencia_hasta' => '2026-06-30'],
            ['nombre' => 'Flexsim Simulation Software',      'subproducto' => null, 'tipo' => 'licenciado', 'vigencia_hasta' => '2026-06-30'],

            // Centro de Datos — Productos Autorizados
            ['nombre' => 'Oracle Database',              'subproducto' => 'Oracle ULA',                'tipo' => 'licenciado', 'vigencia_hasta' => null],
            ['nombre' => 'Red Hat Enterprise Linux 9',   'subproducto' => null,                        'tipo' => 'licenciado', 'vigencia_hasta' => '2026-12-31'],
            ['nombre' => 'Microsoft Windows Server 2022', 'subproducto' => 'Standard Edition',        'tipo' => 'licenciado', 'vigencia_hasta' => null],
            ['nombre' => 'Microsoft Windows Server 2022', 'subproducto' => 'Datacenter Edition',      'tipo' => 'licenciado', 'vigencia_hasta' => null],
            ['nombre' => 'Microsoft SQL Server 2022',    'subproducto' => 'Standard Edition',         'tipo' => 'licenciado', 'vigencia_hasta' => null],
            ['nombre' => 'Microsoft SQL Server 2022',    'subproducto' => 'Enterprise Edition',       'tipo' => 'licenciado', 'vigencia_hasta' => null],
            ['nombre' => 'Microsoft Exchange Server 2019', 'subproducto' => null,                     'tipo' => 'licenciado', 'vigencia_hasta' => null],
            ['nombre' => 'Microsoft System Center 2022', 'subproducto' => null,                       'tipo' => 'licenciado', 'vigencia_hasta' => null],

            // ================================================================
            // LIBRE — Software_Libre.csv (120+ herramientas open source)
            // ================================================================

            // Lenguajes y runtimes
            ['nombre' => 'Python 3.x',                     'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'Java Development Kit (JDK) 21 LTS', 'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'Node.js',                        'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'PHP 8.x',                        'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'R (Statistical Computing)',       'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'Go (Golang)',                    'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'Rust',                           'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'Ruby',                           'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],

            // IDEs y editores
            ['nombre' => 'Visual Studio Code',             'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'Eclipse IDE for Java Developers', 'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'NetBeans IDE',                   'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'IntelliJ IDEA Community Edition', 'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'Android Studio',                 'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'Code::Blocks IDE',               'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'Notepad++',                      'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'Spyder IDE',                     'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'RStudio Desktop',                'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],

            // Frameworks y herramientas de desarrollo
            ['nombre' => 'Laravel Framework',              'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'Composer (PHP)',                 'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'Vue CLI',                        'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'React (Create React App)',        'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'Angular CLI',                    'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'Django',                         'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'FastAPI',                        'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'Spring Boot',                    'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'Git',                            'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'npm',                            'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'Yarn',                           'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],

            // Bases de datos
            ['nombre' => 'MySQL Community Server',         'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'PostgreSQL',                     'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'MongoDB Community',              'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'SQLite',                         'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'Redis',                          'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'pgAdmin 4',                      'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'HeidiSQL',                       'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'DBeaver Community Edition',      'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'DB Browser for SQLite',          'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],

            // Servidores y DevOps
            ['nombre' => 'Apache HTTP Server',             'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'XAMPP',                          'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'Docker Desktop',                 'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'Oracle VM VirtualBox',           'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'Vagrant',                        'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'Elasticsearch / Kibana / Logstash (ELK)', 'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'WordPress',                      'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],

            // Sistemas Operativos
            ['nombre' => 'Ubuntu Linux',                   'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'Debian Linux',                   'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'CentOS / Rocky Linux',           'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'Kali Linux',                     'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],

            // Ciencia de datos / IA
            ['nombre' => 'Anaconda Distribution (Python)', 'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'Jupyter Notebook / JupyterLab',  'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'TensorFlow / Keras',             'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'Scikit-learn',                   'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'Pandas / NumPy / Matplotlib',    'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'OpenCV',                         'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],

            // SIG / Geomática
            ['nombre' => 'QGIS 3.x (Sistema de Información Geográfica)', 'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],

            // Diseño y multimedia
            ['nombre' => 'Blender (Modelado 3D)',           'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'GIMP (GNU Image Manipulation Program)', 'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'Inkscape (Gráficos Vectoriales)', 'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'Audacity (Audio)',                'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'OBS Studio (Grabación/Streaming)', 'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'VLC Media Player',               'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],

            // Ofimática libre
            ['nombre' => 'LibreOffice', 'subproducto' => 'LibreOffice Writer',   'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'LibreOffice', 'subproducto' => 'LibreOffice Calc',     'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'LibreOffice', 'subproducto' => 'LibreOffice Impress',  'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'LibreOffice', 'subproducto' => 'LibreOffice Draw',     'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'LibreOffice', 'subproducto' => 'LibreOffice Base',     'tipo' => 'libre', 'vigencia_hasta' => null],

            // Educativo
            ['nombre' => 'Scratch (MIT)',                   'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'Arduino IDE',                     'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],

            // Herramientas de red / seguridad
            ['nombre' => 'Wireshark',                       'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'Nmap (Network Mapper)',            'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'Metasploit Framework (Community)', 'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'OpenSSL',                         'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'OpenVPN Community',               'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'ClamAV (Antivirus)',              'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'Zabbix (Monitoreo)',              'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'Nagios Core',                     'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],

            // Utilidades y SSH
            ['nombre' => 'PuTTY SSH Client',               'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'WinSCP',                         'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'MobaXterm Free Edition',         'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'FileZilla Client',               'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => '7-Zip',                          'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'KeePass (Gestor de Contraseñas)', 'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'Rufus (USB Bootable)',            'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'balenaEtcher',                   'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'draw.io Desktop',                'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'Postman (Community Edition)',     'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'Malwarebytes Free Edition',      'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'CMake',                          'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'MinGW-w64 (GCC para Windows)',   'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],

            // Navegadores
            ['nombre' => 'Google Chrome',                  'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],
            ['nombre' => 'Mozilla Firefox',                'subproducto' => null, 'tipo' => 'libre', 'vigencia_hasta' => null],

            // ================================================================
            // NO AUTORIZADO — Software_NO_AUTORIZADO.csv
            // ================================================================

            // Software de acceso remoto no institucional
            ['nombre' => 'TeamViewer (Uso Personal — Sin Autorización Institucional)', 'subproducto' => null, 'tipo' => 'no_autorizado', 'vigencia_hasta' => null],
            ['nombre' => 'AnyDesk (Sin Autorización Institucional)',                   'subproducto' => null, 'tipo' => 'no_autorizado', 'vigencia_hasta' => null],

            // Software comercial sin licencia
            ['nombre' => 'Cinema 4D (Sin Licencia)',              'subproducto' => null, 'tipo' => 'no_autorizado', 'vigencia_hasta' => null],
            ['nombre' => 'WinZip (Sin Licencia)',                 'subproducto' => null, 'tipo' => 'no_autorizado', 'vigencia_hasta' => null],
            ['nombre' => 'Nero Burning ROM (Sin Licencia)',       'subproducto' => null, 'tipo' => 'no_autorizado', 'vigencia_hasta' => null],
            ['nombre' => 'CyberLink PowerDVD (Sin Licencia)',     'subproducto' => null, 'tipo' => 'no_autorizado', 'vigencia_hasta' => null],
            ['nombre' => 'Alcohol 120% (Sin Licencia)',           'subproducto' => null, 'tipo' => 'no_autorizado', 'vigencia_hasta' => null],
            ['nombre' => 'VirtualDJ (Sin Licencia)',              'subproducto' => null, 'tipo' => 'no_autorizado', 'vigencia_hasta' => null],

            // Optimizadores / PUP no autorizados
            ['nombre' => 'CCleaner Professional (Sin Licencia)',  'subproducto' => null, 'tipo' => 'no_autorizado', 'vigencia_hasta' => null],
            ['nombre' => 'Advanced SystemCare PRO (Sin Licencia)', 'subproducto' => null, 'tipo' => 'no_autorizado', 'vigencia_hasta' => null],
            ['nombre' => 'Driver Booster PRO (Sin Licencia)',     'subproducto' => null, 'tipo' => 'no_autorizado', 'vigencia_hasta' => null],
            ['nombre' => 'IObit Uninstaller PRO (Sin Licencia)', 'subproducto' => null, 'tipo' => 'no_autorizado', 'vigencia_hasta' => null],

            // Antivirus sin licencia
            ['nombre' => 'Kaspersky (Sin Licencia)',              'subproducto' => null, 'tipo' => 'no_autorizado', 'vigencia_hasta' => null],
            ['nombre' => 'Norton (Sin Licencia)',                 'subproducto' => null, 'tipo' => 'no_autorizado', 'vigencia_hasta' => null],
            ['nombre' => 'Avast Premium (Sin Licencia)',          'subproducto' => null, 'tipo' => 'no_autorizado', 'vigencia_hasta' => null],

            // Juegos y entretenimiento
            ['nombre' => 'Steam (Videojuegos)',                   'subproducto' => null, 'tipo' => 'no_autorizado', 'vigencia_hasta' => null],
            ['nombre' => 'Grand Theft Auto (GTA)',                'subproducto' => null, 'tipo' => 'no_autorizado', 'vigencia_hasta' => null],
            ['nombre' => 'Fortnite',                             'subproducto' => null, 'tipo' => 'no_autorizado', 'vigencia_hasta' => null],
            ['nombre' => 'Minecraft (Sin Licencia Educativa)',    'subproducto' => null, 'tipo' => 'no_autorizado', 'vigencia_hasta' => null],
            ['nombre' => 'League of Legends',                    'subproducto' => null, 'tipo' => 'no_autorizado', 'vigencia_hasta' => null],
            ['nombre' => 'Counter-Strike (CS2)',                  'subproducto' => null, 'tipo' => 'no_autorizado', 'vigencia_hasta' => null],
            ['nombre' => 'Call of Duty',                         'subproducto' => null, 'tipo' => 'no_autorizado', 'vigencia_hasta' => null],
            ['nombre' => 'Roblox',                               'subproducto' => null, 'tipo' => 'no_autorizado', 'vigencia_hasta' => null],

            // Redes P2P / descarga
            ['nombre' => 'uTorrent',                             'subproducto' => null, 'tipo' => 'no_autorizado', 'vigencia_hasta' => null],
            ['nombre' => 'BitTorrent',                           'subproducto' => null, 'tipo' => 'no_autorizado', 'vigencia_hasta' => null],
            ['nombre' => 'eMule',                                'subproducto' => null, 'tipo' => 'no_autorizado', 'vigencia_hasta' => null],

            // Riesgo de seguridad
            ['nombre' => 'Xender / SHAREit (Riesgo de Seguridad)', 'subproducto' => null, 'tipo' => 'no_autorizado', 'vigencia_hasta' => null],
        ];

        $now = now();
        $rows = array_map(fn($item) => array_merge($item, [
            'activo'     => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]), $items);

        foreach (array_chunk($rows, 50) as $chunk) {
            DB::table('software_catalogo')->insert($chunk);
        }

        $this->command->info('Catálogo de software sembrado: ' . count($rows) . ' entradas.');
    }
}
