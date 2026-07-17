<?php
/**
 * Health Service
 * 
 * Application health monitoring service
 */

class HealthService
{
    public function getHealthStatus(): array
    {
        return [
            'overall' => $this->getOverallStatus(),
            'timestamp' => date('c'),
            'version' => $this->getApplicationVersion(),
            'checks' => [
                'database' => $this->checkDatabase(),
                'filesystem' => $this->checkFilesystem(),
                'environment' => $this->checkEnvironment(),
            ]
        ];
    }
    
    private function getOverallStatus(): string
    {
        $dbStatus = $this->checkDatabase();
        $fsStatus = $this->checkFilesystem();
        
        if ($dbStatus['status'] !== 'connected' || $fsStatus['status'] !== 'writable') {
            return 'unhealthy';
        }
        
        return 'healthy';
    }
    
    public function checkDatabase(): array
    {
        try {
            $db = Database::getInstance();
            $pdo = $db->getConnection();
            
            // Test connection with a simple query
            $stmt = $pdo->query("SELECT 1");
            $result = $stmt->fetchColumn();
            
            if ($result == 1) {
                return [
                    'status' => 'connected',
                    'message' => 'Database connection successful',
                    'driver' => $pdo->getAttribute(PDO::ATTR_DRIVER_NAME),
                    'version' => $pdo->getAttribute(PDO::ATTR_SERVER_VERSION),
                ];
            }
            
            return [
                'status' => 'error',
                'message' => 'Database query failed',
            ];
        } catch (Exception $e) {
            return [
                'status' => 'disconnected',
                'message' => 'Database connection failed: ' . $e->getMessage(),
            ];
        }
    }
    
    private function checkFilesystem(): array
    {
        $uploadDir = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'uploads';
        
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0755, true);
        }
        
        if (is_writable($uploadDir)) {
            return [
                'status' => 'writable',
                'message' => 'Filesystem is writable',
                'upload_path' => $uploadDir,
            ];
        }
        
        return [
            'status' => 'readonly',
            'message' => 'Upload directory is not writable',
            'upload_path' => $uploadDir,
        ];
    }
    
    private function checkEnvironment(): array
    {
        $phpVersion = PHP_VERSION;
        $requiredVersion = '8.1';
        
        $checks = [
            'php_version' => [
                'current' => $phpVersion,
                'required' => $requiredVersion,
                'ok' => version_compare($phpVersion, $requiredVersion, '>='),
            ],
            'extensions' => [
                'pdo' => extension_loaded('pdo'),
                'pdo_mysql' => extension_loaded('pdo_mysql'),
                'json' => extension_loaded('json'),
                'mbstring' => extension_loaded('mbstring'),
            ],
        ];
        
        $allOk = $checks['php_version']['ok'] && 
                 $checks['extensions']['pdo'] && 
                 $checks['extensions']['pdo_mysql'];
        
        return [
            'status' => $allOk ? 'ok' : 'warning',
            'message' => $allOk ? 'Environment checks passed' : 'Some environment checks failed',
            'details' => $checks,
        ];
    }
    
    private function getApplicationVersion(): string
    {
        return '1.0.0-dev';
    }
}
