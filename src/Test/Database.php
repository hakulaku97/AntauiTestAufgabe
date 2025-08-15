<?php

namespace Test;

use Test\Database\Csv;

class Database {
    const DRIVER_CSV = 'csv';

    /**
     * Database Factory
     *
     * @param $str_sdn
     * @return Csv|void
     */
    public static function factory($str_sdn)
	{
	    $str_driver = self::customParseUrl($str_sdn, PHP_URL_SCHEME);
	    switch(strtolower($str_driver)){
            case self::DRIVER_CSV:
	            return new Csv(str_replace(sprintf('%s://', $str_driver), '', $str_sdn));
            default:
	            trigger_error(sprintf('No driver support for "%s",  yet.', $str_driver), E_USER_ERROR);
	    }
	}

    /**
     * Custom URL parser
     *
     * @param string $url
     * @param int $component
     *
     * @return mixed
     */
    private static function customParseUrl(string $url, int $component = -1)
    {
        if (preg_match('#^csv:///#', $url)) {
            $url = str_replace('csv:///', 'csv://', $url);
        }

        return parse_url($url, $component);
    }
}
