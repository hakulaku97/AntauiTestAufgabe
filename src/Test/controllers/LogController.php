<?php
namespace Test\controllers;

use Test\models\Log;

class LogController {
    /**
     * Saves Log data as a new row
     *
     * @param array $log
     *
     * @return void
     */
    public static function saveLog(array $log) {
        $saved = Log::insertLog($log);

        if (!$saved) {
            trigger_error('Unable to save log', E_USER_ERROR);
        }
    }

    /**
     * Gets paginated logs for a specific User
     *
     * @param string $username
     * @param int $page
     * @param int $per_page
     *
     * @return array
     */
    public static function getLogsWithPagination(string $username, int $page, int $per_page): array {
        return Log::getLogsForUser($username, $page, $per_page);
    }
}
