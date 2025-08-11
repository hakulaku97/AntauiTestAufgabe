<?php
namespace Test\controllers;

use Test\models\Log;

class LogController {
    public static function saveLog($log) {
        $saved = Log::insertLog($log);

        if (!$saved) {
            trigger_error('Unable to save log', E_USER_ERROR);
        }
    }

    public static function getLogs($username): array {
        return Log::getLogsForUser($username);
    }
}
