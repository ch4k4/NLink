<?php
/**
 * Base Repository
 * 
 * Abstract base class for all repositories with common CRUD operations
 */

abstract class BaseRepository
{
    protected Database $db;
    protected string $table = '';
    protected string $primaryKey = 'id';
    
    public function __construct()
    {
        $this->db = Database::getInstance();
    }
    
    /**
     * Find record by ID
     */
    public function find(int $id): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ? LIMIT 1";
        return $this->db->fetchOne($sql, [$id]);
    }
    
    /**
     * Get all records
     */
    public function all(array $orderBy = []): array
    {
        $orderClause = '';
        if (!empty($orderBy)) {
            $orders = [];
            foreach ($orderBy as $column => $direction) {
                $direction = strtoupper($direction) === 'DESC' ? 'DESC' : 'ASC';
                $orders[] = "$column $direction";
            }
            $orderClause = ' ORDER BY ' . implode(', ', $orders);
        }
        
        $sql = "SELECT * FROM {$this->table}{$orderClause}";
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Find records by condition
     */
    public function findBy(array $conditions, array $orderBy = [], int $limit = null): array
    {
        list($where, $params) = $this->buildWhere($conditions);
        
        $orderClause = '';
        if (!empty($orderBy)) {
            $orders = [];
            foreach ($orderBy as $column => $direction) {
                $direction = strtoupper($direction) === 'DESC' ? 'DESC' : 'ASC';
                $orders[] = "$column $direction";
            }
            $orderClause = ' ORDER BY ' . implode(', ', $orders);
        }
        
        $limitClause = $limit ? " LIMIT {$limit}" : '';
        
        $sql = "SELECT * FROM {$this->table} {$where}{$orderClause}{$limitClause}";
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Find one record by condition
     */
    public function findOneBy(array $conditions): ?array
    {
        $results = $this->findBy($conditions, [], 1);
        return $results[0] ?? null;
    }
    
    /**
     * Insert new record
     */
    public function insert(array $data): int
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        $this->db->execute($sql, array_values($data));
        
        return (int) $this->db->lastInsertId();
    }
    
    /**
     * Update record by ID
     */
    public function update(int $id, array $data): int
    {
        $setParts = [];
        $params = [];
        
        foreach ($data as $column => $value) {
            $setParts[] = "{$column} = ?";
            $params[] = $value;
        }
        
        $params[] = $id;
        $setClause = implode(', ', $setParts);
        
        $sql = "UPDATE {$this->table} SET {$setClause} WHERE {$this->primaryKey} = ?";
        return $this->db->execute($sql, $params);
    }
    
    /**
     * Delete record by ID
     */
    public function delete(int $id): int
    {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?";
        return $this->db->execute($sql, [$id]);
    }
    
    /**
     * Count records
     */
    public function count(array $conditions = []): int
    {
        if (empty($conditions)) {
            $sql = "SELECT COUNT(*) FROM {$this->table}";
            $params = [];
        } else {
            list($where, $params) = $this->buildWhere($conditions);
            $sql = "SELECT COUNT(*) FROM {$this->table} {$where}";
        }
        
        $result = $this->db->fetchOne($sql, $params);
        return (int) ($result['COUNT(*)'] ?? 0);
    }
    
    /**
     * Execute raw query
     */
    public function query(string $sql, array $params = []): array
    {
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Build WHERE clause from conditions
     */
    protected function buildWhere(array $conditions): array
    {
        if (empty($conditions)) {
            return ['1=1', []];
        }
        
        $where = [];
        $params = [];
        
        foreach ($conditions as $column => $value) {
            if ($value === null) {
                $where[] = "{$column} IS NULL";
            } else {
                $where[] = "{$column} = ?";
                $params[] = $value;
            }
        }
        
        return [implode(' AND ', $where), $params];
    }
}
