<?php
namespace Test\controllers;

use Test\models\Log;

class LogController {
    /**
     * Saves Log data as a new row
     *
     * @param $log
     *
     * @return void
     */
    public static function saveLog($log) {
        $saved = Log::insertLog($log);

        if (!$saved) {
            trigger_error('Unable to save log', E_USER_ERROR);
        }
    }

    /**
     * Gets all logs for a specific User
     *
     * @param $username
     *
     * @return array
     */
    public static function getLogs($username): array {
        return Log::getLogsForUser($username);
    }
}
