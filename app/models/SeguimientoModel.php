<?php
require_once __DIR__ . '/../../config/database.php';

class SeguimientoModel {
    protected $table = 'seguimiento_transito';
    protected $primaryKey = 'id_seguimiento';

    public static function getAllWithDetails() {
        $conn = Database::getConnection();
        $stmt = $conn->query("
            SELECT s.*, u.nombre as usuario_nombre, t.placa, t.tipo_vehiculo 
            FROM seguimiento_transito s
            JOIN usuarios u ON s.id_usuario = u.id_usuario
            JOIN transito t ON s.id_transito = t.id_transito
            ORDER BY s.fecha_inicio DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function countByEstado($estado) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT COUNT(*) as total FROM seguimiento_transito WHERE estado = ?");
        $stmt->execute([$estado]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public static function countAll() {
        $conn = Database::getConnection();
        $stmt = $conn->query("SELECT COUNT(*) as total FROM seguimiento_transito");
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    // Relaciones
    public function usuario() {
        return "belongsTo UsuarioModel";
    }

    public function transito() {
        return "belongsTo TransitoModel";
    }

    public function reporte() {
        return "hasOne ReporteModel";
    }
}
?>