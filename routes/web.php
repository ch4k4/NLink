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

// Note: More routes will be added as modules are implemented
// M01 - Organization routes
// M02 - Authentication routes
// M03 - RBAC routes
// M07 - Patient routes
// M09 - Homecare routes
// M10 - Woundcare routes
