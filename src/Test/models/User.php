<?php

namespace Test\models;

class User extends BaseModel {
    /**
     * Gets a user by name
     *
     * @param $username
     *
     * @return array
     */
    public static function getUserByName($username): array
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
     * @param $username
     * @param $value
     *
     * @return bool
     */
    public static function updateLoginFailure($username, $value): bool {
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
     * @param $username
     * @param $value
     *
     * @return bool
     */
    public static function updateLastLogin($username, $value): bool {
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
     * @param $username
     *
     * @return bool
     */
    public static function blockUser($username): bool {
        $query = self::useDatabase()
            ->buildUpdate()
            ->set('blocked', '1')
            ->table('user')
            ->where('username', $username);

        return self::useDatabase()->execute($query);
    }
}