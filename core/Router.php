<?php

declare(strict_types=1);

namespace app\core;

use app\exceptions\RouteException;

class Router
{
    private array $routes;
    private Request $request;
    private Response $response;

    public function __construct(Request $request, Response $response)
    {
        $this->routes = [Request::GET => [], Request::POST => []];
        $this->request = $request;
        $this->response = $response;
    }

    public function setGetRoute(string $path, string|array $callback): void
    {
        $this->routes[Request::GET][$path] = $callback;
    }

    public function setPostRoute(string $path, string|array $callback): void
    {
        $this->routes[Request::POST][$path] = $callback;
    }

    public function resolve(): void
    {
        $method = $this->request->getMethod();
        $uri = $this->request->getUri();
        $path = parse_url($uri, PHP_URL_PATH);

        $callback = null;

        foreach ($this->routes[$method] as $route => $handler) {
            $routePattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $route);
            if (preg_match('@^' . $routePattern . '$@', $path, $matches)) {
                $callback = $handler;
                array_shift($matches); // Удалить первый элемент ($matches[0])
                $this->request->params = $matches; // Сохранить параметры запроса
                break;
            }
        }

        if (!$callback) {
            $this->response->setStatusCode(Response::HTTP_NOT_FOUND);
            $this->renderStatic("404.html");
            return;
        }

        if (is_string($callback)) {
            $this->renderView($callback);
        } else {
            call_user_func_array($callback, array_merge([$this->request], $this->request->params));
        }
    }

    public function renderStatic(string $name): void
    {
        require PROJECT_DIR . "/web/" . $name;
    }

    public function renderView(string $name, array $context = []): void
    {
        require PROJECT_DIR . "/views/" . $name . ".php";
    }

    public function renderTemplate(string $name, array $context = []): void
    {
        Template::View($name, $context);
    }
}
