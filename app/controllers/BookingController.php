<?php
class BookingController extends Controller
{
    private $bookingModel;
    private $roomModel;
    private $equipmentModel;

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
            $data['subject_err'] = '';
            $data['room_id_err'] = '';
            $data['date_err'] = '';

            if(empty($data['subject'])){ $data['subject_err'] = 'กรุณากรอกหัวข้อการประชุม'; }
            if(empty($data['room_id'])){ $data['room_id_err'] = 'กรุณาเลือกห้องประชุม'; }

            // --- เริ่มการตรวจสอบวันที่ (สำหรับ User เท่านั้น) ---
            if($_SESSION['user_role'] === 'user'){
                // ตรวจสอบการจองล่วงหน้า
                $advanceDays = (int)setting('booking_advance_days', 1);
                $minBookingDate = date('Y-m-d', strtotime("+$advanceDays days"));
                if($data['start_date'] < $minBookingDate){
                    $data['date_err'] = 'ต้องจองล่วงหน้าอย่างน้อย ' . $advanceDays . ' วัน';
                }

                // ตรวจสอบการจองวันหยุด
                $allowWeekend = setting('allow_weekend_booking', '0');
                if($allowWeekend === '0' && empty($data['date_err'])){ // ตรวจสอบต่อเมื่อยังไม่เจอ error แรก
                    $dayOfWeek = date('w', strtotime($data['start_date'])); // 0=Sun, 6=Sat
                    if($dayOfWeek == 0 || $dayOfWeek == 6){
                        $data['date_err'] = 'ไม่สามารถจองในวันเสาร์-อาทิตย์ได้';
                    }
                }
            }
            // --- จบส่วนที่เพิ่ม ---

            // --- เพิ่มการตรวจสอบการจองซ้อน ---
            if(empty($data['room_id_err']) && !empty($data['start_date']) && !empty($data['start_time']) && !empty($data['end_date']) && !empty($data['end_time'])){
                
                // 1. สร้างค่า DATETIME ที่สมบูรณ์ขึ้นมาก่อน
                $full_start_time = $data['start_date'] . ' ' . $data['start_time'];
                $full_end_time = $data['end_date'] . ' ' . $data['end_time'];

                // 2. ส่งค่าที่สมบูรณ์นี้ไปให้ Model ตรวจสอบ
                if(!$this->bookingModel->isTimeSlotAvailable($data['room_id'], $full_start_time, $full_end_time)){
                    $data['room_id_err'] = 'ช่วงเวลานี้สำหรับห้องที่เลือกไม่ว่างแล้ว กรุณาตรวจสอบปฏิทินและเลือกเวลาใหม่';
                }
            }

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
                // เปลี่ยนการเรียกใช้ Model
                $newBookingId = $this->bookingModel->createBooking($bookingDataToSave);

