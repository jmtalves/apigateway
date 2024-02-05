<?php

/**
 * File Response
 */

namespace Libraries;

/**
 * class Response
 */
class Response
{
    /**
     * sendResponse
     *
     * @param  int $code code http
     * @param  array $result result to send
     * @return void
     */
    public static function sendResponse(int $code, array $result)
    {
        header('Content-Type: application/json; charset=utf-8');
        $message = isset($result["msg"]) ? $result["msg"] : "";
        http_response_code($code);
        print json_encode($result);
        die();
    }
}
