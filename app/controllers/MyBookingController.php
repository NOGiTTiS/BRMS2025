<?php
class MybookingController extends Controller {
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

        // --- เพิ่มโค้ดดีบักตรงนี้ ---
        // echo '<pre>';
        // print_r($data['bookings']);
        // echo '</pre>';
        // die();
        // --- จบส่วนดีบัก ---
        
        $this->view('my_bookings/index', $data);
    }

    // ยกเลิก/ลบ การจองของตัวเอง
    public function delete($id){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // 1. ดึงข้อมูลการจองมาก่อนเพื่อตรวจสอบความเป็นเจ้าของ
            $booking = $this->bookingModel->getBookingById($id);
            
            // 2. ตรวจสอบว่าเป็นเจ้าของการจองนั้นจริงหรือไม่
            if($booking && $booking->user_id == $_SESSION['user_id']){
                // 3. ถ้าใช่, ให้ทำการลบ
                if($this->bookingModel->deleteBooking($id)){
                    // บันทึก Log หลังจากลบสำเร็จ
                    AuditLogHelper::logAction('USER_DELETE_BOOKING', 'User deleted their own booking ID: ' . $id);
                    
                    flash('mybooking_message', 'ยกเลิกการจองสำเร็จ', 'swal-success');
                } else {
                    flash('mybooking_message', 'เกิดข้อผิดพลาดในการยกเลิกการจอง', 'swal-error');
                }
            } else {
                // กรณีพยายามลบการจองของคนอื่น
                flash('mybooking_message', 'คุณไม่มีสิทธิ์ยกเลิกการจองนี้', 'swal-error');
            }
            
            header('location: ' . URLROOT . '/mybooking');
            exit();
        }
        
        // ถ้าเข้าถึงหน้านี้โดยตรง (ไม่ใช่ POST) ให้กลับไปหน้ารายการ
        header('location: ' . URLROOT . '/mybooking');
    }
}