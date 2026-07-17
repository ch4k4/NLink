<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Database\MigrationManager;

class MigrationController extends BaseController
{
    private $migrationManager;

    public function __construct()
    {
        parent::__construct();
        $this->migrationManager = new MigrationManager();
    }

    /**
     * Tampilkan halaman migration dashboard
     */
    public function index()
    {
        try {
            $status = $this->migrationManager->getStatus();
            
            return $this->render('admin/migrations/index', [
                'title' => 'Migration Console',
                'migrations' => $status,
                'total' => count($status),
                'executed' => count(array_filter($status, fn($m) => $m['status'] === 'executed')),
                'pending' => count(array_filter($status, fn($m) => $m['status'] === 'pending'))
            ]);
        } catch (\Exception $e) {
            return $this->renderError('Failed to load migrations: ' . $e->getMessage());
        }
    }

    /**
     * Jalankan semua migrasi pending
     */
    public function run()
    {
        header('Content-Type: application/json');
        
        try {
            $results = $this->migrationManager->run();
            
            echo json_encode([
                'success' => true,
                'message' => 'Migration process completed',
                'results' => $results
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Rollback migrasi terakhir
     */
    public function rollback()
    {
        header('Content-Type: application/json');
        
        try {
            $results = $this->migrationManager->rollback();
            
            echo json_encode([
                'success' => true,
                'message' => 'Rollback completed',
                'results' => $results
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Refresh status migrasi (AJAX)
     */
    public function refresh()
    {
        header('Content-Type: application/json');
        
        try {
            $status = $this->migrationManager->getStatus();
            
            echo json_encode([
                'success' => true,
                'migrations' => $status,
                'total' => count($status),
                'executed' => count(array_filter($status, fn($m) => $m['status'] === 'executed')),
                'pending' => count(array_filter($status, fn($m) => $m['status'] === 'pending'))
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
