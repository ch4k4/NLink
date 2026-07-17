<?php
/**
 * Base Controller
 * 
 * All controllers should extend this class
 */

class BaseController
{
    protected array $data = [];
    protected string $layout = 'layouts.main';
    
    public function __construct()
    {
    }
    
    /**
     * Render a view with data
     */
    protected function view(string $view, array $data = []): string
    {
        $viewPath = $this->resolveViewPath($view);
        
        if (!file_exists($viewPath)) {
            throw new NotFoundException("View not found: {$view}", 404);
        }
        
        extract($data);
        
        ob_start();
        include $viewPath;
        $content = ob_get_clean();
        
        // If using layout, render it
        if ($this->layout) {
            $layoutPath = $this->resolveViewPath($this->layout);
            if (file_exists($layoutPath)) {
                ob_start();
                include $layoutPath;
                return ob_get_clean();
            }
        }
        
        return $content;
    }
    
    /**
     * Return JSON response
     */
    protected function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    /**
     * Redirect to a URL
     */
    protected function redirect(string $url): void
    {
        header("Location: {$url}");
        exit;
    }
    
    /**
     * Get request input
     */
    protected function input(string $key, mixed $default = null): mixed
    {
        return $_REQUEST[$key] ?? $default;
    }
    
    /**
     * Get POST input
     */
    protected function post(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $default;
    }
    
    /**
     * Get GET input
     */
    protected function get(string $key, mixed $default = null): mixed
    {
        return $_GET[$key] ?? $default;
    }
    
    /**
     * Resolve view path
     */
    private function resolveViewPath(string $view): string
    {
        $viewPath = str_replace('.', DIRECTORY_SEPARATOR, $view) . '.php';
        return dirname(__DIR__) . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . $viewPath;
    }
    
    /**
     * Set layout for the controller
     */
    public function setLayout(string $layout): self
    {
        $this->layout = $layout;
        return $this;
    }
}
