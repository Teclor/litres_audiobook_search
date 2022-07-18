<?php

namespace Rest;


class Endpoint
{
    public static function callMethod($methodName, $arguments)
    {
        return call_user_func_array([Methods::class, $methodName], $arguments);
    }

    public static function handleRequest()
    {
        $method = self::getMethod();
        $arguments = self::getArguments();
        $result = self::callMethod($method, $arguments);
        if (empty($result)) {
            self::setHeaders(['HTTP/1.1 404 Not Found']);
        }
        else {
            self::setHeaders(['Content-Type: application/json']);
            self::sendData($result);
        }
    }
    
    protected static function getMethod(): string
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = explode( '/', $uri );
        $method = $uri[1];
        
        return $method;
    }
    
    protected static function getArguments(): array
    {
        $arguments = [];
        parse_str($_SERVER['QUERY_STRING'], $query);
        foreach ($query as $paramName => $paramValue) {
            $arguments[$paramName] = $paramValue;
        }
        
        return $arguments;
    }
    
    protected static function setHeaders($httpHeaders = [])
    {
        header_remove('Set-Cookie');

        if (is_array($httpHeaders) && count($httpHeaders)) {
            foreach ($httpHeaders as $httpHeader) {
                header($httpHeader);
            }
        }
    }
    
    protected static function sendData($data)
    {
        if (!is_string($data)) {
            $result = json_encode($data);
        }
        echo $data;
        exit;
    }
}