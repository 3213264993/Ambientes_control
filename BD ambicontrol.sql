CREATE DATABASE AmbiControl;

USE AmbiControl;

-- Tabla para los usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_completo VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    tipo_documento ENUM('CC', 'TI', 'PP') NOT NULL,
    numero_documento VARCHAR(50) NOT NULL UNIQUE
);

-- Tabla para asignaciones de ambientes
CREATE TABLE asignaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero_documento VARCHAR(50) NOT NULL,
    tipo ENUM('Entrada', 'Salida') NOT NULL,
    numero_ambiente VARCHAR(50) NOT NULL,
    fecha DATE NOT NULL DEFAULT (CURRENT_DATE),
    hora TIME NOT NULL DEFAULT (CURRENT_TIME),
    FOREIGN KEY (numero_documento) REFERENCES usuarios(numero_documento)
);

-- Tabla para observaciones
CREATE TABLE observaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero_documento VARCHAR(50) NOT NULL,
    observacion TEXT NOT NULL,
    fecha DATE NOT NULL DEFAULT (CURRENT_DATE),
    hora TIME NOT NULL DEFAULT (CURRENT_TIME),
    FOREIGN KEY (numero_documento) REFERENCES usuarios(numero_documento)
);

-- Tabla para el inventario
CREATE TABLE inventario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fecha DATE NOT NULL,
    hora TIME NOT NULL,
    elemento VARCHAR(255) NOT NULL,
    disponibilidad ENUM('sí', 'no') NOT NULL,
    cantidad INT NOT NULL,
    descripcion TEXT
);
