<?php

namespace Lib\Application;

use Lib\Application\Response;
use Lib\Application\Traits\CanRedirect;
use Lib\Application\Traits\CanRenderView;

/**
 * The class represents a object that handles request resolved by actions.
 */
class Controller 
{
    use CanRedirect;
    use CanRenderView;

    protected Request $request;

    public function __construct() 
    {
        $this->request = new Request();
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