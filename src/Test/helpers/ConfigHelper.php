<?php
namespace Test\helpers;

// Secret key for token generation (in production, store this securely)
define('SECRET_KEY', 'your_secure_secret_key_123');

class ConfigHelper {
    /**
     * Sets Login Cookies
     *
     * @param $username
     *
     * @return void
     */
    public static function setLoginCookies($username) {
        $token = hash('sha256', $username . SECRET_KEY);
        setcookie('auth_token', $token, time() + 86400, "/", "", false, true);
        setcookie('auth_user', $username, time() + 86400, "/", "", false, true);
    }

    /**
     * Deletes Login Cookies
     *
     * @return void
     */
    public static function deleteLoginCookies() {
        setcookie('auth_token', '', time() - 3600, "/");
        setcookie('auth_user', '', time() - 3600, "/");
    }

    /**
     * Checks if Login Cookie is already set, if so redirects according to array
     *
     * @param array $redirect_paths
     *
     * @return void
     */
    public static function checkForLoginCookie(array $redirect_paths) {
        $redirect_path = $redirect_paths['redirect_on_failure'];

        if (isset($_COOKIE['auth_token']) && isset($_COOKIE['auth_user'])) {
            $token = $_COOKIE['auth_token'];
            $username = $_COOKIE['auth_user'];
            $expected_token = hash('sha256', $username . SECRET_KEY);

            if ($token === $expected_token) {
                $redirect_path = $redirect_paths['redirect_on_success'];
            }
        }

        if (!empty($redirect_path)) {
            header($redirect_path);
            exit;
        }
    }
}
