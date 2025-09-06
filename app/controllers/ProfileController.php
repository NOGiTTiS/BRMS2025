<?php
class ProfileController extends Controller {
    private $userModel;

    public function __construct(){
        if(!isLoggedIn()){
            header('location: ' . URLROOT . '/user/login');
            exit();
        }
        $this->userModel = $this->model('User');
    }

    // หน้าโปรไฟล์หลัก
    public function index(){
        $user = $this->userModel->getUserById($_SESSION['user_id']);
        $data = [
            'title' => 'โปรไฟล์ของฉัน',
            'active_menu' => 'profile',
            'user' => $user
        ];
        $this->view('profile/index', $data);
    }

    // อัปเดตข้อมูลส่วนตัว
    public function update(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $sanitized_post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'id' => $_SESSION['user_id'],
                'first_name' => trim($sanitized_post['first_name']),
                'last_name' => trim($sanitized_post['last_name']),
                'email' => trim($sanitized_post['email']),
                // ... validation errors
            ];

            // (ควรเพิ่ม Validation ที่นี่)

            if($this->userModel->updateProfile($data)){
                // อัปเดตชื่อใน Session ด้วย
                $_SESSION['user_name'] = $data['first_name'] . ' ' . $data['last_name'];
                flash('notification', 'อัปเดตข้อมูลโปรไฟล์สำเร็จ', 'success');
                header('location: ' . URLROOT . '/profile');
            } else {
                die('Something went wrong');
            }
        } else {
            header('location: ' . URLROOT . '/profile');
        }
    }

    // เปลี่ยนรหัสผ่าน
    public function changePassword(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $sanitized_post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'current_password' => trim($sanitized_post['current_password']),
                'new_password' => trim($sanitized_post['new_password']),
                'confirm_password' => trim($sanitized_post['confirm_password']),
                'password_err' => ''
            ];

            // 1. ดึงข้อมูลผู้ใช้ทั้งหมด (รวมรหัสผ่าน)
            $user = $this->userModel->getFullUserById($_SESSION['user_id']);

            // 2. ตรวจสอบรหัสผ่านปัจจุบัน
            if(!password_verify($data['current_password'], $user->password)){
                $data['password_err'] = 'รหัสผ่านปัจจุบันไม่ถูกต้อง';
            }
            
            // 3. ตรวจสอบรหัสผ่านใหม่
            if(strlen($data['new_password']) < 6){
                $data['password_err'] = 'รหัสผ่านใหม่ต้องมีอย่างน้อย 6 ตัวอักษร';
            }
            if($data['new_password'] !== $data['confirm_password']){
                $data['password_err'] = 'รหัสผ่านใหม่และการยืนยันไม่ตรงกัน';
            }

            // 4. ถ้าไม่มี Error
            if(empty($data['password_err'])){
                // 5. เข้ารหัสรหัสผ่านใหม่
                $hashed_password = password_hash($data['new_password'], PASSWORD_DEFAULT);
                
                if($this->userModel->changePassword($_SESSION['user_id'], $hashed_password)){
                    flash('notification', 'เปลี่ยนรหัสผ่านสำเร็จ', 'success');
                    header('location: ' . URLROOT . '/profile');
                } else {
                    die('Something went wrong');
                }
            } else {
                // ถ้ามี Error, ส่งกลับไปพร้อมข้อความ
                flash('notification', $data['password_err'], 'error');
                header('location: ' . URLROOT . '/profile');
            }
        } else {
            header('location: ' . URLROOT . '/profile');
        }
    }
}