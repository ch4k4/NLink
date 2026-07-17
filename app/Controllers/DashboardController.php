<?php
/**
 * Dashboard Controller
 */

class DashboardController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Main dashboard page
     */
    public function index(): string
    {
        return $this->view('dashboard', [
            'pageTitle' => 'Dashboard'
        ]);
    }
}
