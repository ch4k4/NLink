<?php

namespace App\Database;

use PDOException;
use App\Core\Database;

class MigrationManager
{
    private $db;
    private $migrationPath;
    private $table = 'schema_migrations';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
        $this->migrationPath = __DIR__ . '/../../database/migrations';
    }

    /**
     * Pastikan tabel migrations ada
     */
    public function ensureMigrationsTableExists(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS {$this->table} (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255) NOT NULL UNIQUE,
            executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        $this->db->exec($sql);
    }

    /**
     * Dapatkan daftar migrasi yang sudah dijalankan
     */
    public function getExecutedMigrations(): array
    {
        $stmt = $this->db->query("SELECT migration FROM {$this->table} ORDER BY id ASC");
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }

    /**
     * Dapatkan daftar file migrasi di folder
     */
    public function getAvailableMigrations(): array
    {
        if (!is_dir($this->migrationPath)) {
            return [];
        }

        $files = scandir($this->migrationPath);
        $migrations = [];

        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'sql') {
                $migrations[] = $file;
            }
        }

        sort($migrations);
        return $migrations;
    }

    /**
     * Dapatkan status semua migrasi
     */
    public function getStatus(): array
    {
        $executed = $this->getExecutedMigrations();
        $available = $this->getAvailableMigrations();
        $status = [];

        foreach ($available as $migration) {
            $status[] = [
                'name' => $migration,
                'status' => in_array($migration, $executed) ? 'executed' : 'pending',
                'executed_at' => in_array($migration, $executed) ? $this->getExecutionTime($migration) : null
            ];
        }

        return $status;
    }

    private function getExecutionTime(string $migration): ?string
    {
        $stmt = $this->db->prepare("SELECT executed_at FROM {$this->table} WHERE migration = ?");
        $stmt->execute([$migration]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result ? $result['executed_at'] : null;
    }

    /**
     * Jalankan semua migrasi yang pending
     */
    public function run(): array
    {
        $this->ensureMigrationsTableExists();
        $executed = $this->getExecutedMigrations();
        $available = $this->getAvailableMigrations();
        $results = [];

        foreach ($available as $migration) {
            if (!in_array($migration, $executed)) {
                try {
                    $this->executeMigration($migration);
                    $results[] = ['migration' => $migration, 'status' => 'success', 'message' => 'Executed successfully'];
                } catch (\Exception $e) {
                    $results[] = ['migration' => $migration, 'status' => 'failed', 'message' => $e->getMessage()];
                    // Stop on failure
                    break; 
                }
            }
        }

        return $results;
    }

    /**
     * Rollback migrasi terakhir
     */
    public function rollback(): array
    {
        $executed = $this->getExecutedMigrations();
        
        if (empty($executed)) {
            return [['status' => 'info', 'message' => 'No migrations to rollback']];
        }

        $lastMigration = end($executed);
        $results = [];

        try {
            // Simple rollback: hapus record dari schema_migrations
            $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE migration = ?");
            $stmt->execute([$lastMigration]);
            
            $results[] = ['migration' => $lastMigration, 'status' => 'success', 'message' => 'Rolled back successfully (record removed)'];
            
            $this->logAudit('ROLLBACK', $lastMigration);

        } catch (\Exception $e) {
            $results[] = ['migration' => $lastMigration, 'status' => 'failed', 'message' => $e->getMessage()];
        }

        return $results;
    }

    private function executeMigration(string $filename): void
    {
        $filepath = $this->migrationPath . '/' . $filename;
        $sql = file_get_contents($filepath);

        $this->db->exec($sql);

        $stmt = $this->db->prepare("INSERT INTO {$this->table} (migration) VALUES (?)");
        $stmt->execute([$filename]);

        $this->logAudit('MIGRATE', $filename);
    }

    private function logAudit(string $action, string $migration): void
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO audit_logs (action, entity_type, entity_id, description, ip_address, user_agent) VALUES (?, ?, ?, ?, ?, ?)");
            $actionDesc = "$action migration $migration";
            $stmt->execute([
                $action,
                'SYSTEM_MIGRATION',
                null,
                $actionDesc,
                $_SERVER['REMOTE_ADDR'] ?? 'CLI',
                $_SERVER['HTTP_USER_AGENT'] ?? 'CLI'
            ]);
        } catch (\Exception $e) {
            // Ignore audit failure during migration
        }
    }
}
