<?php

namespace Test\models;

class User extends BaseModel {
    /**
     * Gets a user by name
     *
     * @param string $username
     *
     * @return array
     */
    public static function getUserByName(string $username): array
    {
        $query = self::useDatabase()
            ->buildSelect()
            ->from('user')
            ->where('username', $username);

        return self::useDatabase()->fetchAssoc($query);
    }

    /**
     * Updates the failed Login Counter for a specific User
     *
     * @param string $username
     * @param string $value
     *
     * @return bool
     */
    public static function updateLoginFailure(string $username, string $value): bool
    {
        $query = self::useDatabase()
            ->buildUpdate()
            ->set('failed', $value)
            ->table('user')
            ->where('username', $username);

        return self::useDatabase()->execute($query);
    }

    /**
     * Updates the last login date for a specific User
     *
     * @param string $username
     * @param string|false $value
     *
     * @return bool
     */
    public static function updateLastLogin(string $username, $value): bool
    {
        if (!$value) {
            return false;
        }

        $query = self::useDatabase()
            ->buildUpdate()
            ->set('lastlogin', $value)
            ->table('user')
            ->where('username', $username);

        return self::useDatabase()->execute($query);
    }

    /**
     * Blocks the login for a specific User
     *
     * @param string $username
     *
     * @return bool
     */
    public static function blockUser(string $username): bool
    {
        $query = self::useDatabase()
            ->buildUpdate()
            ->set('blocked', '1')
            ->table('user')
            ->where('username', $username);

        return self::useDatabase()->execute($query);
    }
}