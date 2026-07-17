-- M00.1: Bootstrap - Schema version tracking table
-- This table tracks database migration versions

CREATE TABLE IF NOT EXISTS `schema_migrations` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `version` VARCHAR(50) NOT NULL UNIQUE COMMENT 'Migration version identifier (e.g., M00.1)',
  `name` VARCHAR(255) NOT NULL COMMENT 'Migration name/description',
  `executed_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'When migration was executed',
  `execution_time_ms` INT UNSIGNED DEFAULT NULL COMMENT 'Execution time in milliseconds',
  `rollback_sql` TEXT DEFAULT NULL COMMENT 'SQL to rollback this migration',
  `checksum` VARCHAR(64) DEFAULT NULL COMMENT 'Checksum of migration file for integrity'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add index for faster lookups
CREATE INDEX idx_schema_migrations_version ON `schema_migrations` (`version`);
