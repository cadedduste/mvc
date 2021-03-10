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
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }


    /**
     * @param string $path
     * @param $callback
     */
    public function get(string $path, $callback)   //whenever $path exists the callback will be executed
    {
        $this->routes['get'][$path] = $callback;
    }

    /**
     * @param string $path
     * @param $callback
     */
    public function post(string $path, $callback)   //whenever $path exists the callback will be executed
    {
        $this->routes['post'][$path] = $callback;
    }


    public function resolve() //determinare quale è l'attuale path
    {
        $method = $this->request->method();
        $path = $this->request->getPath();
        $callback = $this->routes[$method][$path] ?? false;

        if ($callback === false) {
            $this->response->setStatusCode(404);
            // $this->renderContent("not found");
            $this->renderView("_404");
            exit;
        }
        if (is_string($callback)) {
            return $this->renderView($callback);
        }

        if (is_array($callback)) {
            //$callback[0] = new $callback[0]();
            Application::$app->controller = new $callback[0]();
            $callback[0] = Application::$app->controller;
        }
        return call_user_func($callback, $this->request);
    }

    public function renderView($view, $params = [])
    {
        $layoutContent = $this->layoutContent();
        $viewContent = $this->renderOnlyView($view, $params);
        return str_replace('{{content}}', $viewContent, $layoutContent);
    }

    public function renderContent($viewContent)
    {
        $layoutContent = $this->layoutContent();
        return str_replace('{{content}}', $viewContent, $layoutContent);
    }

    protected function layoutContent()
    {
        $layout = Application::$app->controller->layout;
        ob_start();  //starts the output cashing
        include_once Application::$ROOT_DIR."/views/layouts/$layout.php";
        return ob_get_clean();   //restituisce i valori e pulisce il buffer
    }

    protected function renderOnlyView($view, $params = [])
    {
        foreach ($params as $key => $value) {
            $$key = $value;
        }

        ob_start();  //starts the output cashing
        include_once Application::$ROOT_DIR . "/views/$view.php";
        return ob_get_clean();   //restituisce i valori e pulisce il buffer
    }
}