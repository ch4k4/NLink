<?php
/**
 * Database Connection Manager
 * 
 * Singleton pattern for PDO database connection
 */

class Database
{
    private static ?Database $instance = null;
    private ?PDO $connection = null;
    private array $config;
    
    private function __construct()
    {
        $config = Config::getInstance();
        $this->config = [
            'driver' => $config->get('database.driver', 'mysql'),
            'host' => $config->get('database.host', '127.0.0.1'),
            'port' => $config->get('database.port', '3306'),
            'database' => $config->get('database.database', 'nerslink'),
            'username' => $config->get('database.username', 'root'),
            'password' => $config->get('database.password', ''),
        ];
    }
    
    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection(): PDO
    {
        if ($this->connection === null) {
            $this->connect();
        }
        return $this->connection;
    }
    
    private function connect(): void
    {
        $dsn = sprintf(
            "%s:host=%s;port=%s;dbname=%s;charset=utf8mb4",
            $this->config['driver'],
            $this->config['host'],
            $this->config['port'],
            $this->config['database']
        );
        
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        
        try {
            $this->connection = new PDO(
                $dsn,
                $this->config['username'],
                $this->config['password'],
                $options
            );
        } catch (PDOException $e) {
            throw new AppException(
                "Database connection failed: " . $e->getMessage(),
                500,
                $e
            );
        }
    }
    
    /**
     * Execute a query and return affected rows
     */
    public function execute(string $sql, array $params = []): int
    {
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }
    
    /**
     * Fetch all rows from a query
     */
    public function fetchAll(string $sql, array $params = []): array
    {
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    /**
     * Fetch single row from a query
     */
    public function fetchOne(string $sql, array $params = []): ?array
    {
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result ?: null;
    }
    
    /**
     * Get last inserted ID
     */
    public function lastInsertId(): string
    {
        return $this->getConnection()->lastInsertId();
    }
    
    /**
     * Begin transaction
     */
    public function beginTransaction(): bool
    {
        return $this->getConnection()->beginTransaction();
    }
    
    /**
     * Commit transaction
     */
    public function commit(): bool
    {
        return $this->getConnection()->commit();
    }
    
    /**
     * Rollback transaction
     */
    public function rollback(): bool
    {
        return $this->getConnection()->rollBack();
    }
    
    /**
     * Execute within transaction
     */
    public function transaction(callable $callback): mixed
    {
        $this->beginTransaction();
        try {
            $result = $callback($this);
            $this->commit();
            return $result;
        } catch (Throwable $e) {
            $this->rollback();
            throw $e;
        }
    }
}
