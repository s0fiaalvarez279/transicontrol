<?php
require_once __DIR__ . '/../middleware/AuthMiddleware.php';

class DashboardController {
    public function index() {
        AuthMiddleware::check();
        require_once __DIR__ . '/../views/layouts/spa_base.php';
    }
}
?>