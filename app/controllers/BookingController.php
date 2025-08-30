<?php
class BookingController extends Controller {
    private $bookingModel;

    public function __construct(){
        $this->bookingModel = $this->model('Booking');
    }

    // เมธอดนี้จะทำหน้าที่เป็น API Endpoint
    public function getEvents(){
        // ดึงข้อมูลการจองจาก Model
        $bookings = $this->bookingModel->getApprovedBookings();
        
        // กำหนด header ให้ response เป็น JSON
        header('Content-Type: application/json');
        
        // แปลง array ของข้อมูลเป็น JSON แล้วส่งออกไป
        echo json_encode($bookings);
    }
}