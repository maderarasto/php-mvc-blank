<?php

namespace App\Controllers;

use Lib\Application\Controller;

class IndexController extends Controller 
{
    public function indexAction() 
    {
        $this->renderView('home', ['title' => 'Nadpis']);
    }
}