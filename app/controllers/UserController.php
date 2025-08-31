<?php
class UserController extends Controller {
    private $userModel;

    public function __construct(){
        $this->userModel = $this->model('User');
    }

    // เมธอดสำหรับหน้าสมัครสมาชิก
    public function register(){
        // ตรวจสอบว่าเป็น POST request (กดปุ่มส่งฟอร์ม) หรือไม่
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // ประมวลผลฟอร์ม
        $sanitized_post = [];
        foreach($_POST as $key => $value){
            $sanitized_post[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        }

            $data = [
                'first_name' => trim($_POST['first_name']),
                'last_name' => trim($_POST['last_name']),
                'username' => trim($_POST['username']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'first_name_err' => '',
                'last_name_err' => '',
                'username_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];

            // --- Validation ---
            if(empty($data['first_name'])){ $data['first_name_err'] = 'กรุณากรอกชื่อ'; }
            if(empty($data['last_name'])){ $data['last_name_err'] = 'กรุณากรอกนามสกุล'; }
            if(empty($data['username'])){
                $data['username_err'] = 'กรุณากรอกชื่อผู้ใช้งาน';
            } elseif($this->userModel->findUserByUsername($data['username'])){
                $data['username_err'] = 'ชื่อผู้ใช้งานนี้มีผู้ใช้แล้ว';
            }
            if(empty($data['email'])){
                $data['email_err'] = 'กรุณากรอกอีเมล';
            } elseif(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
                $data['email_err'] = 'รูปแบบอีเมลไม่ถูกต้อง';
            } elseif($this->userModel->findUserByEmail($data['email'])){
                $data['email_err'] = 'อีเมลนี้มีผู้ใช้แล้ว';
            }
            if(empty($data['password'])){
                $data['password_err'] = 'กรุณากรอกรหัสผ่าน';
            } elseif(strlen($data['password']) < 6){
                $data['password_err'] = 'รหัสผ่านต้องมีอย่างน้อย 6 ตัวอักษร';
            }
            if(empty($data['confirm_password'])){
                $data['confirm_password_err'] = 'กรุณายืนยันรหัสผ่าน';
            } else {
                if($data['password'] != $data['confirm_password']){
                    $data['confirm_password_err'] = 'รหัสผ่านไม่ตรงกัน';
                }
            }
            
            // ตรวจสอบว่าไม่มี error เลย
            if(empty($data['first_name_err']) && empty($data['last_name_err']) && empty($data['username_err']) && empty($data['email_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])){
                // เข้ารหัสผ่าน (Hash Password)
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                // เรียกใช้ฟังก์ชัน register ใน Model
                if($this->userModel->register($data)){
                    // เปลี่ยนพารามิเตอร์ที่ 3 ให้เป็น 'swal-success'
                    flash('register_success', 'สมัครสมาชิกสำเร็จ!', 'swal-success'); 
                    header('location: ' . URLROOT . '/user/login');
                } else {
                    die('มีบางอย่างผิดพลาด');
                }
            } else {
                // โหลด view พร้อมกับ error กลับไปที่ฟอร์มเดิม
                $this->view('users/register', $data);
            }

        } else {
            // โหลดฟอร์มเปล่าสำหรับกรอกข้อมูลครั้งแรก
            $data = [
                'first_name' => '', 'last_name' => '', 'username' => '', 'email' => '', 'password' => '', 'confirm_password' => '',
                'first_name_err' => '', 'last_name_err' => '', 'username_err' => '', 'email_err' => '', 'password_err' => '', 'confirm_password_err' => ''
            ];
            $this->view('users/register', $data);
        }
    }

    public function login(){
        // ตรวจสอบว่าเป็น POST request หรือไม่
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // กรองข้อมูลด้วย htmlspecialchars เพื่อความปลอดภัย
            $sanitized_post = [];
            foreach($_POST as $key => $value){
                $sanitized_post[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            }

            $data = [
                'username_email' => trim($sanitized_post['username_email']),
                'password' => trim($sanitized_post['password']),
                'username_email_err' => '',
                'password_err' => '',      
            ];

            // ตรวจสอบว่ากรอกข้อมูลครบหรือไม่
            if(empty($data['username_email'])){
                $data['username_email_err'] = 'กรุณากรอกชื่อผู้ใช้หรืออีเมล';
            }
            if(empty($data['password'])){
                $data['password_err'] = 'กรุณากรอกรหัสผ่าน';
            }

            // ตรวจสอบว่าไม่มี error
            if(empty($data['username_email_err']) && empty($data['password_err'])){
                // เรียกใช้ model login
                $loggedInUser = $this->userModel->login($data['username_email'], $data['password']);

                if($loggedInUser){
                    // สร้าง Session
                    createUserSession($loggedInUser);
                    // เปลี่ยนเส้นทางไปหน้า Dashboard โดยตรง
                    header('location: ' . URLROOT . '/dashboard');
                    exit(); // เพิ่ม exit() เพื่อความปลอดภัย
                } else {
                    $data['password_err'] = 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง';
                    $this->view('users/login', $data);
                }
            } else {
                // โหลด view พร้อม error
                $this->view('users/login', $data);
            }
        } else {
            // โหลดฟอร์มเปล่า
            $data = [
                'title' => 'เข้าสู่ระบบ',
                'username_email' => '',
                'password' => '',
                'username_email_err' => '',
                'password_err' => '',
            ];
            $this->view('users/login', $data);
        }
    }
    
    // สร้างเมธอด logout
    public function logout(){
        logout(); // เรียกใช้ helper
        header('location: ' . URLROOT . '/user/login'); // กลับไปหน้า login
    }
}