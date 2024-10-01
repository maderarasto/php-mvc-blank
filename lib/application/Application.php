<?php

namespace Lib\Application;

use Exception;

class Application
{
    const ENV_FILE_PATH = ROOT_DIR . DIRECTORY_SEPARATOR . '.env';

    protected static array $env;
    protected array $controllers;

    public function __construct()
    {
        $this->controllers = $this->loadControllers();
    }
    
    public static function loadEnvVariables()
    {
        self::$env = [];
        
        if (!FileSystem::exists(self::ENV_FILE_PATH)) {
            return;
        }

        $content = FileSystem::get(self::ENV_FILE_PATH);
        $matches;

        preg_match_all('/(\w+)="(.*?)"/', $content, $matches);
        [, $keys, $values] = $matches;

        foreach ($keys as $index => $key) {
            self::$env[$key] = $values[$index];
        }
    }

    public static function getEnv(string $key, string|null $default = null)
    {
        $envValue = self::$env[$key]; 
        
        if (!isset($envValue)) {
            return $default;
        }

        return $envValue;
    }

    public function handleRequest()
    {
        $urlData = $this->resolveUrlData();
        $controllerCls = $this->findController($urlData['controller'])  ;

        if (!$controllerCls) {
            throw new Exception('asdas');
        }

        $controllerAction = $this->findControllerAction($controllerCls, $urlData['action']);

        if (!$controllerAction) {
            throw new Exception('asdas');
        }

        $controller = new $controllerCls;
        call_user_func([ $controller, $controllerAction ]);
    }

    public function resolveUrlData()
    {
        ['path' => $path] = parse_url($_SERVER['REQUEST_URI']);
        
        // Trim slashes and spaces from both sides.
        $path = trim($path, '/');
        $path = trim($path);
        
        [$controller, $action, $param] = explode('/', $path) + [
            1 => 'index', 
            2 => null
        ];
        
        if (empty($controller)) {
            $controller = 'index';
        }

        return [
            'controller' => $controller,
            'action' => $action,
            'param' => $param
        ];
    }

    public function findController(string $controllerName)
    {
        if (strpos($controllerName, 'Controller') === false) {
            $controllerName = unslug($controllerName) . 'Controller';
        }

        $found = array_filter($this->controllers, function ($controller) use ($controllerName) {
            return strpos($controller, '\\' . $controllerName);
        });
        
        // Re-index values
        $found = array_values($found);

        return count($found) > 0 ? $found[0] : null;
    }

    public function findControllerAction(string $controllerCls, string $action)
    {
        if (strpos($action, 'Action') === false) {
            $action = unslug($action, CASE_CAMEL) . 'Action';
        }

        return method_exists($controllerCls, $action) ? $action : null;
    }

    protected function loadControllers()
    {
        $controllerFiles = FileSystem::getFiles(CONTROLLERS_DIR, true);

        // Filter files that contains in name "Controller"
        $controllerFiles = array_filter($controllerFiles, function ($file)  {
            return strpos($file['name'], 'Controller') !== false;
        });

        // Filter files that are subclass of class "Controller".
        $controllerFiles = array_filter($controllerFiles, function ($file) {
            $namespace = resolve_namespace($file['path']);
            return is_subclass_of($namespace, Controller::class);
        });

        // Reindex values
        $controllerFiles = array_values($controllerFiles);
        
        return array_map(function ($file) {
            return resolve_namespace($file['path']);
        }, $controllerFiles);
    }
}