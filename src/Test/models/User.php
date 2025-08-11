<?php

namespace Test\models;

class User extends BaseModel {
    public static function getUserByName($username): array
    {
        $query = self::useDatabase()
            ->buildSelect()
            ->from('user')
            ->where('username', $username);

        return self::useDatabase()->fetchAssoc($query);
    }

    public static function updateLoginFailure($username, $value): bool {
        $query = self::useDatabase()
            ->buildUpdate()
            ->set('failed', $value)
            ->table('user')
            ->where('username', $username);

        return self::useDatabase()->execute($query);
    }

    public static function updateLastLogin($username, $value): bool {
        $query = self::useDatabase()
            ->buildUpdate()
            ->set('lastlogin', $value)
            ->table('user')
            ->where('username', $username);

        return self::useDatabase()->execute($query);
    }

    public static function blockUser($username): bool {
        $query = self::useDatabase()
            ->buildUpdate()
            ->set('blocked', '1')
            ->table('user')
            ->where('username', $username);

        return self::useDatabase()->execute($query);
    }
}