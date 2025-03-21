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
    function unslug(string $slug, $case = CASE_PASCAL) 
    {
        $tokens = preg_split('/-/', $slug);
        $text = '';

        foreach ($tokens as $index => $token) {
            if ($case == CASE_CAMEL && $index === 0) {
                $text = strtolower($token);
            } else if ($case == CASE_SNAKE) {
                $text .= strtolower($token) . '_';
            } else {
                $text .= capitalize($token);
            }
        }

        if ($case == CASE_SNAKE) {
            $text = substr($text, 0, -1);
        }

        return $text;
    }
}

if (!function_exists('env')) {
    function env(string $key, string|null $default = null)
    {
        return \Lib\Application\Application::getEnv($key, $default);
    }
}

if (!function_exists('get_class_name')) {
    function get_class_name(object $obj)
    {
        $class = get_class($obj);
        $classTokens = explode('\\', $class);

        return end($classTokens);
    }
}

if (!function_exists('print_object')) {
    function print_object(object $obj, $indent = 1)
    {
        $base_indent = 16;
        $text = get_class_name($obj) . ' (<br />';

        foreach (get_object_vars($obj) as $key => $value) {
            if (is_array($value)) {
                continue;
            }

            $text .= '<span style="padding-left: ' . ($indent * $base_indent) . 'px;">"' . $key . '" => ';

            if (is_array($value)) {
                $text .= print_array($value, $indent + 1);
            } else if (is_string($value)) {
                $text .= '"' . $value . '",<br />';
            } else {
                $text .= $value . ',<br />';
            }
        }

        $text .= '<span style="padding-left: ' . (($indent - 1) * $base_indent) . 'px">),</span><br />';

        return $text;
    }
}

if (!function_exists('print_array')) {
    function print_array(array $array, int $indent = 2)
    {
        $base_indent = 16;
        $text = 'Array (<br />';

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                continue;
            }

            $text .= '<span style="padding-left: ' . ($indent * $base_indent) . 'px;">"' . $key . '" => ';

            if (is_array($value)) {
                $text .= print_array($value, $indent + 1);
            } else if (is_string($value)) {
                $text .= '"' . $value . '",<br />';
            } else {
                $text .= $value . ',<br />';
            }
        }

        $text .= '<span style="padding-left: ' . (($indent - 1) * $base_indent) . 'px">),</span><br />';

        return $text;
    }
}

if (!function_exists('is_json')) {
    function is_json($str) 
    {
        json_decode($str);
        return json_last_error() === JSON_ERROR_NONE;
    }
}   

if (!function_exists('is_between')) {
    function is_between(int $value, int $min, int $max)
    {
        return $value >= $min && $value <= $max;
    }
}