<?php

namespace app\core;

/**
 * Class Application
 * @package app\core
 */
class Application
{
    /**
     * @var Router
     */
    public static string $ROOT_DIR;
    public Router $router;
    public Request $request;
    public Response $response;
    public Controller $controller;
    public static Application $app;

    /**
     * Application constructor.
     * @param $rootPath
     */
    public function __construct($rootPath)
    {
        self::$ROOT_DIR = $rootPath;
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
    }

    public function run()
    {
        echo $this->router->resolve();
    }

    /**
     * @return mixed
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @param mixed $controller
     */
    public function setController($controller): void
    {
        $this->controller = $controller;
    }
}