<?php
// เริ่มต้น session โดยอัตโนมัติเมื่อไฟล์นี้ถูกเรียกใช้
session_start();

// Flash message helper
function flash($name = '', $message = '', $type = 'success'){
    if(!empty($name)){
        // การตั้งค่าข้อความ
        if(!empty($message) && empty($_SESSION[$name])){
            $_SESSION[$name] = $message;
            $_SESSION[$name. '_type'] = $type;
        } 
        // การดึงข้อมูล (เราจะไม่ echo ที่นี่แล้ว)
        elseif(empty($message) && !empty($_SESSION[$name])){
            // แค่เตรียมข้อมูลไว้ให้ header.php ดึงไปใช้
            return true; 
        }
    }
    return false;
}

// --- ฟังก์ชันสำหรับระบบ Login ---

// ฟังก์ชันสร้าง Session ของผู้ใช้
function createUserSession($user){
    $_SESSION['user_id'] = $user->id;
    $_SESSION['user_username'] = $user->username;
    $_SESSION['user_name'] = $user->first_name . ' ' . $user->last_name;
    $_SESSION['user_role'] = $user->role;
}

// ฟังก์ชันออกจากระบบ
function logout(){
    unset($_SESSION['user_id']);
    unset($_SESSION['user_username']);
    unset($_SESSION['user_name']);
    unset($_SESSION['user_role']);
    session_destroy();
}

// ฟังก์ชันตรวจสอบว่าล็อกอินอยู่หรือไม่
function isLoggedIn(){
    if(isset($_SESSION['user_id'])){
        return true;
    } else {
        return false;
    }
}