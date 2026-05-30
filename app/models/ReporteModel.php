<?php
require_once __DIR__ . '/../../config/database.php';

class ReporteModel {
    protected $table = 'reportes_transito';
    protected $primaryKey = 'id_reporte';

    public static function getAllWithDetails() {
        $conn = Database::getConnection();
        $stmt = $conn->query("
            SELECT r.*, s.estado, s.fecha_inicio, u.nombre as usuario_nombre, t.placa 
            FROM reportes_transito r
            JOIN seguimiento_transito s ON r.id_seguimiento = s.id_seguimiento
            JOIN usuarios u ON s.id_usuario = u.id_usuario
            JOIN transito t ON s.id_transito = t.id_transito
            ORDER BY r.fecha_reporte DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function countAll() {
        $conn = Database::getConnection();
        $stmt = $conn->query("SELECT COUNT(*) as total FROM reportes_transito");
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
}
?>