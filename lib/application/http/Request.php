<?php

namespace Lib\Application\Http;

class Request
{
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    
    public function __construct()
    {
        
    }

    public function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function uri()
    {
        return ltrim($_SERVER['REQUEST_URI'], '/');
    }

    public function referer()
    {
        return $_SERVER['HTTP_REFERER'];
    }

    /** 
     * Get keys of all post data.
     **/
    public function keys() : array
    {
        return array_keys($_POST);
    }

    /**
     * Checks if all given keys are present in post data.
     **/
    public function has(string|array $keys) : bool
    {
        $postKeys = array_keys($_POST);
        
        if (!is_array($keys)) {
            return in_array($keys, $postKeys);
        }

        return count(array_intersect($postKeys, $keys)) == count($keys);
    }

    /**
     * Checks if one of given keys is present in post data.
     **/
    public function hasAny(array $keys)
    {
        return !empty(array_intersect(array_keys($_POST), $keys));
    }

    /**
     * Checks if key is present in headers.
     **/
    public function hasHeader(string $key)
    {
        return in_array($key, array_keys(getallheaders()));
    }

    /**
     * Checks if key is present in cookies.
     **/
    public function hasCookie(string $key)
    {
        return isset($_COOKIE[$key]);
    }

    /**
     * Checks if key is present in files.
     **/
    public function hasFile(string $key)
    {
        return isset($_FILES[$key]);
    }

    /**
     * Get a request header or all header if the key isn't specified.
     * If header isn't found the it will return default value.
     **/
    public function header(string|null $key, string|null $default = null)
    {
        $headers = getallheaders();

        if (empty($headers) && isset($default)) {
            return $default;
        }
        
        if (!isset($key)) {
            return $headers;
        }

        if (!in_array($key, array_keys($headers))) {
            return $default;
        }

        return $headers[$key];
    }

    /**
     * Checks if the request accepts content type.
     **/
    public function accepts(string $contentType)
    {   
        $acceptHeader = $this->header('accept');
        return $acceptHeader && strpos($acceptHeader, $contentType) !== false;

    }

    /**
     * Get a request cookie or all cookies if the key isn't specified.
     * If cookie isn't found it will return default value.
     **/
    public function cookie(string|null $key, string|array|null $default = null)
    {
        if (empty($_COOKIE) && isset($default)) {
            return $default;
        }
        
        if (!isset($key)) {
            return $_COOKIE;
        }

        if (!in_array($key, array_keys($_COOKIE))) {
            return $default;
        }

        return $_COOKIE[$key];
    }

    /**
     * Gets user agent of request.
     **/
    public function userAgent()
    {
        return  $_SERVER['HTTP_USER_AGENT'];
    }

    /**
     * Get a request query param or all query params if the key isn't specified.
     * If query param isn't found it will return default value.
     **/
    public function query(string|null $key = null, string|array|null $default = null)
    {
        if (empty($_GET) && isset($default)) {
            return $default;
        }
        
        if (!isset($key)) {
            return $_GET;
        }

        if (!in_array($key, array_keys($_GET))) {
            return $default;
        }

        return $_GET[$key];
    }

    /**
     * Get a request post param or all post params if the key isn't specified.
     * If post param isn't found it will return default value.
     */
    public function post(string|null $key = null, string|array|null $default = null) 
    {
        if (empty($_POST) && isset($default)) {
            return $default;
        }
        
        if (!isset($key)) {
            return $_POST;
        }

        if (!in_array($key, array_keys($_POST))) {
            return $default;
        }

        return $_POST[$key];
    }

    /**
     * Gets all of post data.
     * 
     * @return array
     */
    public function all()
    {
        return $_POST;
    }

    /**
     * Gets post data which keys are present in given keys array.
     * 
     * @return array
     */
    public function only(array $keys) : array
    {
        return array_filter($_POST, function ($key) use ($keys) {
            return in_array($key, $keys);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * Gets post data which keys are not present in given keys array.
     * 
     * @return array
     */
    public function except(array $keys)
    {
        return array_filter(array_keys($_POST), function ($key) use ($keys) {
            return !in_array($key, $keys);
        });
    }

    /**
     * Gets a request file param or all file params if the key isn't specified.
     * If file param isn't found it will return default value.
     */
    public function file(string|null $key = null, mixed $default = null)
    {
        if (empty($_FILES) && isset($default)) {
            return $default;
        }
        
        if (!isset($key)) {
            return $_FILES;
        }

        if (!in_array($key, array_keys($_FILES))) {
            return $default;
        }

        return $_FILES[$key];
    }

    /**
     * Get a request server param or all server params if the key isn't specified.
     * If server param isn't found it will return default value.
     */
    public function server(string|null $key = null, string|array|null $default = null)
    {
        if (empty($_SERVER) && isset($default)) {
            return $default;
        }
        
        if (!isset($key)) {
            return $_SERVER;
        }

        if (!in_array($key, array_keys($_SERVER))) {
            return $default;
        }

        return $_SERVER[$key];
    }
}