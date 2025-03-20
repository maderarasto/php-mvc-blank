<?php

spl_autoload_register(function ($namespace) {
    $tokens = preg_split('/\\\\/', $namespace);
    $path = '';
    
    foreach ($tokens as $index => $token) {
        $tokenParts = preg_split('/(?=[A-Z])/', $token);

        if (count($tokenParts) > 0) {
            $tokenParts = array_slice($tokenParts, 1);
            $token = '';
        }

        foreach ($tokenParts as $tokenPart) {
            if ($index === count($tokens) - 1) {
                $token .= capitalize($tokenPart);
            } else {
                $token .= strtolower($tokenPart) . '-';
            }
        }

        if ($index < count($tokens) - 1 && count($tokenParts) > 0) {
            $token = substr($token, 0, strlen($token) - 1);
        }

        if ($token && $index === count($tokens) - 1) {
            $token = strtoupper($token[0]) . substr($token, 1);
        }

        $path .= $token . DIRECTORY_SEPARATOR;
    }

    if (count($tokens) > 0) {
        $path = ROOT_DIR . DIRECTORY_SEPARATOR . substr($path, 0, strlen($path) - 1) . '.php';
    }
    
    if (file_exists($path)) {
        require_once($path);
    } else {
        throw new Exception('Class file "' . $namespace . '" not found!');
    }
});