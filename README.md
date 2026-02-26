Sistema de Gestión de Incidencias IT - Entorno Doméstico
Este proyecto implementa una plataforma de Service Desk basada en los fundamentos de ITIL (Information Technology Infrastructure Library). Su objetivo es centralizar y estandarizar el soporte técnico en entornos de pequeña oficina o domésticos, garantizando el cumplimiento de tiempos de respuesta y la trazabilidad de las soluciones.

Arquitectura y Funcionalidades
Gestión de Incidencias y Niveles de Servicio (SLA)
El sistema clasifica las peticiones mediante una matriz de prioridad que determina el tiempo de resolución garantizado:

Prioridad 1 (Crítica): Resolución en un máximo de 4 horas.

Prioridad 2 (Alta): Resolución en un máximo de 24 horas.

Prioridad 3 (Media/Baja): Resolución en un máximo de 72 horas.

Módulos Principales
Panel de Administración: Interfaz para la gestión del ciclo de vida del ticket (Apertura, Asignación, Progreso y Cierre).

Control de Usuarios: Sistema de autenticación con segregación de funciones basada en roles (Administrador y Usuario Final).

Gestión de Activos y Perfiles: Personalización de cuentas de usuario incluyendo almacenamiento de imágenes de perfil.

Knowledge Base (KEDB): Repositorio de soluciones basado en tickets históricos resueltos para la reducción de tiempos de respuesta en incidencias recurrentes.

Especificaciones Técnicas
Motor de Backend: PHP 8.x

Gestor de Base de Datos: MySQL / MariaDB (Motor InnoDB para integridad referencial)

Framework de Frontend: Bootstrap 5 (Responsive Design)

Componentes Adicionales: Bootstrap Icons, Google Fonts API

Despliegue del Sistema
Requisitos del Entorno
Servidor web Apache 2.4+

Intérprete de PHP 8.0 o superior

Instancia de MySQL/MariaDB activa

Procedimiento de Instalación
Obtención del código fuente:
Clonar el repositorio en el directorio raíz del servidor web (ej: www/ o htdocs/):

Bash
git clone https://github.com/adriansnchzventura/Sistema_Gestor_Incidencias_Hogar_Modelo_ITIL.git
Configuración de la Base de Datos:

Crear una base de datos denominada sistema_tickets.

Importar el esquema definido en el archivo database.sql.

Parámetros de Conexión:
Renombrar el archivo database_config.php.example a database_config.php y editar las constantes de conexión con las credenciales de su entorno local:

PHP
define('DB_SERVER', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'su_contraseña');
define('DB_NAME', 'sistema_tickets');
Permisos de Directorio:
Asegurar permisos de escritura en la carpeta img/perfiles/ para permitir la carga de imágenes de usuario.

Seguridad y Privacidad
Este proyecto utiliza password_hash() para el almacenamiento seguro de credenciales. Se recomienda encarecidamente no incluir el archivo database_config.php en sistemas de control de versiones públicos para evitar la exposición de credenciales de acceso a la base de datos.

Licencia
Este software se distribuye bajo la licencia MIT.
