<?php

/**
 * File Request
 */

namespace Libraries;

/**
 * class Request
 */
class Request
{
    /**
     * getPostParams
     *
     * @return array
     */
    public static function getPostParams()
    {
        if (empty($_POST)) {
            $json = file_get_contents('php://input');
            return json_decode($json, true);
        } else {
            return $_POST;
        }
    }
}
