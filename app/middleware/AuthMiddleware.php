<?php
class AuthMiddleware {
    public static function check() {
        session_name('transicontrol_session');
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: /transicontrol/');
            exit;
        }
    }
}
?>