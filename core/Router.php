<?php

namespace app\core;


/**
 * Class Router
 * @package app\core
 */
class Router
{
    public Request $request;
    public Response $response;
    protected array $routes = [];

    /**
     * Router constructor.
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response )
    {
        $this->request = $request;
        $this->response = $response;
    }


    public function get($path, $callback)   //whenever $path exists the callback will be executed
    {
        $this->routes['get'][$path] = $callback;
    }

    public function post($path, $callback)   //whenever $path exists the callback will be executed
    {
        $this->routes['post'][$path] = $callback;
    }



    public function resolve() //determinare quale è l'attuale path
    {
       $path = $this->request->getPath();
       $method = $this->request->getMethod();
       $callback = $this->routes[$method][$path] ?? false;
       if ($callback === false){
           $this->response->setStatusCode(404);
          // $this->renderContent("not found");
           $this->renderView("_404");
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
    }

    public function renderContent($viewContent)
    {
        $layoutContent = $this->layoutContent();
        return str_replace('{{content}}', $viewContent, $layoutContent);
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