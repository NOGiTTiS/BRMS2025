<?php
class BookingController extends Controller
{
    private $bookingModel;

    public function __construct()
    {
        $this->bookingModel   = $this->model('Booking');
        $this->roomModel      = $this->model('Room');
        $this->equipmentModel = $this->model('Equipment');
    }

    // หน้าแสดงรายการจองทั้งหมด (สำหรับ Admin)
    public function index(){
        // ป้องกัน: ต้องเป็น Admin เท่านั้น
        if(!isLoggedIn() || $_SESSION['user_role'] !== 'admin'){
            header('location: ' . URLROOT . '/dashboard');
            exit();
        }

        $bookings = $this->bookingModel->getAllBookings();
        $data = [
            'title' => 'จัดการการจองทั้งหมด',
            'active_menu' => 'manage_bookings',
            'bookings' => $bookings
        ];
        $this->view('bookings/index', $data);
    }

    public function show($id){
        // ป้องกัน: ต้องเป็น Admin เท่านั้น
        if(!isLoggedIn() || $_SESSION['user_role'] !== 'admin'){
            header('location: ' . URLROOT . '/dashboard');
            exit();
        }
            $booking = $this->bookingModel->getBookingById($id);
    
        if(!$booking){
            // ถ้าหา booking ไม่เจอ ให้กลับไปหน้ารายการ
            header('location: ' . URLROOT . '/booking');
            exit();
        }

        $data = [
            'title' => 'รายละเอียดการจอง',
            'active_menu' => 'manage_bookings',
            'booking' => $booking
        ];

        $this->view('bookings/show', $data);
    }

    // เมธอดนี้จะทำหน้าที่เป็น API Endpoint
    public function getEvents()
    {
        // ดึงข้อมูลการจองจาก Model
        $bookings = $this->bookingModel->getApprovedBookings();

        // กำหนด header ให้ response เป็น JSON
        header('Content-Type: application/json');

        // แปลง array ของข้อมูลเป็น JSON แล้วส่งออกไป
        echo json_encode($bookings);
    }

