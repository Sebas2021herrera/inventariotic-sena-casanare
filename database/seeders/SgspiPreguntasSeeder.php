<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SgspiPregunta;

class SgspiPreguntasSeeder extends Seeder
{
    public function run(): void
    {
        SgspiPregunta::truncate();

        $preguntas = [

            // ─── CONTRASEÑAS ──────────────────────────────────────────────────
            ['tema' => 'Contraseñas',
             'pregunta' => '¿Cuál de las siguientes contraseñas es la más segura?',
             'opciones' => ['A' => '12345678', 'B' => 'Password', 'C' => 'Colombia2026', 'D' => 'M@r!0#82LpQ'],
             'respuesta' => 'D',
             'explicacion' => 'Combina letras mayúsculas, minúsculas, números y caracteres especiales.'],

            ['tema' => 'Contraseñas',
             'pregunta' => '¿Es correcto compartir tu contraseña con un compañero porque necesita ingresar rápidamente al sistema?',
             'opciones' => ['A' => 'Sí', 'B' => 'No'],
             'respuesta' => 'B',
             'explicacion' => 'Las contraseñas son personales e intransferibles.'],

            ['tema' => 'Contraseñas',
             'pregunta' => '¿Qué debes hacer si sospechas que alguien conoce tu contraseña?',
             'opciones' => ['A' => 'Continuar utilizándola.', 'B' => 'Cambiarla inmediatamente e informar si es necesario.', 'C' => 'Compartirla con otro compañero.', 'D' => 'Escribirla en un papel.'],
             'respuesta' => 'B',
             'explicacion' => 'Cambiar la contraseña de inmediato limita el daño ante una posible filtración.'],

            ['tema' => 'Contraseñas',
             'pregunta' => '¿Cuál de estas prácticas NO es recomendable?',
             'opciones' => ['A' => 'Utilizar un gestor de contraseñas.', 'B' => 'Crear contraseñas largas.', 'C' => 'Reutilizar la misma contraseña en todos los sistemas.', 'D' => 'Activar la autenticación multifactor.'],
             'respuesta' => 'C',
             'explicacion' => 'Reutilizar contraseñas expone todas tus cuentas si una es comprometida.'],

            ['tema' => 'Contraseñas',
             'pregunta' => '¿Qué significa autenticación de múltiples factores (MFA)?',
             'opciones' => ['A' => 'Tener varias contraseñas.', 'B' => 'Utilizar más de un método para verificar la identidad.', 'C' => 'Compartir la contraseña con el jefe.', 'D' => 'Cambiar la contraseña cada hora.'],
             'respuesta' => 'B',
             'explicacion' => 'MFA añade una capa adicional de seguridad más allá de la contraseña.'],

            ['tema' => 'Contraseñas',
             'pregunta' => '¿Dónde NO debes guardar tu contraseña?',
             'opciones' => ['A' => 'En un gestor de contraseñas autorizado.', 'B' => 'En un papel pegado al monitor.', 'C' => 'En una aplicación segura autorizada.', 'D' => 'En un administrador de credenciales.'],
             'respuesta' => 'B',
             'explicacion' => 'Un papel visible es un riesgo físico de seguridad.'],

            // ─── PHISHING ─────────────────────────────────────────────────────
            ['tema' => 'Phishing',
             'pregunta' => '¿Qué es el phishing?',
             'opciones' => ['A' => 'Un antivirus.', 'B' => 'Un ataque para engañar a las personas y robar información.', 'C' => 'Un navegador.', 'D' => 'Un firewall.'],
             'respuesta' => 'B',
             'explicacion' => 'El phishing simula ser una fuente confiable para obtener datos sensibles.'],

            ['tema' => 'Phishing',
             'pregunta' => '¿Cuál es una señal de un correo de phishing?',
             'opciones' => ['A' => 'Solicita información urgente.', 'B' => 'Tiene errores ortográficos.', 'C' => 'El remitente parece extraño.', 'D' => 'Todas las anteriores.'],
             'respuesta' => 'D',
             'explicacion' => 'Todas son señales de alerta ante un posible ataque de phishing.'],

            ['tema' => 'Phishing',
             'pregunta' => 'Recibes un correo diciendo que ganaste un premio y debes ingresar tu contraseña. ¿Qué haces?',
             'opciones' => ['A' => 'Ingresar la información.', 'B' => 'Ignorarlo o reportarlo.', 'C' => 'Reenviarlo.', 'D' => 'Compartirlo.'],
             'respuesta' => 'B',
             'explicacion' => 'Los premios no solicitados por correo son una técnica clásica de phishing.'],

            ['tema' => 'Phishing',
             'pregunta' => '¿Qué debes verificar primero antes de hacer clic en un enlace?',
             'opciones' => ['A' => 'El color del correo.', 'B' => 'El remitente y la dirección del enlace.', 'C' => 'La hora.', 'D' => 'El tamaño del archivo.'],
             'respuesta' => 'B',
             'explicacion' => 'El remitente y la URL real del enlace son los indicadores más importantes.'],

            ['tema' => 'Phishing',
             'pregunta' => 'Si accidentalmente hiciste clic en un enlace sospechoso, ¿qué debes hacer?',
             'opciones' => ['A' => 'No decir nada.', 'B' => 'Reportar inmediatamente al área de TI o Seguridad.', 'C' => 'Apagar el computador y continuar al día siguiente.', 'D' => 'Borrar el correo solamente.'],
             'respuesta' => 'B',
             'explicacion' => 'Reportar de inmediato permite contener el incidente a tiempo.'],

            ['tema' => 'Phishing',
             'pregunta' => '¿Los ataques de phishing solo llegan por correo electrónico?',
             'opciones' => ['A' => 'Sí.', 'B' => 'No, también por SMS, llamadas, redes sociales y aplicaciones de mensajería.'],
             'respuesta' => 'B',
             'explicacion' => 'El phishing puede llegarte por cualquier canal de comunicación digital.'],

            // ─── MALWARE ──────────────────────────────────────────────────────
            ['tema' => 'Malware',
             'pregunta' => '¿Qué es un malware?',
             'opciones' => ['A' => 'Un software malicioso.', 'B' => 'Un antivirus.', 'C' => 'Un navegador.', 'D' => 'Un programa de oficina.'],
             'respuesta' => 'A',
             'explicacion' => 'Malware es cualquier software diseñado para dañar, robar o comprometer sistemas.'],

            ['tema' => 'Malware',
             'pregunta' => '¿Cuál de estos es un tipo de malware?',
             'opciones' => ['A' => 'Excel.', 'B' => 'Word.', 'C' => 'Ransomware.', 'D' => 'PowerPoint.'],
             'respuesta' => 'C',
             'explicacion' => 'El ransomware cifra los archivos y exige un pago para recuperarlos.'],

            ['tema' => 'Malware',
             'pregunta' => '¿Cuál es una forma común de infectar un computador?',
             'opciones' => ['A' => 'Abrir archivos adjuntos desconocidos.', 'B' => 'Actualizar Windows.', 'C' => 'Bloquear la pantalla.', 'D' => 'Cambiar la contraseña.'],
             'respuesta' => 'A',
             'explicacion' => 'Los adjuntos de remitentes desconocidos son uno de los principales vectores de infección.'],

            ['tema' => 'Malware',
             'pregunta' => '¿Qué debes hacer si el antivirus detecta una amenaza?',
             'opciones' => ['A' => 'Ignorarla.', 'B' => 'Seguir las recomendaciones del antivirus y reportar el incidente si aplica.', 'C' => 'Desactivar el antivirus.', 'D' => 'Reiniciar el computador sin más.'],
             'respuesta' => 'B',
             'explicacion' => 'Seguir las recomendaciones del antivirus y reportar permite una respuesta adecuada al incidente.'],

            ['tema' => 'Malware',
             'pregunta' => '¿Por qué es importante mantener actualizado el sistema operativo?',
             'opciones' => ['A' => 'Para cambiar el fondo de pantalla.', 'B' => 'Porque corrige vulnerabilidades de seguridad.', 'C' => 'Para ahorrar energía.', 'D' => 'Para abrir más rápido los documentos.'],
             'respuesta' => 'B',
             'explicacion' => 'Las actualizaciones incluyen parches de seguridad que cierran vulnerabilidades conocidas.'],

            ['tema' => 'Malware',
             'pregunta' => '¿Qué puede hacer un ransomware?',
             'opciones' => ['A' => 'Mejorar el rendimiento del equipo.', 'B' => 'Cifrar los archivos y bloquear el acceso a la información.', 'C' => 'Limpiar el disco duro de forma segura.', 'D' => 'Aumentar la velocidad de Internet.'],
             'respuesta' => 'B',
             'explicacion' => 'El ransomware secuestra los archivos cifrándolos hasta recibir un "rescate".'],

            // ─── COPIAS DE SEGURIDAD ──────────────────────────────────────────
            ['tema' => 'Copias de Seguridad',
             'pregunta' => '¿Qué es una copia de seguridad?',
             'opciones' => ['A' => 'Una copia de la información para poder recuperarla si ocurre un incidente.', 'B' => 'Una fotografía.', 'C' => 'Un antivirus.', 'D' => 'Un navegador.'],
             'respuesta' => 'A',
             'explicacion' => 'La copia de seguridad garantiza la disponibilidad de la información ante cualquier incidente.'],

            ['tema' => 'Copias de Seguridad',
             'pregunta' => '¿Cuál es el principal beneficio de realizar copias de seguridad?',
             'opciones' => ['A' => 'Recuperar información en caso de pérdida o ataque.', 'B' => 'Ahorrar energía.', 'C' => 'Mejorar el internet.', 'D' => 'Aumentar la memoria RAM.'],
             'respuesta' => 'A',
             'explicacion' => 'La recuperación de información es el beneficio clave de las copias de seguridad.'],

            ['tema' => 'Copias de Seguridad',
             'pregunta' => '¿Dónde es recomendable almacenar una copia de seguridad?',
             'opciones' => ['A' => 'En un lugar seguro y autorizado por la entidad.', 'B' => 'En cualquier USB desconocida.', 'C' => 'En un computador sin protección.', 'D' => 'En cualquier equipo compartido.'],
             'respuesta' => 'A',
             'explicacion' => 'El almacenamiento debe cumplir con los requisitos de seguridad establecidos por la organización.'],

            ['tema' => 'Copias de Seguridad',
             'pregunta' => '¿Las copias de seguridad deben realizarse periódicamente?',
             'opciones' => ['A' => 'Sí.', 'B' => 'No.'],
             'respuesta' => 'A',
             'explicacion' => 'La periodicidad asegura que la información más reciente pueda ser recuperada.'],

            ['tema' => 'Copias de Seguridad',
             'pregunta' => 'Si ocurre un ataque de ransomware, ¿qué ayuda a recuperar la información?',
             'opciones' => ['A' => 'Una copia de seguridad actualizada.', 'B' => 'Reiniciar el computador.', 'C' => 'Cambiar el teclado.', 'D' => 'Borrar todos los archivos.'],
             'respuesta' => 'A',
             'explicacion' => 'Una copia actualizada permite restaurar la información sin pagar el rescate.'],

            ['tema' => 'Copias de Seguridad',
             'pregunta' => '¿Quién es responsable de proteger la información que genera?',
             'opciones' => ['A' => 'Solo el área de TI.', 'B' => 'Todos los colaboradores, siguiendo las políticas de la entidad.'],
             'respuesta' => 'B',
             'explicacion' => 'La seguridad de la información es responsabilidad de toda la organización.'],

            // ─── PRIVACIDAD ───────────────────────────────────────────────────
            ['tema' => 'Privacidad',
             'pregunta' => '¿Qué significa privacidad de la información?',
             'opciones' => ['A' => 'Proteger los datos personales y limitar su acceso a quienes están autorizados.', 'B' => 'Compartir toda la información.', 'C' => 'Eliminar todos los archivos.', 'D' => 'Publicar datos en redes sociales.'],
             'respuesta' => 'A',
             'explicacion' => 'La privacidad garantiza que solo las personas autorizadas accedan a los datos personales.'],

            ['tema' => 'Privacidad',
             'pregunta' => '¿Cuál es un dato personal?',
             'opciones' => ['A' => 'El nombre completo de una persona.', 'B' => 'La ubicación de la oficina.', 'C' => 'El modelo de un monitor.', 'D' => 'El número de una silla.'],
             'respuesta' => 'A',
             'explicacion' => 'El nombre completo permite identificar a una persona física y es un dato personal protegido.'],

            ['tema' => 'Privacidad',
             'pregunta' => '¿Es recomendable dejar documentos con datos personales sobre el escritorio?',
             'opciones' => ['A' => 'Sí.', 'B' => 'No.'],
             'respuesta' => 'B',
             'explicacion' => 'Los documentos físicos con datos personales deben guardarse en un lugar seguro.'],

            ['tema' => 'Privacidad',
             'pregunta' => '¿Qué principio protege que solo las personas autorizadas accedan a la información?',
             'opciones' => ['A' => 'Confidencialidad.', 'B' => 'Disponibilidad.', 'C' => 'Productividad.', 'D' => 'Rapidez.'],
             'respuesta' => 'A',
             'explicacion' => 'La confidencialidad es uno de los tres pilares de la seguridad de la información (CIA).'],

            ['tema' => 'Privacidad',
             'pregunta' => '¿Qué debes hacer si encuentras información personal de otra persona en un lugar público de la empresa?',
             'opciones' => ['A' => 'Tomarle una foto.', 'B' => 'Entregarla al responsable o reportarla.', 'C' => 'Compartirla con otros.', 'D' => 'Ignorarla.'],
             'respuesta' => 'B',
             'explicacion' => 'Reportar o entregar la información al responsable protege la privacidad del afectado.'],

            // ─── USB ──────────────────────────────────────────────────────────
            ['tema' => 'USB y Dispositivos',
             'pregunta' => 'Encuentras una memoria USB en el parqueadero. ¿Qué debes hacer?',
             'opciones' => ['A' => 'Conectarla para ver de quién es.', 'B' => 'Entregarla al área de TI o Seguridad.', 'C' => 'Llevarla a casa.', 'D' => 'Prestársela a un compañero.'],
             'respuesta' => 'B',
             'explicacion' => 'Conectar una USB desconocida puede infectar el equipo con malware.'],

            ['tema' => 'USB y Dispositivos',
             'pregunta' => '¿Por qué una memoria USB desconocida representa un riesgo?',
             'opciones' => ['A' => 'Puede contener malware.', 'B' => 'Porque ocupa espacio.', 'C' => 'Porque es lenta.', 'D' => 'Porque cambia el fondo de pantalla.'],
             'respuesta' => 'A',
             'explicacion' => 'Las USB pueden venir pre-cargadas con malware diseñado para ejecutarse automáticamente.'],

            ['tema' => 'USB y Dispositivos',
             'pregunta' => '¿Es recomendable utilizar memorias USB personales para almacenar información confidencial de la empresa?',
             'opciones' => ['A' => 'Sí.', 'B' => 'No, salvo que la política de la organización lo autorice y se apliquen las medidas de seguridad correspondientes.'],
             'respuesta' => 'B',
             'explicacion' => 'Las USB personales no están bajo el control de seguridad de la organización.'],

            ['tema' => 'USB y Dispositivos',
             'pregunta' => 'Antes de abrir archivos de una memoria USB autorizada, ¿qué es recomendable hacer?',
             'opciones' => ['A' => 'Analizarla con el antivirus.', 'B' => 'Formatearla siempre.', 'C' => 'Compartirla.', 'D' => 'Reiniciar el computador.'],
             'respuesta' => 'A',
             'explicacion' => 'Analizar con antivirus detecta amenazas antes de que puedan activarse.'],

            ['tema' => 'USB y Dispositivos',
             'pregunta' => '¿Qué debes hacer si una memoria USB presenta comportamientos extraños al conectarla?',
             'opciones' => ['A' => 'Seguir utilizándola.', 'B' => 'Desconectarla y reportar la situación.', 'C' => 'Prestarla.', 'D' => 'Copiar todos los archivos.'],
             'respuesta' => 'B',
             'explicacion' => 'Comportamientos extraños pueden indicar la presencia de malware activo.'],

            ['tema' => 'USB y Dispositivos',
             'pregunta' => '¿Cuál es una buena práctica para transportar información en una memoria USB autorizada?',
             'opciones' => ['A' => 'Proteger la información mediante cifrado, si la política de la organización lo establece.', 'B' => 'Dejarla sobre cualquier escritorio.', 'C' => 'Compartirla con cualquier persona.', 'D' => 'Rotularla con la contraseña.'],
             'respuesta' => 'A',
             'explicacion' => 'El cifrado protege la información en caso de pérdida o robo del dispositivo.'],

            // ─── CORREO ELECTRÓNICO ───────────────────────────────────────────
            ['tema' => 'Correo Electrónico',
             'pregunta' => 'Recibes un correo de un remitente desconocido con el asunto "Factura pendiente de pago" y un archivo adjunto. ¿Qué debes hacer?',
             'opciones' => ['A' => 'Abrir el archivo inmediatamente.', 'B' => 'Verificar el remitente, analizar si el correo es legítimo y reportarlo si genera sospecha.', 'C' => 'Reenviarlo a todos los compañeros.', 'D' => 'Responder solicitando más información.'],
             'respuesta' => 'B',
             'explicacion' => 'Los archivos adjuntos de remitentes desconocidos pueden contener malware o ser parte de un ataque de phishing.'],

            ['tema' => 'Correo Electrónico',
             'pregunta' => '¿Cuál de las siguientes acciones es una buena práctica al utilizar el correo corporativo?',
             'opciones' => ['A' => 'Compartir tu contraseña con un compañero.', 'B' => 'Abrir todos los archivos adjuntos que recibas.', 'C' => 'Cerrar sesión cuando utilices un equipo compartido.', 'D' => 'Reenviar cadenas de mensajes personales.'],
             'respuesta' => 'C',
             'explicacion' => 'Cerrar la sesión evita que otras personas accedan a tu cuenta y a la información institucional.'],

            ['tema' => 'Correo Electrónico',
             'pregunta' => 'Recibes un correo que parece enviado por el gerente solicitando una transferencia urgente, pero el dominio del remitente es diferente al oficial. ¿Qué debes hacer?',
             'opciones' => ['A' => 'Realizar la transferencia de inmediato.', 'B' => 'Verificar la solicitud por otro medio oficial antes de actuar.', 'C' => 'Ignorar el dominio porque el nombre del remitente es correcto.', 'D' => 'Reenviar el correo a todos los compañeros.'],
             'respuesta' => 'B',
             'explicacion' => 'Los ciberdelincuentes pueden falsificar nombres, pero el dominio del correo suele revelar el fraude.'],

            ['tema' => 'Correo Electrónico',
             'pregunta' => '¿Cuál de las siguientes señales puede indicar que un correo es fraudulento?',
             'opciones' => ['A' => 'Errores de ortografía.', 'B' => 'Enlaces extraños o acortados.', 'C' => 'Solicitudes urgentes de información personal o contraseñas.', 'D' => 'Todas las anteriores.'],
             'respuesta' => 'D',
             'explicacion' => 'Estas son características comunes de correos de phishing.'],

            ['tema' => 'Correo Electrónico',
             'pregunta' => 'Antes de hacer clic en un enlace recibido por correo electrónico, ¿qué debes hacer?',
             'opciones' => ['A' => 'Hacer clic rápidamente para evitar que expire.', 'B' => 'Pasar el cursor sobre el enlace para verificar la dirección web y confirmar que sea legítima.', 'C' => 'Descargar primero todos los archivos adjuntos.', 'D' => 'Compartir el enlace con otros compañeros.'],
             'respuesta' => 'B',
             'explicacion' => 'Al pasar el cursor sobre el enlace puedes verificar si dirige al sitio oficial o a una página fraudulenta.'],

            ['tema' => 'Correo Electrónico',
             'pregunta' => 'Recibes por error un correo con información confidencial que no estaba dirigido a ti. ¿Qué debes hacer?',
             'opciones' => ['A' => 'Leer toda la información por curiosidad.', 'B' => 'Reenviarlo a otros compañeros.', 'C' => 'Informar al remitente del error, eliminar el correo si la política lo indica y no divulgar su contenido.', 'D' => 'Guardarlo por si lo necesitas en el futuro.'],
             'respuesta' => 'C',
             'explicacion' => 'La información confidencial solo debe ser conocida por las personas autorizadas.'],
        ];

        foreach ($preguntas as $p) {
            SgspiPregunta::create($p);
        }
    }
}
