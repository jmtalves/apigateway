<?php

/**
 * File Start
 */

use Libraries\Response;
use Libraries\Request;
use Controllers\ApiGateway;
use Libraries\Memcache;

/**
 * class Start
 */
class Start
{

    /**
     * init
     *
     * @throws \Exception
     * @return void
     */
    public function init()
    {
        if (!isset($_GET['param'])) {
            Response::sendResponse(400, ["msg" => "Bad Request"]);
        }
        try {
            if (empty($_SERVER['HTTP_AUTHORIZATION'])) {
                Response::sendResponse(401, ["msg" => "Authentication not received"]);
            }
            Memcache::start();

            $api_gateway = new ApiGateway();
            $method = $_SERVER['REQUEST_METHOD'];
            $path = $_GET['param'];
            $data =   Request::getPostParams();
            $http_authorization = $_SERVER['HTTP_AUTHORIZATION'];
            $php_auth_user = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : '';
            $php_auth_pw = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : '';
            $api_gateway->handleRequest($method, $path, $data, $http_authorization, $php_auth_user, $php_auth_pw);
        } catch (\Exception $e) {
            Response::sendResponse(400, ["msg" => $e->getMessage()]);
        }
    }
}
