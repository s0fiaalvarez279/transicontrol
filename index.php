<?php
require_once 'config/config.php';
require_once 'config/database.php';

$request = $_SERVER['REQUEST_URI'];
$path = parse_url($request, PHP_URL_PATH);

// Eliminar el prefijo de la carpeta del proyecto si estás en subdirectorio
$base = '/transicontrol';
if (strpos($path, $base) === 0) {
    $path = substr($path, strlen($base));
}
if ($path === '') $path = '/';

$method = $_SERVER['REQUEST_METHOD'];

switch ($path) {
    case '/':
        // Página de inicio pública (landing page)
        require_once 'app/controllers/HomeController.php';
        $home = new HomeController();
        $home->index();
        break;

    case '/login':
        require_once 'app/views/auth/login.php';
        break;

    case '/dashboard':
        require_once 'app/controllers/DashboardController.php';
        $dashboard = new DashboardController();
        $dashboard->index();
        break;

    case '/logout':
        require_once 'app/controllers/AuthController.php';
        $auth = new AuthController();
        $auth->logout();
        break;

    case '/auth/login':
        if ($method === 'POST') {
            require_once 'app/controllers/AuthController.php';
            $auth = new AuthController();
            $auth->loginJSON();
        } else {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
        }
        break;

    case '/auth/register':
        if ($method === 'POST') {
            require_once 'app/controllers/AuthController.php';
            $auth = new AuthController();
            $auth->registerJSON();
        } else {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
        }
        break;

    default:
        http_response_code(404);
        echo "404 - Página no encontrada";
<<<<<<< HEAD
}
?>
=======
}
>>>>>>> de973c3c64ca69fbfc8cc06d37143c6990d9aafb
