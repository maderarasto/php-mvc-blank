<?php

namespace Lib\Application;

use Lib\Application\Response;
use Lib\Application\Traits\CanRedirect;
use Lib\Application\Traits\CanRenderView;

class Controller 
{
    use CanRedirect;
    use CanRenderView;

    protected Request $request;

    public function __construct() 
    {
        $this->request = new Request();
    }

    public function response()
    {
        return new Response();
    }
}