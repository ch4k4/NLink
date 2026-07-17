<?php
/**
 * Health Controller
 * 
 * Application health check endpoints
 */

class HealthController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->setLayout(''); // No layout for health endpoints
    }
    
    /**
     * Health check endpoint
     */
    public function index(): void
    {
        $healthService = new HealthService();
        $status = $healthService->getHealthStatus();
        
        $httpStatus = $status['overall'] === 'healthy' ? 200 : 503;
        $this->json($status, $httpStatus);
    }
    
    /**
     * Health dashboard page
     */
    public function dashboard(): string
    {
        $healthService = new HealthService();
        $status = $healthService->getHealthStatus();
        
        return $this->view('health.dashboard', [
            'status' => $status,
            'pageTitle' => 'System Health'
        ]);
    }
    
    /**
     * Database connectivity check
     */
    public function database(): void
    {
        $healthService = new HealthService();
        $dbStatus = $healthService->checkDatabase();
        
        $httpStatus = $dbStatus['status'] === 'connected' ? 200 : 503;
        $this->json($dbStatus, $httpStatus);
    }
}
