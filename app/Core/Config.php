<?php
/**
 * Configuration Manager
 * 
 * Centralized configuration management
 */

class Config
{
    private static ?Config $instance = null;
    private array $config = [];
    
    private function __construct()
    {
        $this->loadDefaults();
    }
    
    public static function getInstance(): Config
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function loadDefaults(): void
    {
        $this->config = [
            'app' => [
                'name' => $_ENV['APP_NAME'] ?? 'NersLink Platform',
                'env' => $_ENV['APP_ENV'] ?? 'development',
                'debug' => $_ENV['APP_DEBUG'] ?? true,
                'url' => $_ENV['APP_URL'] ?? 'http://localhost',
            ],
            'database' => [
                'driver' => $_ENV['DB_CONNECTION'] ?? 'mysql',
                'host' => $_ENV['DB_HOST'] ?? '127.0.0.1',
                'port' => $_ENV['DB_PORT'] ?? '3306',
                'database' => $_ENV['DB_DATABASE'] ?? 'nerslink',
                'username' => $_ENV['DB_USERNAME'] ?? 'root',
                'password' => $_ENV['DB_PASSWORD'] ?? '',
            ],
            'session' => [
                'driver' => $_ENV['SESSION_DRIVER'] ?? 'files',
                'lifetime' => (int)($_ENV['SESSION_LIFETIME'] ?? 120),
                'secure' => $_ENV['SESSION_SECURE'] ?? false,
            ],
            'security' => [
                'hash_cost' => (int)($_ENV['HASH_COST'] ?? 12),
                'mfa_enabled' => $_ENV['MFA_ENABLED'] ?? false,
            ],
        ];
    }
    
    public function get(string $key, mixed $default = null): mixed
    {
        $keys = explode('.', $key);
        $value = $this->config;
        
        foreach ($keys as $k) {
            if (!isset($value[$k])) {
                return $default;
            }
            $value = $value[$k];
        }
        
        return $value;
    }
    
    public function set(string $key, mixed $value): void
    {
        $keys = explode('.', $key);
        $config = &$this->config;
        
        for ($i = 0; $i < count($keys) - 1; $i++) {
            $k = $keys[$i];
            if (!isset($config[$k])) {
                $config[$k] = [];
            }
            $config = &$config[$k];
        }
        
        $config[end($keys)] = $value;
    }
}
