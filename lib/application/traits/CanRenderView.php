<?php

namespace Lib\Application\Traits;

use Exception;

trait CanRenderView
{
    function renderView(string $view, array $data = [])
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

        $viewPath = VIEWS_DIR . DIRECTORY_SEPARATOR . $viewPath . '.phtml';

        extract(['data' => $data]);
        require($viewPath);
    }
}