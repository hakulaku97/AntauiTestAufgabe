<?php
namespace Test\models;

class Log extends BaseModel {
    /**
     * Gets all Logs for a specific User
     *
     * @param $username
     *
     * @return array
     */
    public static function getLogsForUser($username): array {
        $query = self::useDatabase()
            ->buildSelect()
            ->from('log')
            ->where('username', $username);

        return self::useDatabase()->fetchAll($query);
    }

    /**
     * Inserts a new Log row into the database
     *
     * @param array $log
     *
     * @return bool
     */
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
