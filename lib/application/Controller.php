<?php

namespace Lib\Application;

use Lib\Application\Http\Request;
use Lib\Application\Http\Response;
use Lib\Application\Traits\CanRedirect;

/**
 * The class represents a object that handles request resolved by actions.
 */
class Controller 
{
    use CanRedirect;

    protected string $guard = '';

    public function guard()
    {
        return $this->guard;
    }

    /**
     * Retrieve a new instance of response.
     * 
     * @return Response
     */
    public function response()
    {
        return new Response();
    }
}