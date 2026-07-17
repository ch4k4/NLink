<?php
/**
 * Web Routes
 * 
 * Define all web application routes here
 */

// Dashboard
$router->get('/', ['DashboardController', 'index']);

// Health endpoints
$router->get('/health', ['HealthController', 'dashboard']);
$router->get('/health/api', ['HealthController', 'index']);
$router->get('/health/database', ['HealthController', 'database']);

// M00.2 - Developer Console & Migration UI
$router->get('/admin/migrations', ['Admin\MigrationController', 'index']);
$router->post('/admin/migrations/run', ['Admin\MigrationController', 'run']);
$router->post('/admin/migrations/rollback', ['Admin\MigrationController', 'rollback']);
$router->get('/admin/migrations/refresh', ['Admin\MigrationController', 'refresh']);

// Note: More routes will be added as modules are implemented
// M01 - Organization routes
// M02 - Authentication routes
// M03 - RBAC routes
// M07 - Patient routes
// M09 - Homecare routes
// M10 - Woundcare routes
