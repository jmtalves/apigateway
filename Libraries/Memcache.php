<?php

/**
 * File Memcached
 */

namespace Libraries;

use Memcached;

/**
 * class Memcached
 */
class Memcache
{
    //@var Memcached $memcached memcached
    private static $memcached;

    /**
     * start
     *
     * @return void
     */
    public static function start()
    {
        self::$memcached = new Memcached();
        self::$memcached->addServer(getenv('PHP_MEMCACHED_HOST'), getenv('PHP_MEMCACHED_PORT'));
    }

    /**
     * get Instance
     * @return Memcached 
     */
    public static function getInstance(){
        return self::$memcached;
    }


}
