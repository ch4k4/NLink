<?php
/**
 * Database Migration Runner
 * 
 * Executes SQL migrations and tracks schema versions
 */

class MigrationRunner
{
    private Database $db;
    private string $migrationsPath;
    
    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->migrationsPath = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'migrations';
    }
    
    /**
     * Run all pending migrations
     */
    public function migrate(): array
    {
        $results = [];
        
        // Ensure migrations table exists
        $this->ensureMigrationsTable();
        
        // Get executed migrations
        $executed = $this->getExecutedMigrations();
        
        // Get migration files
        $files = $this->getMigrationFiles();
        
        foreach ($files as $file) {
            $version = $this->extractVersion($file);
            
            if (in_array($version, $executed)) {
                continue; // Already executed
            }
            
            $result = $this->executeMigration($file, $version);
            $results[] = $result;
        }
        
        return $results;
    }
    
    /**
     * Rollback last migration
     */
    public function rollback(): array
    {
        $results = [];
        
        // Get last executed migration
        $sql = "SELECT * FROM schema_migrations ORDER BY id DESC LIMIT 1";
        $last = $this->db->fetchOne($sql);
        
        if (!$last) {
            return [['status' => 'info', 'message' => 'No migrations to rollback']];
        }
        
        $result = $this->executeRollback($last);
        $results[] = $result;
        
        return $results;
    }
    
    /**
     * Get migration status
     */
    public function getStatus(): array
    {
        $executed = $this->getExecutedMigrations();
        $files = $this->getMigrationFiles();
        
        $status = [
            'total' => count($files),
            'executed' => count($executed),
            'pending' => 0,
            'migrations' => [],
        ];
        
        foreach ($files as $file) {
            $version = $this->extractVersion($file);
            $isExecuted = in_array($version, $executed);
            
            $status['migrations'][] = [
                'version' => $version,
                'file' => basename($file),
                'status' => $isExecuted ? 'executed' : 'pending',
            ];
            
            if (!$isExecuted) {
                $status['pending']++;
            }
        }
        
        return $status;
    }
    
    /**
     * Ensure migrations tracking table exists
     */
    private function ensureMigrationsTable(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS `schema_migrations` (
          `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
          `version` VARCHAR(50) NOT NULL UNIQUE,
          `name` VARCHAR(255) NOT NULL,
          `executed_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
          `execution_time_ms` INT UNSIGNED DEFAULT NULL,
          `rollback_sql` TEXT DEFAULT NULL,
          `checksum` VARCHAR(64) DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $this->db->execute($sql);
    }
    
    /**
     * Get list of executed migration versions
     */
    private function getExecutedMigrations(): array
    {
        try {
            $sql = "SELECT version FROM schema_migrations ORDER BY id";
            $rows = $this->db->fetchAll($sql);
            return array_column($rows, 'version');
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * Get migration files sorted by name
     */
    private function getMigrationFiles(): array
    {
        if (!is_dir($this->migrationsPath)) {
            return [];
        }
        
        $files = glob($this->migrationsPath . DIRECTORY_SEPARATOR . '*.sql');
        sort($files);
        
        return $files;
    }
    
    /**
     * Extract version from filename
     */
    private function extractVersion(string $file): string
    {
        $basename = basename($file, '.sql');
        // Remove numeric prefix (e.g., 001_)
        return preg_replace('/^\d+_/', '', $basename);
    }
    
    /**
     * Execute a single migration
     */
    private function executeMigration(string $file, string $version): array
    {
        $startTime = microtime(true);
        
        try {
            $sql = file_get_contents($file);
            
            // Extract rollback SQL if present
            $rollbackSql = $this->extractRollbackSql($sql);
            
            // Calculate checksum
            $checksum = hash_file('sha256', $file);
            
            // Execute migration
            $this->db->execute($sql);
            
            $executionTime = (int) ((microtime(true) - $startTime) * 1000);
            
            // Record migration
            $insertSql = "INSERT INTO schema_migrations 
                         (version, name, execution_time_ms, rollback_sql, checksum) 
                         VALUES (?, ?, ?, ?, ?)";
            
            $this->db->execute($insertSql, [
                $version,
                basename($file, '.sql'),
                $executionTime,
                $rollbackSql,
                $checksum,
            ]);
            
            return [
                'status' => 'success',
                'version' => $version,
                'file' => basename($file),
                'execution_time_ms' => $executionTime,
            ];
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'version' => $version,
                'file' => basename($file),
                'message' => $e->getMessage(),
            ];
        }
    }
    
    /**
     * Execute rollback for a migration
     */
    private function executeRollback(array $migration): array
    {
        try {
            if (empty($migration['rollback_sql'])) {
                return [
                    'status' => 'error',
                    'version' => $migration['version'],
                    'message' => 'No rollback SQL available',
                ];
            }
            
            $startTime = microtime(true);
            
            // Execute rollback
            $this->db->execute($migration['rollback_sql']);
            
            $executionTime = (int) ((microtime(true) - $startTime) * 1000);
            
            // Remove migration record
            $deleteSql = "DELETE FROM schema_migrations WHERE version = ?";
            $this->db->execute($deleteSql, [$migration['version']]);
            
            return [
                'status' => 'success',
                'version' => $migration['version'],
                'execution_time_ms' => $executionTime,
            ];
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'version' => $migration['version'],
                'message' => $e->getMessage(),
            ];
        }
    }
    
    /**
     * Extract rollback SQL from migration file
     */
    private function extractRollbackSql(string $sql): ?string
    {
        // Look for -- ROLLBACK: marker
        if (preg_match('/--\s*ROLLBACK:\s*(.+?)(?=--|$)/is', $sql, $matches)) {
            return trim($matches[1]);
        }
        
        // For simple CREATE TABLE, generate DROP TABLE
        if (preg_match('/CREATE\s+TABLE\s+(?:IF\s+NOT\s+EXISTS\s+)?[`"\']?(\w+)[`"\']?/i', $sql, $matches)) {
            $tableName = $matches[1];
            return "-- ROLLBACK: DROP TABLE\nDROP TABLE IF EXISTS `{$tableName}`;";
        }
        
        return null;
    }
}
