<?php
// เริ่มต้น session โดยอัตโนมัติเมื่อไฟล์นี้ถูกเรียกใช้
session_start();

// Flash message helper
function flash($name = '', $message = '', $type = 'success'){
    if(!empty($name)){
        // การตั้งค่าข้อความ: เก็บข้อความและประเภทไว้ใน Session
        if(!empty($message) && empty($_SESSION['flash_messages'][$name])){
            $_SESSION['flash_messages'][$name] = [
                'message' => $message,
                'type' => $type
            ];
        }
    }
}

// สร้างฟังก์ชันใหม่สำหรับแสดงผลโดยเฉพาะ
function display_flash_messages(){
    if(isset($_SESSION['flash_messages'])){
        foreach($_SESSION['flash_messages'] as $name => $flash){
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: '{$flash['type']}',
                            title: '{$flash['message']}',
                            confirmButtonText: 'ตกลง',
                            confirmButtonColor: '" . setting('sidebar_color', '#DB2777') . "'
                        });
                    });
                  </script>";
        }
        // ล้างข้อความทั้งหมดหลังจากแสดงผลแล้ว
        unset($_SESSION['flash_messages']);
    }
}

// --- ฟังก์ชันสำหรับระบบ Login ---

// ฟังก์ชันสร้าง Session ของผู้ใช้
function createUserSession($user)
{
    $_SESSION['user_id']       = $user->id;
    $_SESSION['user_username'] = $user->username;
    $_SESSION['user_name']     = $user->first_name . ' ' . $user->last_name;
    $_SESSION['user_role']     = $user->role;
}

// ฟังก์ชันออกจากระบบ
function logout()
{
    unset($_SESSION['user_id']);
    unset($_SESSION['user_username']);
    unset($_SESSION['user_name']);
    unset($_SESSION['user_role']);
    session_destroy();
}

// ฟังก์ชันตรวจสอบว่าล็อกอินอยู่หรือไม่
function isLoggedIn()
{
    if (isset($_SESSION['user_id'])) {
        return true;
    } else {
        return false;
    }
}
