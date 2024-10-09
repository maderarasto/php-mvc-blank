<?php

namespace Lib\Application\Traits;

trait CanRedirect 
{
    /**
     * Redirects user to given url.
     * 
     * @param string $url 
     */
    function redirectTo(string $url)
    {
        header('Location: ' . $url);
        header('Connection: close');
        exit;
    }
}