-- 1. Tabla usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    clave VARCHAR(255) NOT NULL,
    fecha_register DATE NOT NULL
);

-- 2. Tabla incidencias (vehículos/incidencias)
CREATE TABLE IF NOT EXISTS incidencias (
    id_incidencia INT AUTO_INCREMENT PRIMARY KEY,
    placa VARCHAR(20) NOT NULL,
    tipo_vehiculo VARCHAR(50) NOT NULL,
    fecha_registro DATE NOT NULL
);

-- 3. Tabla reportes_transito (Conectada directamente a incidencias)
CREATE TABLE IF NOT EXISTS reportes_transito (
    id_reporte INT AUTO_INCREMENT PRIMARY KEY,
    id_incidencia INT,
    puntuacion INT NOT NULL,
    comentario TEXT,
    fecha_reporte DATE NOT NULL,
    -- Restricción CHECK emulada y Llave Foránea para MySQL
    CONSTRAINT check_puntuacion CHECK (puntuacion BETWEEN 1 AND 10),
    FOREIGN KEY (id_incidencia) REFERENCES incidencias(id_incidencia) ON DELETE CASCADE
);

-- --------------------------------------------------------
-- INSERTS DE PRUEBA (Sintaxis compatible con MySQL/MariaDB)

-- Insertar usuario de prueba
INSERT INTO usuarios (nombre, email, clave, fecha_register) VALUES
('Administrador', 'admin@transito.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', CURDATE());

-- Insertar algunas incidencias de ejemplo
INSERT INTO incidencias (placa, tipo_vehiculo, fecha_registro) VALUES
('ABC-123', 'Automóvil', '2024-01-15'),
('XYZ-789', 'Motocicleta', '2024-02-20'),
('LMN-456', 'Camión', '2024-03-10');

-- Insertar reportes de ejemplo
INSERT INTO reportes_transito (id_incidencia, puntuacion, comentario, fecha_reporte) VALUES
(1, 9, 'Conducción responsable', '2024-01-20'),
(2, 5, 'Exceso de velocidad detectado', '2024-02-25');