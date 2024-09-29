<?php

namespace Lib\Application;

class Router
{
    private function __construct()
    {
        
    }

    public static function redirectTo(string $url)
    {
        header('Location: ' . $url);
        header('Connection: close');
        exit;
    }
}