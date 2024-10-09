<?php

session_start();

/**
 * Represents interface to maintain session PHP storage.
 */
class Session
{
    private function __construct()
    {
        
    }

    /**
     * Gets a session variable with given key. If variable is not found then it will return default value.
     * 
     * @param string $key 
     * @param mixed $default 
     * @return mixed
     */
    public static function get(string $key, mixed $default = null)
    {
        if (!isset($_SESSION[$key])) {
            return $default;
        }

        return $_SESSION[$key];
    }

    /**
     * Gets keys of variables stored in session storage.
     * 
     * @return array
     */
    public static function keys()
    {
        return array_keys($_SESSION);
    }

    /**
     * Gets all session variables.
     * 
     * @return array
     */
    public static function all()
    {
        return $_SESSION;
    }

    /**
     * Sets a new value for session variable with given key and value.
     * 
     * @param string $key 
     * @param mixed $value 
     */
    public static function set(string $key, mixed $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Clears a session variable with given key. If there isn't given key it will clear all session storage.
     * 
     * @param string|null $key 
     * @return void
     */
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

    /**
     * Destroys session with all session variables.
     */
    public static function destroy()
    {
        session_destroy();
    }
}