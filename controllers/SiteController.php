<?php

namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\Request;

class SiteController extends Controller
{
    public function handleContact(Request $request)
    {
        $try = $request->getBody();
        echo '<pre>';
        var_dump($try);
        echo '</pre>';
        exit;
        return $this->render('contact');
    }

    public function contact()
    {
        return $this->render('contact');
    }

    public function home()
    {
        $params = [
          "name" => "Stefano"
        ];
        return $this->render('home', $params);
    }
}