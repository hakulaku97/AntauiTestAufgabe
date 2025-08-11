<?php

namespace Test\models;

use Test\Database;

class BaseModel {

    protected static $database;

    protected static function useDatabase(): ?Database\Csv
    {
        if (!isset(self::$database)) {
            $str_driver = 'csv';
            $str_host = str_replace('\\', '/', realpath(__DIR__.'/../../../data'));
            $str_dsn = sprintf('%s:/%s', $str_driver, $str_host);
            self::$database = Database::factory($str_dsn);
        }

        return self::$database;
    }
}