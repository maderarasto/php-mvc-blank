<?php

namespace Lib\Application\Traits;

use Exception;
use Lib\Application\Session;

trait CanRenderView
{
    function resolveViewPath(string $view, $rootDir = VIEWS_DIR)
    {
        if (empty($view)) {
            throw new Exception('A view template "' . $view . '" not found!');
        }

        $tokens = explode('.', $view);
        $viewPath = '';

        foreach ($tokens as $token) {
            $viewPath .= $token . DIRECTORY_SEPARATOR;
        }

        if (count($tokens) > 0) {
            $viewPath = substr($viewPath, 0, -1);
        }

        return $rootDir . DIRECTORY_SEPARATOR . $viewPath . '.phtml';
    }

    /**
     * Renders a view template and binds data with it.
     * 
     * @param string $view path to view seperated by '.'.
     * @param array $data binding data to view
     * @throws Exception 
     */
    function renderView(string $view, array $data = [], $layout = '')
    {   
        $viewPath = $this->resolveViewPath($view);
        $errors = [];

        if (empty($layout)) {
            $rootDir = LIB_DIR . DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR . 'views';
            $layoutPath = $this->resolveViewPath('layout', $rootDir);
        } else {
            $layoutPath = $this->resolveViewPath($layout);
        }

        if (Session::has('errors')) {
            $errors = Session::get('errors');
            Session::clear('errors');
        }

        extract([
            'slot' => function () use ($viewPath, $data, $errors) {
                extract([
                    'data' => $data,
                    'errors' => $errors,
                    'includeView' => function ($view, $data = []) {
                        include $this->resolveViewPath($view);
                    }
                ]);

                include $viewPath;
            },
            'errors' => $errors,
            'includeView' => function ($view) {
                include $this->resolveViewPath($view);
            }
        ]);

        require $layoutPath;
        exit;
    }
}