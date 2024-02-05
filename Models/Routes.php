<?php

/**
 * File Routes
 *
 */

namespace Models;

use Config\Database;

/**
 * class Routes
 *
 */
class Routes
{
    // @var string  $route  route
    public string $route;

    // @var string  $ip  ip
    public string $ip;

    // @var string  $status  status
    public int $status;

    /**
     * find
     *
     * @param  string $columns
     * @param  array $filters
     * @return array|null
     */
    public static function find(string $columns = "*", array $filters = [])
    {
        $sql = "SELECT " . $columns . " FROM routes  ";
        if (!empty($filters)) {
            $sql .= " WHERE ";
            $count = 0;
            foreach ($filters as $column => $value) {
                if ($count > 0) {
                    $sql .= " AND ";
                }
                $sql .= $column . " = :" . $column;
                $count++;
            }
        }
        return Database::getResults($sql, $filters);
    }

    /**
     * find Last
     *
     * @param  string $columns
     * @param  array $filters
     * @return array|null
     */
    public static function findLast(string $columns = "*", array $filters = [])
    {
        $sql = " SELECT " . $columns . " FROM routes WHERE status=:status and :route LIKE CONCAT(route, '%') ORDER BY LENGTH(route) DESC limit 1";
        
        return Database::getResults($sql, $filters);
    }
}
