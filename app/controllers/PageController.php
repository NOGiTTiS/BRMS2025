<?php
  class PageController extends Controller { // <--- เปลี่ยนชื่อคลาสตรงนี้
    private $bookingModel;

    public function __construct(){
      // ในอนาคตเราจะโหลด Model ที่นี่
      // $this->bookingModel = $this->model('Booking');
    }

    // เมธอดเริ่มต้นที่ Core.php จะเรียกใช้
    public function index(){
      // ดึงข้อมูลการจองทั้งหมด (เราจะทำฟังก์ชันนี้ใน Model ต่อไป)
      // $bookings = $this->bookingModel->getAllBookings();

      // เตรียมข้อมูลที่จะส่งไปให้ View
      $data = [
        'title' => 'ปฏิทินการจองห้องประชุม',
        // 'bookings' => $bookings
      ];

      // โหลด View พร้อมส่งข้อมูลไปด้วย
      $this->view('pages/index', $data);
    }
  }