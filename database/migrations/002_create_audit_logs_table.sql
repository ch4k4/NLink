-- M00.2: Bootstrap - Audit log table for immutable event tracking
-- Required for compliance and security auditing

CREATE TABLE IF NOT EXISTS `audit_logs` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `event_type` VARCHAR(50) NOT NULL COMMENT 'Type of event (e.g., USER_LOGIN, RECORD_CREATE)',
  `event_category` VARCHAR(50) NOT NULL DEFAULT 'general' COMMENT 'Category: auth, data, clinical, system',
  `actor_id` INT UNSIGNED DEFAULT NULL COMMENT 'User ID who performed the action',
  `actor_type` VARCHAR(50) DEFAULT 'user' COMMENT 'Type of actor: user, system, api',
  `resource_type` VARCHAR(50) DEFAULT NULL COMMENT 'Type of resource affected',
  `resource_id` INT UNSIGNED DEFAULT NULL COMMENT 'ID of resource affected',
  `patient_id` INT UNSIGNED DEFAULT NULL COMMENT 'Patient ID if related to patient care',
  `action` VARCHAR(100) NOT NULL COMMENT 'Action performed',
  `status` VARCHAR(20) DEFAULT 'success' COMMENT 'success, failure, denied',
  `ip_address` VARCHAR(45) DEFAULT NULL COMMENT 'IP address of request',
  `user_agent` VARCHAR(500) DEFAULT NULL COMMENT 'Browser/client user agent',
  `correlation_id` VARCHAR(64) DEFAULT NULL COMMENT 'Correlation ID for tracing across services',
  `scope_tenant_id` INT UNSIGNED DEFAULT NULL COMMENT 'Tenant scope',
  `scope_hospital_id` INT UNSIGNED DEFAULT NULL COMMENT 'Hospital scope',
  `scope_unit_id` INT UNSIGNED DEFAULT NULL COMMENT 'Unit scope',
  `metadata_before` JSON DEFAULT NULL COMMENT 'State before change (safe subset only)',
  `metadata_after` JSON DEFAULT NULL COMMENT 'State after change (safe subset only)',
  `metadata_extra` JSON DEFAULT NULL COMMENT 'Additional context metadata',
  `occurred_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'When event occurred',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_audit_event_type (`event_type`),
  INDEX idx_audit_actor (`actor_id`, `actor_type`),
  INDEX idx_audit_resource (`resource_type`, `resource_id`),
  INDEX idx_audit_patient (`patient_id`),
  INDEX idx_audit_occurred (`occurred_at`),
  INDEX idx_audit_correlation (`correlation_id`),
  INDEX idx_audit_scope (`scope_tenant_id`, `scope_hospital_id`, `scope_unit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
