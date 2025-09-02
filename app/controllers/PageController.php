<?php
class PageController extends Controller {
    // 1. ประกาศ Property ของคลาสไว้ข้างบน
    private $roomModel;

    public function __construct(){
        // 2. โหลด Model เข้ามาเก็บไว้ใน Property
        $this->roomModel = $this->model('Room');
    }

    // เมธอดสำหรับหน้าแรกสุด (Landing Page)
    public function index(){
        if(isLoggedIn()){
            header('location: ' . URLROOT . '/dashboard');
            exit();
        }
        
        // 3. เรียกใช้ Property เพื่อดึงข้อมูล
        $rooms = $this->roomModel->getRooms();

        $data = [
            'title' => 'ปฏิทินการจองห้องประชุม',
            'active_menu' => 'calendar',
            'rooms' => $rooms // <-- ส่งข้อมูลไปให้ View
        ];

        $this->view('pages/index', $data);
    }

    // เมธอดสำหรับแสดงหน้าปฏิทินของผู้ใช้ที่ล็อกอินแล้ว
    public function calendar(){
        if(!isLoggedIn()){
            header('location: ' . URLROOT . '/user/login');
            exit();
        }
        
        // 3. เรียกใช้ Property เพื่อดึงข้อมูล
        $rooms = $this->roomModel->getRooms();
        
        $data = [
            'title' => 'ปฏิทินการจองห้องประชุม',
            'active_menu' => 'calendar',
            'rooms' => $rooms // <-- ส่งข้อมูลไปให้ View
        ];

        $this->view('pages/index', $data);
    }
}