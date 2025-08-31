<?php
class MyBookingController extends Controller {
    private $bookingModel;

    public function __construct(){
        // ป้องกัน: ถ้ายังไม่ได้ล็อกอิน ให้เด้งออก
        if(!isLoggedIn()){
            header('location: ' . URLROOT . '/user/login');
            exit();
        }
        
        $this->bookingModel = $this->model('Booking');
    }

    // หน้าแสดงรายการจองของฉัน
    public function index(){
        // ดึงข้อมูลการจองทั้งหมดที่เป็นของผู้ใช้ที่กำลังล็อกอินอยู่
        $bookings = $this->bookingModel->getBookingsByUserId($_SESSION['user_id']);
        
        $data = [
            'title' => 'ประวัติการจองของฉัน',
            'active_menu' => 'my_bookings',
            'bookings' => $bookings
        ];
        
        $this->view('my_bookings/index', $data);
    }
}