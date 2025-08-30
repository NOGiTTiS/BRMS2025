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
                    flash('register_success', 'สมัครสมาชิกสำเร็จ! กรุณาเข้าสู่ระบบ');
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

    // สร้างเมธอด login ว่างๆ ไว้ก่อน
    public function login(){
        // โค้ดสำหรับหน้า login จะสร้างในขั้นตอนถัดไป
         $data = ['username_email' => '', 'password' => '', 'username_email_err' => '', 'password_err' => '',];
        $this->view('users/login', $data);
    }
}