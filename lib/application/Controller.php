<?php

namespace Lib\Application;

use Lib\Application\Traits\CanRedirect;
use Lib\Application\Traits\CanRenderView;

class Controller 
{
    use CanRedirect;
    use CanRenderView;

    public function __construct() 
    {
        
    }
}