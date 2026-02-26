-- 1. Creamos la base de datos (si no existe) y la usamos
CREATE DATABASE IF NOT EXISTS sistema_tickets DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sistema_tickets;

-- 2. Tabla de Usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id_usuario` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('usuario','administrador') NOT NULL DEFAULT 'usuario',
  `imagen_perfil` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Tabla de Categorías (Catálogo de servicios)
CREATE TABLE IF NOT EXISTS `categorias` (
  `id_categoria` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `sla_horas` int(11) NOT NULL DEFAULT 24,
  PRIMARY KEY (`id_categoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Tabla de Tickets (¡Ahora con Criticidad!)
CREATE TABLE IF NOT EXISTS `tickets` (
  `id_ticket` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_usuario` int(10) unsigned NOT NULL,
  `id_categoria` int(10) unsigned NOT NULL,
  `titulo` varchar(150) NOT NULL, 
  `descripcion` text NOT NULL,    
  `criticidad` enum('P1','P2','P3') NOT NULL DEFAULT 'P3', -- Nuestro nuevo campo
  `estado` enum('Pendiente','En Progreso','Resuelto','Cancelado') NOT NULL DEFAULT 'Pendiente',
  `fecha_creacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_resolucion` datetime DEFAULT NULL,
  PRIMARY KEY (`id_ticket`),
  FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id_categoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ==========================================
-- DATOS INICIALES 
-- ==========================================

INSERT INTO `usuarios` (`nombre`, `apellidos`, `email`, `password`, `rol`) 
VALUES ('KikoAdmin', 'Sanchez', 'admin@casa.com', '$2y$10$TBmy5kr.1yeFzoUf6Jo04efooY3AS.zZD52FqL9VOH4hSvqU7o8wW', 'administrador');

INSERT INTO `categorias` (`nombre`, `sla_horas`) VALUES 
('Internet / El WiFi va mal', 2), 
('Dispositivos (Impresora, PC, TV)', 48), 
('Instalación de Apps / Cuentas', 72), 
('Otros (Especificar en la descripción)', 120);

ALTER TABLE `tickets` 
ADD COLUMN `notas_resolucion` TEXT NULL AFTER `fecha_resolucion`;