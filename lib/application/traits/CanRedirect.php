<?php

namespace Lib\Application\Traits;

trait CanRedirect 
{
    function redirectTo(string $url)
    {
        header('Location: ' . $url);
        header('Connection: close');
        exit;
    }
}