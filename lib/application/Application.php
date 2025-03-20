<?php

namespace Lib\Application;

use App\Services\AuthService;
use Exception;
use Lib\Application\Http\Request;
use Lib\Application\Http\Response;

class Application
{
    use Traits\CanRedirect;

    private const ENV_FILE_PATH = ROOT_DIR . DIRECTORY_SEPARATOR . '.env';
    public const GUARD_GUEST = 'guest';
    public const GUARD_AUTH = 'auth';
    
    private array $controllers = [];
    private array $middlewares = [];

    public function __construct()
    {
        $this->controllers = $this->loadControllers();
        ServiceContainer::getInstance();
    }
    
    public function middlewares(array $middlewares)
    {
        $this->middlewares = array_filter($middlewares, function ($middleware) {
            try {
                $exists = class_exists($middleware);
            } catch (Exception $ex) {
                $exists = false;
            }

            return $exists;
        });
    }

    /**
     * Resolves controller and its action based on url. After this it will handle controller's action and its response.
     * 
     * @throws Exception 
     */
    public function handleRequest()
    {
        $urlData = $this->_resolveUrlData();
        $controllerCls = $this->_findController($urlData['controller'])  ;

        if (!$controllerCls) {
            throw new Exception('asdas');
        }

        $controllerAction = $this->_findControllerAction($controllerCls, $urlData['action']);

        if (!$controllerAction) {
            throw new Exception('asdas');
        }

        /** @var Controller */
        $controller = new $controllerCls;
        $request = new Request();
        
        foreach ($this->middlewares as $middleware) {
            if (!method_exists($middleware, 'handle')) {
                continue;
            }

            $instance = new $middleware;
            $instance->handle($request, $controller->guard());
        }
        
        /**
         * @var Response|void $response
         */ 
        $response = call_user_func([ $controller, $controllerAction ], $request, $urlData['param']);

        if ($response) {
            $response->handle();
        }
    }

    private function _resolveUrlData()
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

    private function _findController(string $controllerName)
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

    private function _findControllerAction(string $controllerCls, string $action)
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

        // Re-index values
        $controllerFiles = array_values($controllerFiles);
        
        return array_map(function ($file) {
            return resolve_namespace($file['path']);
        }, $controllerFiles);
    }

    /**
     * Loads environment variables from .env file.
     * 
     * @return void
     */
    public static function loadEnvVariables()
    {
        if (!FileSystem::exists(self::ENV_FILE_PATH)) {
            return;
        }

        $lines = FileSystem::lines(self::ENV_FILE_PATH);
        $lines = array_filter($lines, function ($line) {
            return !empty(trim($line)) && !str_starts_with($line, '#');
        });

        foreach ($lines as $line) {
            [$key, $value] = explode('=', $line);
            putenv($key . '=' . trim($value));
        }
    }

    /**
     * Loads environment variable based on key. If value is not found it will return default value.
     * 
     * @param string $key 
     * @param string|null $default 
     * @return array|bool|string|null
     */
    public static function getEnv(string $key, string|null $default = null)
    {
        $envValue = getenv($key);
           
        if (empty($envValue)) {
            return $default;
        }

        return $envValue;
    }
}