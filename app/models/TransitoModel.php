<?php
require_once __DIR__ . '/../../config/database.php';

class TransitoModel {
    protected $table = 'transito';
    protected $primaryKey = 'id_transito';

    public static function getAll($page = 1, $limit = 10, $search = '') {
        $conn = Database::getConnection();
        $offset = ($page - 1) * $limit;
        $searchTerm = "%$search%";
        $stmt = $conn->prepare("SELECT * FROM transito WHERE placa LIKE ? OR tipo_vehiculo LIKE ? ORDER BY id_transito DESC LIMIT ? OFFSET ?");
        $stmt->execute([$searchTerm, $searchTerm, $limit, $offset]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $countStmt = $conn->prepare("SELECT COUNT(*) as total FROM transito WHERE placa LIKE ? OR tipo_vehiculo LIKE ?");
        $countStmt->execute([$searchTerm, $searchTerm]);
        $total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        return ['data' => $data, 'total' => $total, 'page' => $page, 'limit' => $limit];
    }

    public static function find($id) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM transito WHERE id_transito = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($data) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("INSERT INTO transito (placa, tipo_vehiculo, fecha_registro) VALUES (?, ?, ?)");
        return $stmt->execute([$data['placa'], $data['tipo_vehiculo'], $data['fecha_registro']]);
    }

    public static function update($id, $data) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("UPDATE transito SET placa=?, tipo_vehiculo=?, fecha_registro=? WHERE id_transito=?");
        return $stmt->execute([$data['placa'], $data['tipo_vehiculo'], $data['fecha_registro'], $id]);
    }

    public static function delete($id) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("DELETE FROM transito WHERE id_transito = ?");
        return $stmt->execute([$id]);
    }

    public static function countAll() {
        $conn = Database::getConnection();
        $stmt = $conn->query("SELECT COUNT(*) as total FROM transito");
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function toArray($data) {
        return $data;
    }

    public function toJson($data) {
        return json_encode($data);
    }
}
?>