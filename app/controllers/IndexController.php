<?php

namespace App\Controllers;

use Lib\Application\Controller;
use Lib\Application\Http\Request;

class IndexController extends Controller 
{
    public function indexAction(Request $request) 
    {   
        $this->response()
            ->layout('layout.main')
            ->view('home', [ 'title' => 'Header' ]);
    }
}