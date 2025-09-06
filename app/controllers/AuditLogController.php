<?php
class AuditLogController extends Controller {
    public function __construct(){
        if(!isLoggedIn() || $_SESSION['user_role'] !== 'admin'){
            header('location: ' . URLROOT . '/dashboard');
            exit();
        }
    }
    public function index(){
        $logModel = $this->model('AuditLog');
        $logs = $logModel->getLogs();
        $data = [
            'title' => 'Audit Log',
            'active_menu' => 'audit_log',
            'logs' => $logs
        ];
        $this->view('audit_log/index', $data);
    }
}