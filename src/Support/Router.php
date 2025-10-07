<?php
namespace Televice\Support;

class Router
{
    private array $routes = [];

    public function get(string $path, callable $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, callable $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch(string $method, string $path): void
    {
        $path = rtrim($path, '/') ?: '/';
        $methodRoutes = $this->routes[$method] ?? [];
        if (isset($methodRoutes[$path])) {
            echo call_user_func($methodRoutes[$path]);
            return;
        }

        http_response_code(404);
        echo '404 Not Found';
    }
}
