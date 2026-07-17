<?php
/**
 * Audit Service
 * 
 * Records immutable audit events for compliance and security
 */

class AuditService
{
    private Database $db;
    
    public function __construct()
    {
        $this->db = Database::getInstance();
    }
    
    /**
     * Log an audit event
     */
    public function log(
        string $eventType,
        string $action,
        array $options = []
    ): int {
        $data = [
            'event_type' => $eventType,
            'event_category' => $options['category'] ?? 'general',
            'actor_id' => $options['actor_id'] ?? null,
            'actor_type' => $options['actor_type'] ?? 'user',
            'resource_type' => $options['resource_type'] ?? null,
            'resource_id' => $options['resource_id'] ?? null,
            'patient_id' => $options['patient_id'] ?? null,
            'action' => $action,
            'status' => $options['status'] ?? 'success',
            'ip_address' => $options['ip_address'] ?? $this->getClientIp(),
            'user_agent' => $options['user_agent'] ?? $this->getUserAgent(),
            'correlation_id' => $options['correlation_id'] ?? $this->generateCorrelationId(),
            'scope_tenant_id' => $options['scope_tenant_id'] ?? null,
            'scope_hospital_id' => $options['scope_hospital_id'] ?? null,
            'scope_unit_id' => $options['scope_unit_id'] ?? null,
            'metadata_before' => isset($options['before']) ? json_encode($options['before']) : null,
            'metadata_after' => isset($options['after']) ? json_encode($options['after']) : null,
            'metadata_extra' => isset($options['extra']) ? json_encode($options['extra']) : null,
        ];
        
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        
        $sql = "INSERT INTO audit_logs ({$columns}) VALUES ({$placeholders})";
        
        $params = array_values($data);
        $this->db->execute($sql, $params);
        
        return (int) $this->db->lastInsertId();
    }
    
    /**
     * Log authentication event
     */
    public function logAuth(string $action, int $userId, bool $success, array $extra = []): int
    {
        return $this->log(
            $action === 'login' ? 'USER_LOGIN' : 'USER_LOGOUT',
            $action,
            [
                'category' => 'auth',
                'actor_id' => $userId,
                'status' => $success ? 'success' : 'failure',
                'extra' => $extra,
            ]
        );
    }
    
    /**
     * Log data access event
     */
    public function logAccess(string $resourceType, int $resourceId, string $action, array $options = []): int
    {
        return $this->log(
            'DATA_ACCESS',
            $action,
            array_merge([
                'category' => 'data',
                'resource_type' => $resourceType,
                'resource_id' => $resourceId,
            ], $options)
        );
    }
    
    /**
     * Log data modification event
     */
    public function logModification(
        string $resourceType,
        int $resourceId,
        string $action,
        array $before,
        array $after,
        array $options = []
    ): int {
        return $this->log(
            'DATA_MODIFICATION',
            $action,
            array_merge([
                'category' => 'data',
                'resource_type' => $resourceType,
                'resource_id' => $resourceId,
                'before' => $before,
                'after' => $after,
            ], $options)
        );
    }
    
    /**
     * Get audit logs with filters
     */
    public function getLogs(array $filters = [], int $limit = 100, int $offset = 0): array
    {
        $where = ['1=1'];
        $params = [];
        
        if (!empty($filters['event_type'])) {
            $where[] = 'event_type = ?';
            $params[] = $filters['event_type'];
        }
        
        if (!empty($filters['actor_id'])) {
            $where[] = 'actor_id = ?';
            $params[] = $filters['actor_id'];
        }
        
        if (!empty($filters['resource_type'])) {
            $where[] = 'resource_type = ?';
            $params[] = $filters['resource_type'];
        }
        
        if (!empty($filters['resource_id'])) {
            $where[] = 'resource_id = ?';
            $params[] = $filters['resource_id'];
        }
        
        if (!empty($filters['patient_id'])) {
            $where[] = 'patient_id = ?';
            $params[] = $filters['patient_id'];
        }
        
        if (!empty($filters['date_from'])) {
            $where[] = 'occurred_at >= ?';
            $params[] = $filters['date_from'];
        }
        
        if (!empty($filters['date_to'])) {
            $where[] = 'occurred_at <= ?';
            $params[] = $filters['date_to'];
        }
        
        $sql = "SELECT * FROM audit_logs WHERE " . implode(' AND ', $where);
        $sql .= " ORDER BY occurred_at DESC LIMIT {$limit} OFFSET {$offset}";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Get client IP address
     */
    private function getClientIp(): ?string
    {
        $ipKeys = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'];
        
        foreach ($ipKeys as $key) {
            if (!empty($_SERVER[$key])) {
                $ip = explode(',', $_SERVER[$key])[0];
                return trim($ip);
            }
        }
        
        return null;
    }
    
    /**
     * Get user agent
     */
    private function getUserAgent(): ?string
    {
        return $_SERVER['HTTP_USER_AGENT'] ?? null;
    }
    
    /**
     * Generate correlation ID for request tracing
     */
    private function generateCorrelationId(): string
    {
        return uniqid('req_', true) . '_' . bin2hex(random_bytes(4));
    }
}
