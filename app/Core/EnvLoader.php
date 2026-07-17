<?php
/**
 * Environment Loader
 * 
 * Loads environment variables from .env file
 */

class EnvLoader
{
    private string $basePath;
    
    public function __construct(string $basePath)
    {
        $this->basePath = rtrim($basePath, DIRECTORY_SEPARATOR);
    }
    
    public function load(): void
    {
        $envFile = $this->basePath . DIRECTORY_SEPARATOR . '.env';
        
        if (!file_exists($envFile)) {
            return; // Silent fail for development
        }
        
        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        foreach ($lines as $line) {
            // Skip comments
            if (strpos(trim($line), '#') === 0) {
                continue;
            }
            
            // Parse key=value
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value, " \t\n\r\0\x0B\"'");
                
                if (!empty($key)) {
                    $_ENV[$key] = $value;
                    putenv("$key=$value");
                    
                    // Also set as constant for frequently accessed values
                    if (!defined($key)) {
                        define($key, $value);
                    }
                }
            }
        }
    }
    
    public function get(string $key, mixed $default = null): mixed
    {
        return $_ENV[$key] ?? getenv($key) ?: $default;
    }
    
    public function getBool(string $key, bool $default = false): bool
    {
        $value = $this->get($key, $default ? 'true' : 'false');
        return in_array(strtolower($value), ['true', '1', 'yes', 'on']);
    }
}