                if($newBookingId){
                    // --- ส่งแจ้งเตือน Telegram ---
                    $room = $this->roomModel->getRoomById($bookingDataToSave['room_id']);
                    $room_name = $room ? $room->name : 'N/A';
                    
                    // สร้างข้อความโดยใช้ Tag HTML ของ Telegram
                    $message  = "🔔 <b>มีการจองใหม่</b> 🔔\n\n";
                    $message .= "<b>หัวข้อ:</b> " . htmlspecialchars($bookingDataToSave['subject']) . "\n";
                    $message .= "<b>ห้อง:</b> " . htmlspecialchars($room_name) . "\n";
                    $message .= "<b>เวลา:</b> " . date('d/m/Y H:i', strtotime($bookingDataToSave['start_time'])) . "\n";
                    $message .= "<b>โดย:</b> " . htmlspecialchars($_SESSION['user_name']) . "\n\n";
                    // ดึง Public URL จาก Setting
                    $publicUrl = setting('public_url', URLROOT); // ใช้ URLROOT เป็นค่าสำรอง
                    $detailsLink = $publicUrl . "/booking/show/" . $newBookingId;

                    $message .= "<a href='" . $detailsLink . "'>คลิกเพื่อดูรายละเอียดและอนุมัติ</a>";
                    
                    // ส่งแจ้งเตือน
                    NotificationHelper::sendTelegram($message);

                    // ใช้ชื่อกลางๆ และส่ง type เป็น success
                    flash('notification', 'ส่งคำขอจองห้องประชุมสำเร็จแล้ว', 'success');
                    
                    header('location: ' . URLROOT . '/mybooking');
                    exit();
                } else {
                    die('Something went wrong');
                }
            } else {
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

    // หน้าแก้ไขการจอง (สำหรับ Admin)
    public function edit($id){
        if(!isLoggedIn() || $_SESSION['user_role'] !== 'admin'){
            header('location: ' . URLROOT . '/dashboard');
            exit();
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // กรองข้อมูลด้วย htmlspecialchars
            $sanitized_post = [];
            foreach($_POST as $key => $value){
                if (!is_array($value)) {
                    $sanitized_post[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
                } else {
                    $sanitized_post[$key] = $value;
                }
            }
            // จัดการข้อมูลไฟล์ (ถ้ามีการอัปโหลดใหม่)
            $newImageName = null;
            if (isset($_FILES['room_layout_image']) && $_FILES['room_layout_image']['error'] === UPLOAD_ERR_OK) {
                $newImageName = FileHelper::upload($_FILES['room_layout_image']);
            }

            $data = [
                'id' => $id,
                'room_id' => trim($sanitized_post['room_id']),
                'subject' => trim($sanitized_post['subject']),
                'department' => trim($sanitized_post['department']),
                'phone' => trim($sanitized_post['phone']),
                'attendees' => trim($sanitized_post['attendees']),
                'start_time' => trim($sanitized_post['start_date']) . ' ' . trim($sanitized_post['start_time']),
                'end_time' => trim($sanitized_post['end_date']) . ' ' . trim($sanitized_post['end_time']),
                'note' => trim($sanitized_post['note']),
                'equipments' => isset($sanitized_post['equipments']) ? $sanitized_post['equipments'] : [],
                'room_layout_image' => $newImageName, // ชื่อไฟล์ใหม่ หรือ null
                'existing_layout_image' => trim($sanitized_post['existing_layout_image']), // ชื่อไฟล์เดิม
            ];
            
            // ถ้าไม่มีการอัปโหลดไฟล์ใหม่ ให้ใช้ไฟล์เดิม
            if(is_null($data['room_layout_image'])){
                $data['room_layout_image'] = $data['existing_layout_image'];
            }

            // Validation (ควรเพิ่มให้ครบถ้วน)
            if(empty($data['subject'])){
                // ควรจะ redirect กลับไปพร้อม error message
                die('Subject is required.');
            }

            if($this->bookingModel->updateBooking($data)){
                flash('booking_manage_message', 'อัปเดตการจองสำเร็จ', 'swal-success');
                header('location: ' . URLROOT . '/booking/show/' . $id);
            } else {
                die('Something went wrong during update');
            }

        } else {
            $booking = $this->bookingModel->getBookingById($id);
            // ดึง ID ของอุปกรณ์ที่ถูกเลือกไว้แล้ว
            $selectedEquipments = $this->bookingModel->getSelectedEquipments($id);

            $data = [
                'title' => 'แก้ไขการจอง',
                'active_menu' => 'manage_bookings',
                'booking' => $booking,
                'rooms' => $this->roomModel->getRooms(),
                'all_equipments' => $this->equipmentModel->getEquipments(),
                'selected_equipments' => $selectedEquipments
            ];
            $this->view('bookings/edit', $data);
        }
    }

    // ลบการจอง (สำหรับ Admin)
    public function delete($id){
        if(!isLoggedIn() || $_SESSION['user_role'] !== 'admin'){
            header('location: ' . URLROOT . '/dashboard');
            exit();
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // ควรมีการตรวจสอบเพิ่มเติมว่าการจองนี้มีอยู่จริงหรือไม่
            if($this->bookingModel->deleteBooking($id)){
                flash('booking_manage_message', 'ลบการจองสำเร็จ', 'swal-success');
                header('location: ' . URLROOT . '/booking');
            } else {
                die('Something went wrong during deletion');
            }
        } else {
            header('location: ' . URLROOT . '/booking');
        }
    }
}
