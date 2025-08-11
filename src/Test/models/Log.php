<?php
namespace Test\models;

class Log extends BaseModel {
    public static function getLogsForUser($username): array {
        $query = self::useDatabase()
            ->buildSelect()
            ->from('log')
            ->where('username', $username);

        return self::useDatabase()->fetchAll($query);
    }

    public static function insertLog(array $log): bool {
        $query = self::useDatabase()
            ->buildInsert()
            ->table('log');

        foreach ($log as $key => $value) {
            $query = $query->set($key, $value);
        }

        return self::useDatabase()->execute($query);
    }
}
