<?php
namespace Test\models;

class Log extends BaseModel {
    /**
     * Gets all Logs for a specific User
     *
     * @param string $username
     * @param int $page
     * @param int $per_page
     *
     * @return array
     */
    public static function getLogsForUser(string $username, int $page, int $per_page): array
    {
        $query = self::useDatabase()
            ->buildSelect()
            ->from('log')
            ->where('username', $username);

        return self::useDatabase()->fetchAll($query, [], $page, $per_page);
    }

    /**
     * Inserts a new Log row into the database
     *
     * @param array $log
     *
     * @return bool
     */
    public static function insertLog(array $log): bool
    {
        $query = self::useDatabase()
            ->buildInsert()
            ->table('log');

        foreach ($log as $key => $value) {
            $query = $query->set($key, $value);
        }

        return self::useDatabase()->execute($query);
    }
}
