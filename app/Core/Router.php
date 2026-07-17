<?php
/**
 * Router
 * 
 * Simple PSR-7 inspired router for the application
 */

class Router
{
    private array $routes = [];
    private array $middleware = [];
    private string $basePath = '';
    
    public function __construct(string $basePath = '')
    {
        $this->basePath = rtrim($basePath, '/');
    }
    
    public function get(string $path, callable|array $handler): self
    {
        return $this->addRoute('GET', $path, $handler);
    }
    
    public function post(string $path, callable|array $handler): self
    {
        return $this->addRoute('POST', $path, $handler);
    }
    
    public function put(string $path, callable|array $handler): self
    {
        return $this->addRoute('PUT', $path, $handler);
    }
    
    public function delete(string $path, callable|array $handler): self
    {
        return $this->addRoute('DELETE', $path, $handler);
    }
    
    public function any(string $path, callable|array $handler): self
    {
        return $this->addRoute(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], $path, $handler);
    }
    
    private function addRoute(array|string $methods, string $path, callable|array $handler): self
    {
        $methods = (array) $methods;
        $path = $this->normalizePath($path);
        
        foreach ($methods as $method) {
            $this->routes[$method][$path] = [
                'handler' => $handler,
                'middleware' => [],
            ];
        }
        
        return $this;
    }
    
    public function middleware(array $middleware): self
    {
        $this->middleware = array_merge($this->middleware, $middleware);
        return $this;
    }
    
    private function normalizePath(string $path): string
    {
        $path = '/' . trim($path, '/');
        if ($this->basePath && strpos($path, $this->basePath) !== 0) {
            $path = $this->basePath . $path;
        }
        return $path === '' ? '/' : $path;
    }
    
    public function dispatch(string $method, string $uri): mixed
    {
        $method = strtoupper($method);
        $uri = $this->normalizePath($uri);
        
        // Check for exact match first
        if (isset($this->routes[$method][$uri])) {
            return $this->executeRoute($this->routes[$method][$uri]);
        }
        
        // Check for parameterized routes
        foreach ($this->routes[$method] ?? [] as $routePath => $route) {
            $params = $this->matchParameters($routePath, $uri);
            if ($params !== false) {
                return $this->executeRoute($route, $params);
            }
        }
        
        throw new NotFoundException("Route not found: {$method} {$uri}", 404);
    }
    
    private function matchParameters(string $routePath, string $uri): array|false
    {
        $routeParts = explode('/', trim($routePath, '/'));
        $uriParts = explode('/', trim($uri, '/'));
        
        if (count($routeParts) !== count($uriParts)) {
            return false;
        }
        
        $params = [];
        
        foreach ($routeParts as $index => $part) {
            if (strpos($part, '{') === 0 && strpos($part, '}') === strlen($part) - 1) {
                $paramName = trim($part, '{}');
                $params[$paramName] = $uriParts[$index];
            } elseif ($part !== $uriParts[$index]) {
                return false;
            }
        }
        
        return $params;
    }
    
    private function executeRoute(array $route, array $params = []): mixed
    {
        $handler = $route['handler'];
        $allMiddleware = array_merge($this->middleware, $route['middleware'] ?? []);
        
        // Execute middleware chain
        $next = function() use ($handler, $params) {
            if (is_array($handler)) {
                [$controller, $method] = $handler;
                $controllerInstance = is_string($controller) ? new $controller() : $controller;
                return $controllerInstance->$method($params);
            }
            return $handler($params);
        };
        
        // Wrap handler with middleware
        foreach (array_reverse($allMiddleware) as $middleware) {
            $currentNext = $next;
            $next = function() use ($middleware, $currentNext, $params) {
                return $middleware($params, $currentNext);
            };
        }
        
        return $next();
    }
    
    public function getRoutes(): array
    {
        return $this->routes;
    }
}
