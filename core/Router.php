<?php

namespace app\core;


/**
 * Class Router
 * @package app\core
 */
class Router
{
    public Request $request;
    protected array $routes = [];

    /**
     * Router constructor.
     * @param $request
     */
    public function __construct(\app\core\Request $request)
    {
        $this->request = $request;
    }


    public function get($path, $callback)   //whenever $path exists the callback will be executed
    {
        $this->routes['get'][$path] = $callback;
    }

    public function resolve() //determinare quale è l'attuale path
    {
       $path = $this->request->getPath();
       $method = $this->request->getMethod();
       $callback = $this->routes[$method][$path] ?? false;
       if ($callback === false){
           return "not found";
           exit;
       }
       if (is_string($callback)){
           return $this->renderView($callback);
       }
       return call_user_func($callback);
    }

    public function renderView($view)
    {
        $layoutContent = $this->layoutContent();
        $viewContent = $this->renderOnlyView($view);
        return str_replace('{{content}}', $viewContent, $layoutContent);
        include_once Application::$ROOT_DIR."/views/$view.php";
    }

    protected function layoutContent()
    {
        ob_start();  //starts the output cashing
        include_once Application::$ROOT_DIR."/views/layouts/main.php";
        return ob_get_clean();   //restituisce i valori e pulisce il buffer
    }

    protected function renderOnlyView($view)
    {
        ob_start();  //starts the output cashing
        include_once Application::$ROOT_DIR."/views/$view.php";
        return ob_get_clean();   //restituisce i valori e pulisce il buffer
    }
}