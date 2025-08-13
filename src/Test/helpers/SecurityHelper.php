<?php
namespace Test\helpers;


class SecurityHelper {
    /**
     * Gets the SALT_KRY from the environment configuration
     *
     * @return array|false|string
     */
    public static function getSaltKeyFromEnvironment() {
        $salt_key = getenv('SALT_KEY');

        if (empty($salt_key)) {
            trigger_error('SALT_KEY not set in environment', E_USER_ERROR);
        }

        return $salt_key;
    }
}
