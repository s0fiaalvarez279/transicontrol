<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class UsuarioModel {

    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function buscarPorEmail($email) {

        $sql = "SELECT * FROM usuarios WHERE email = ?";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([$email]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crearUsuario($nombre,$email,$password) {

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuarios(nombre,email,password)
                VALUES(?,?,?)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            $nombre,
            $email,
            $hash
        ]);
    }
}