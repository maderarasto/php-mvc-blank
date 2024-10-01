<?php

session_start();

class Session
{
    private function __construct()
    {
        
    }

    public static function get(string $key, mixed $default = null)
    {
        if (!isset($_SESSION[$key])) {
            return $default;
        }

        return $_SESSION[$key];
    }

    public static function keys()
    {
        return array_keys($_SESSION);
    }

    public static function all()
    {
        return $_SESSION;
    }

    public static function set(string $key, mixed $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function clear(string|null $key = null)
    {
        if (isset($key)) {
            unset($_SESSION[$key]);
            return;
        }

        foreach (self::keys() as $key) {
            unset($_SESSION[$key]);
        }
    }

    public static function destroy()
    {
        session_destroy();
    }
}