<?php
namespace Test\helpers;

// Secret key for token generation (in production, store this securely)
define('SECRET_KEY', 'your_secure_secret_key_123');

class ConfigHelper {
    public static function setCookies($username) {
        $token = hash('sha256', $username . SECRET_KEY);
        setcookie('auth_token', $token, time() + 86400, "/", "", false, true);
        setcookie('auth_user', $username, time() + 86400, "/", "", false, true);
    }

    public static function unsetCookies() {
        setcookie('auth_token', '', time() - 3600, "/");
        setcookie('auth_user', '', time() - 3600, "/");
    }

    public static function checkForLoginCookie() {
        if (isset($_COOKIE['auth_token']) && isset($_COOKIE['auth_user'])) {
            $token = $_COOKIE['auth_token'];
            $username = $_COOKIE['auth_user'];
            $expected_token = hash('sha256', $username . SECRET_KEY);
            if ($token === $expected_token) {
                header("Location: pages/greetingsPage.php");
                exit;
            }
        }
    }
}