    // หน้าฟอร์มสร้างการจองใหม่
    public function create()
    {
        // ป้องกัน: ต้องล็อกอินก่อนถึงจะจองได้
        if (! isLoggedIn()) {
            header('location: ' . URLROOT . '/user/login');
            exit();
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // 1. จัดการไฟล์อัปโหลด
            $uploadedFileName = FileHelper::upload($_FILES['room_layout_image']);
            $layout_err = '';
            if (is_array($uploadedFileName) && isset($uploadedFileName['error'])) {
                $layout_err = $uploadedFileName['error'];
                $uploadedFileName = null;
            }

            // 2. กรองข้อมูล POST
            $sanitized_post = [];
            foreach($_POST as $key => $value){
                if (!is_array($value)) {
                    $sanitized_post[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
                } else {
                    $sanitized_post[$key] = $value;
                }
            }

            // 3. เตรียมข้อมูลสำหรับ View (เผื่อเกิด Error)
            $data = [
                'rooms' => $this->roomModel->getRooms(),
                'all_equipments' => $this->equipmentModel->getEquipments(),
                'room_id' => trim($sanitized_post['room_id']),
                'subject' => trim($sanitized_post['subject']),
                'department' => trim($sanitized_post['department']),
                'phone' => trim($sanitized_post['phone']),
                'attendees' => trim($sanitized_post['attendees']),
                'start_date' => trim($sanitized_post['start_date']),
                'start_time' => trim($sanitized_post['start_time']),
                'end_date' => trim($sanitized_post['end_date']),
                'end_time' => trim($sanitized_post['end_time']),
                'note' => trim($sanitized_post['note']),
                'equipments' => isset($sanitized_post['equipments']) ? $sanitized_post['equipments'] : [],
                'title' => 'จองห้องประชุม',
                'subject_err' => '',
                'room_id_err' => '',
                'layout_err' => $layout_err,
            ];

            // 4. Validation
            if(empty($data['subject'])){ $data['subject_err'] = 'กรุณากรอกหัวข้อการประชุม'; }
            if(empty($data['room_id'])){ $data['room_id_err'] = 'กรุณาเลือกห้องประชุม'; }
            // ... add more validation as needed

            // 5. ตรวจสอบว่าไม่มี error
            if(empty($data['subject_err']) && empty($data['room_id_err']) && empty($data['layout_err'])){
                
                // --- ส่วนที่แก้ไขสำคัญ ---
                // 6. สร้าง Array ใหม่สำหรับส่งให้ Model โดยเฉพาะ
                $bookingDataToSave = [
                    'user_id' => $_SESSION['user_id'],
                    'room_id' => $data['room_id'],
                    'subject' => $data['subject'],
                    'department' => $data['department'],
                    'phone' => $data['phone'],
                    'attendees' => $data['attendees'],
                    'start_time' => $data['start_date'] . ' ' . $data['start_time'],
                    'end_time' => $data['end_date'] . ' ' . $data['end_time'],
                    'note' => $data['note'],
                    'equipments' => $data['equipments'],
                    'room_layout_image' => $uploadedFileName
                ];

                // 7. ส่งแค่ข้อมูลที่จำเป็นไปให้ Model
                if($this->bookingModel->createBooking($bookingDataToSave)){
                    flash('booking_message', 'ส่งคำขอจองห้องประชุมสำเร็จแล้ว กรุณารอการอนุมัติ', 'swal-success');
                    header('location: ' . URLROOT);
                    exit();
                } else {
                    die('Something went wrong');
                }
            } else {
                // ถ้ามี error, โหลด View กลับไปพร้อมข้อมูลทั้งหมด
                $this->view('bookings/create', $data);
            }
        } else {
            // โหลดฟอร์มเปล่าพร้อมข้อมูลที่จำเป็น
            $rooms      = $this->roomModel->getRooms();
            $equipments = $this->equipmentModel->getEquipments();

            $data = [
                'title'          => 'จองห้องประชุม',
                'active_menu'    => 'booking',
                'rooms'          => $rooms,
                'all_equipments' => $equipments,
                // ... initial empty fields
                'room_id'        => '', 'subject'     => '', 'department' => '', 'phone'    => '', 'attendees' => '',
                'start_date'     => '', 'start_time'  => '', 'end_date'   => '', 'end_time' => '', 'note'      => '',
                'equipments'     => [],
                'subject_err'    => '', 'room_id_err' => '',
                // ...
            ];
            $this->view('bookings/create', $data);
        }
    }

    // อนุมัติการจอง (สำหรับ Admin)
    public function approve($id){
        if(!isLoggedIn() || $_SESSION['user_role'] !== 'admin'){
            header('location: ' . URLROOT . '/dashboard');
            exit();
        }
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            if($this->bookingModel->approveBooking($id, $_SESSION['user_id'])){
                flash('booking_manage_message', 'อนุมัติการจองสำเร็จ', 'swal-success');
                header('location: ' . URLROOT . '/booking');
            } else {
                die('Something went wrong');
            }
        } else {
            header('location: ' . URLROOT . '/booking');
        }
    }

    // ปฏิเสธการจอง (สำหรับ Admin)
    public function reject($id){
        if(!isLoggedIn() || $_SESSION['user_role'] !== 'admin'){
            header('location: ' . URLROOT . '/dashboard');
            exit();
        }
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            if($this->bookingModel->rejectBooking($id, $_SESSION['user_id'])){
                flash('booking_manage_message', 'ปฏิเสธการจองเรียบร้อย', 'swal-success');
                header('location: ' . URLROOT . '/booking');
            } else {
                die('Something went wrong');
            }
        } else {
            header('location: ' . URLROOT . '/booking');
        }
    }
}
