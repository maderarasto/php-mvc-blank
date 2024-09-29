<?php

if (!function_exists('capitalize')) {
    function capitalize(string $text) 
    {
        return strtoupper($text[0]) . substr($text, 1);
    }
}

if (!function_exists('resolve_namespace')) {
    function resolve_namespace(string $path) 
    {
        $path = str_replace(ROOT_DIR . DIRECTORY_SEPARATOR, '', $path);
        $tokens = explode(DIRECTORY_SEPARATOR, $path);
        $namespace = '';

        foreach ($tokens as $index => $token) {
            $tokenParts = preg_split('/-/', $token);
            
            if (count($tokenParts) > 0) {
                $token = '';
            }

            foreach ($tokenParts as $tokenPart) {
                $token .= capitalize($tokenPart);
            }

            $namespace .= $token . ($index < count($tokens) - 1 ? '\\' : '');
        }

        return rtrim($namespace, '.php');
    }
}

if (!function_exists('is_capitalized')) {
    function is_capitalized(string $text)
    {
        return $text[0] == strtoupper($text[0]);
    }
}

if (!function_exists('slug')) {
    function slug(string $text)
    {
        if (is_capitalized($text)) {
            $text = strtolower($text[0]) . substr($text, 1);
        }
        
        $tokens = preg_split('/(?=[A-Z])/', $text);
        $slug = '';

        foreach ($tokens as $token) {
            $slug .= strtolower($token) . '-';
        }

        if (count($tokens) > 0) {
            $slug = substr($slug, 0, -1);
        }

        return $slug;
    }
}

if (!function_exists('unslug')) {
    function unslug(string $slug) 
    {
        $tokens = preg_split('/-/', $slug);
        $text = '';

        foreach ($tokens as $token) {
            $text .= capitalize($token);
        }

        return $text;
    }
}