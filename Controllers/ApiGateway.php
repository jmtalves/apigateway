<?php

/**
 * File ApiGateway
 */

namespace Controllers;

use Libraries\Response;
use Libraries\Encrypt;
use Models\Routes;
use GuzzleHttp\Client;
use Libraries\Memcache;

/**
 * class ApiGateway
 */
class ApiGateway
{
    //@var int $status
    public int $status;

    /**
     * handle Request
     * @param string $method method
     * @param string $path path
     * @param array $data data
     * @param string $authorization authorization
     * @param string $php_auth_user php_auth_user
     * @param string $php_auth_pw php_auth_pw
     */
    public function handleRequest(string $method, string $path, ?array $data, string $authorization, string $php_auth_user, $php_auth_pw)
    {
        $this->status = 200;

        if ($path != "api/auth") {

            /*$jwt = Encrypt::encryptJwt("a4b728c805a50b7d81115ce5d10a39d8-1-0", "E");
            var_dump($jwt);die();*/
            $jwt = Encrypt::decryptJwt($authorization, "E");
            if (!empty($jwt['error'])) {
                Response::sendResponse(503, ["msg" => $jwt['error']]);
            }
            $jwt = Encrypt::encryptJwt($jwt["sub"], "I");
            if (!empty($jwt['error'])) {
                Response::sendResponse(503, ["msg" => $jwt['error']]);
            }
            $authorization = $jwt["token"];
        }

        $processedData = $this->processRequest($method, $path, $data, $authorization, $php_auth_user, $php_auth_pw);
        Response::sendResponse($this->status, $processedData);
    }

    /**
     * process Request
     * @param string $method method
     * @param string $path path
     * @param array $data data
     * @param string $authorization authorization
     * @param string $php_auth_user php_auth_user
     * @param string $php_auth_pw php_auth_pw
     */
    private function processRequest(string $method, string $path, ?array $data, string $authorization, string $php_auth_user, string $php_auth_pw)
    {
        if($cache = Memcache::getInstance()->get($path)){
            $routes = $cache;
        }else{
            $routes = Routes::findLast("*", ["route" => $path, "status" => 1]);
            if (!$routes) {
                $this->status = 404;
                return ["msg" => "Unauthorized"];
            }
            $routes = $routes[0];
            Memcache::getInstance()->set($path, $routes);
        }
        $echoMicroserviceResponse = $this->forwardRequest($method, $routes->ip . $path, $data, $authorization, $php_auth_user, $php_auth_pw);
        return $echoMicroserviceResponse;
    }

    /**
     * forward Request
     * @param string $method method
     * @param string $url url
     * @param array $data data
     * @param string $authorization authorization
     * @param string $php_auth_user php_auth_user
     * @param string $php_auth_pw php_auth_pw
     */
    private function forwardRequest(string $method, string $url, ?array $data, string $authorization, string $php_auth_user, string $php_auth_pw)
    {
        $client = new Client();
        try {
            $response = $client->request($method, $url, [
                'headers' => ['Content-Type' => 'application/json', "Authorization" => $authorization, "PHP_AUTH_USER" => $php_auth_user, "PHP_AUTH_PW" => $php_auth_pw],
                'json' => $data,
            ]);
            $this->status = $response ? $response->getStatusCode() : '0';
            return json_decode($response->getBody(), true);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $response = $e->getResponse();
            $this->status = $response ? $response->getStatusCode() : '0';
            return json_decode($response->getBody()->getContents(), true);
        }
    }
}
