<?php

namespace Test;

class Database {
    const DRIVER_CSV = 'csv';

    /**
     * Database Factory
     *
     * @param $str_sdn
     * @return Database\Csv|void
     */
    public static function factory($str_sdn)
	{
	    $str_driver = parse_url($str_sdn, PHP_URL_SCHEME);
	    switch(strtolower($str_driver)){
            case self::DRIVER_CSV:
                //TODO: Check for slash
	            return new Database\Csv(str_replace(sprintf('%s:/', $str_driver), '', $str_sdn));
            default:
	            trigger_error(sprintf('No driver support for "%s",  yet.', $str_driver), E_USER_ERROR);
	    }
	}
}
