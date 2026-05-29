<?php
class HomeController {
    public function index() {
        // Esta página es pública, no requiere autenticación
        // Si el usuario ya está logueado, puede seguir viendo la home sin redirigir
        require_once __DIR__ . '/../views/home/index.php';
    }
}
?>