<?php

namespace Lib\Application;

class Application
{
    protected array $controllers;

    public function __construct()
    {
        $this->controllers = $this->loadControllers();
    }

    public function resolveUrlData()
    {
        ['path' => $path] = parse_url($_SERVER['REQUEST_URI']);
        
        // Trim last slash and spaces from both sides.
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