<?php
// เริ่มต้น session โดยอัตโนมัติเมื่อไฟล์นี้ถูกเรียกใช้
session_start();

// Flash message helper
function flash($name = '', $message = '', $class = 'p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg'){
    if(!empty($name)){
        // การตั้งค่าข้อความ
        if(!empty($message) && empty($_SESSION[$name])){
            if(!empty($_SESSION[$name])){ unset($_SESSION[$name]); }
            if(!empty($_SESSION[$name. '_class'])){ unset($_SESSION[$name. '_class']); }
            $_SESSION[$name] = $message;
            $_SESSION[$name. '_class'] = $class;
        } 
        // การแสดงผลข้อความ
        elseif(empty($message) && !empty($_SESSION[$name])){
            $class = !empty($_SESSION[$name. '_class']) ? $_SESSION[$name. '_class'] : '';

            // ตรวจสอบว่า class ที่ส่งมาขึ้นต้นด้วย 'swal-' หรือไม่
            if (strpos($class, 'swal-') === 0) {
                $type = explode('-', $class)[1]; 
                
                echo "<script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: '{$type}',
                                title: '{$_SESSION[$name]}',
                                text: 'กรุณาเข้าสู่ระบบเพื่อดำเนินการต่อ',
                                confirmButtonText: 'ตกลง',
                                confirmButtonColor: '#a855f7'
                            });
                        });
                      </script>";
            } else {
                // ถ้าไม่ใช่ ให้แสดงผลเป็น HTML แบบเดิม
                echo '<div class="'.$class.'" id="msg-flash" role="alert">'.$_SESSION[$name].'</div>';
            }

            // ล้าง session หลังจากแสดงผลแล้ว
            unset($_SESSION[$name]);
            unset($_SESSION[$name. '_class']);
        }
    }
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