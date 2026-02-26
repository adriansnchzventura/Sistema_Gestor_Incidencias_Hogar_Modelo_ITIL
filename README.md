# 🛠️ Sistema de Gestión de Incidencias IT - Hogar (Basado en ITIL)

Este proyecto es una plataforma de **Service Desk** simplificada, diseñada para gestionar el soporte técnico dentro de un entorno familiar o pequeña oficina, aplicando los principios de la biblioteca **ITIL (Information Technology Infrastructure Library)**.



## 🚀 Características Principales

* **Gestión de Incidencias:** Registro de tickets con categorización y niveles de prioridad (P1, P2, P3).
* **Gestión de Niveles de Servicio (SLA):** Control visual de tiempos de respuesta según criticidad:
    * **P1 (Crítico):** 4 Horas.
    * **P2 (Alta):** 24 Horas.
    * **P3 (Media/Baja):** 72 Horas.
* **Panel de Administración:** Control total de estados de tickets y notas de resolución.
* **Gestión de Usuarios:** Registro, roles (Admin/Usuario) y personalización de perfiles con fotografía.
* **Base de Conocimientos:** Almacenamiento de soluciones en los tickets resueltos para consultas futuras.

## 🛠️ Tecnologías Utilizadas

* **Backend:** PHP 8.x
* **Base de Datos:** MySQL / MariaDB
* **Frontend:** Bootstrap 5, Bootstrap Icons, Google Fonts
* **Entorno Recomendado:** AppServ, XAMPP o Laragon

## 📋 Requisitos Previos

1. Tener un servidor local activo (Apache y MySQL).
2. Crear una base de datos llamada `sistema_tickets`.
3. Importar el archivo `database.sql` incluido en este repositorio.

## 🔧 Instalación y Configuración

1. **Clonar el repositorio** en tu carpeta de servidor (ej. `www/` o `htdocs/`):
   ```bash
   git clone [https://github.com/tu-usuario/ITIL_CASA.git](https://github.com/tu-usuario/ITIL_CASA.git)
