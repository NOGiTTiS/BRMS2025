<?php
class PageController extends Controller {

    // เมธอดสำหรับหน้าแรกสุด (Landing Page)
    public function index(){
        // ถ้าผู้ใช้ล็อกอินอยู่แล้ว ให้พาไปที่ Dashboard
        if(isLoggedIn()){
            header('location: ' . URLROOT . '/dashboard');
            exit();
        }
        
        // ถ้ายังไม่ล็อกอิน ให้แสดงหน้าปฏิทินสาธารณะ
        $data = [
            'title' => 'ปฏิทินการจองห้องประชุม',
            'active_menu' => 'calendar'
        ];

        $this->view('pages/index', $data);
    }

    // เมธอดใหม่สำหรับแสดงหน้าปฏิทินของผู้ใช้ที่ล็อกอินแล้ว
    public function calendar(){
        // ป้องกัน: ถ้ายังไม่ได้ล็อกอิน ให้เด้งไปหน้า login
        if(!isLoggedIn()){
            header('location: ' . URLROOT . '/user/login');
            exit();
        }

        // แสดงหน้าปฏิทินตามปกติ
        $data = [
            'title' => 'ปฏิทินการจองห้องประชุม',
            'active_menu' => 'calendar'
        ];

        $this->view('pages/index', $data);
    }
}