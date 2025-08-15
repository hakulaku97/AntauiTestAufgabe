<?php

namespace Test\models;

use Test\Database;
use Test\Database\Csv;

class BaseModel {
    protected static ?Csv $database;

    /**
     * Returns the database connection.
     * Shared Connection by all Sub-classes
     *
     * @return Csv|null
     */
    protected static function useDatabase(): ?Csv
    {
        if (!isset(self::$database)) {
            $str_driver = 'csv';
            $str_host = str_replace('\\', '/', realpath(__DIR__.'/../../../data'));
            $str_dsn = sprintf('%s://%s', $str_driver, $str_host);
            self::$database = Database::factory($str_dsn);
        }

        return self::$database;
    }
}