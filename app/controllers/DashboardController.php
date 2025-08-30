<?php
class DashboardController extends Controller {
    private $dashboardModel;

    public function __construct(){
        // ถ้ายังไม่ได้ล็อกอิน ให้เด้งกลับไปหน้า login ทันที
        if(!isLoggedIn()){
            header('location: ' . URLROOT . '/user/login');
        }
        
        // ถ้าล็อกอินแล้ว ให้โหลด Model
        $this->dashboardModel = $this->model('Dashboard');
    }

    public function index(){
        // ดึงข้อมูลสรุปจาก Model
        $totalBookings = $this->dashboardModel->getTotalBookings();
        $pendingBookings = $this->dashboardModel->getPendingBookings();
        $totalRooms = $this->dashboardModel->getTotalRooms();
        $totalUsers = $this->dashboardModel->getTotalUsers();
        $monthlyBookings = $this->dashboardModel->getMonthlyBookings();

        // เตรียมข้อมูลสำหรับส่งไปให้ View
        $data = [
            'title' => 'ภาพรวมระบบ',
            'totalBookings' => $totalBookings,
            'pendingBookings' => $pendingBookings,
            'totalRooms' => $totalRooms,
            'totalUsers' => $totalUsers,
            'monthlyBookings' => $monthlyBookings
        ];

        $this->view('dashboard/index', $data);
    }
}