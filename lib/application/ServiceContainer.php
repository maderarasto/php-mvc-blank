<?php

namespace Lib\Application;

use App\Services\CurlService;

class ServiceContainer 
{
    private static $instance;
    
    protected $services;

    private function __construct() {
        $this->services = [];
    }

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public static function register(array $services) {
        $container = self::getInstance();

        foreach ($services as $serviceName) {
            if (!class_exists($serviceName)) {
                throw new \Exception("A class with name \"$serviceName\" not found!");
            }

            if (in_array($serviceName, $container->services)) {
                unset(self::$services[$serviceName]);
            }

            $container->services[] = $serviceName;
        }
    }

    public static function get(string $clsName) {
        $container = self::getInstance();
        
        if (!class_exists($clsName)) {
            throw new \Exception("A class with name \"$clsName\" not found!");
        }
        
        $serviceIndex = array_search($clsName, $container->services);
        $serviceCls = $container->services[$serviceIndex] ?: null;
        
        if (!isset($serviceCls)) {
            throw new \Exception("A service with name \"$clsName\" not registered!");
        }

        return new $serviceCls;
    }
}