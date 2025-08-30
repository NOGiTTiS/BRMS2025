<?php
// เริ่มต้น session โดยอัตโนมัติเมื่อไฟล์นี้ถูกเรียกใช้
session_start();

// Flash message helper
// ใช้สำหรับแสดงข้อความแจ้งเตือนแค่ครั้งเดียว เช่น "สมัครสมาชิกสำเร็จ!"
// EXAMPLE - flash('register_success', 'You are now registered');
// DISPLAY IN VIEW - <?php echo flash('register_success');
function flash($name = '', $message = '', $class = 'p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800'){
    if(!empty($name)){
        // การตั้งค่าข้อความ
        if(!empty($message) && empty($_SESSION[$name])){
            if(!empty($_SESSION[$name])){
                unset($_SESSION[$name]);
            }

            if(!empty($_SESSION[$name. '_class'])){
                unset($_SESSION[$name. '_class']);
            }

            $_SESSION[$name] = $message;
            $_SESSION[$name. '_class'] = $class;
        } 
        // การแสดงผลข้อความ
        elseif(empty($message) && !empty($_SESSION[$name])){
            $class = !empty($_SESSION[$name. '_class']) ? $_SESSION[$name. '_class'] : '';
            echo '<div class="'.$class.'" id="msg-flash" role="alert">'.$_SESSION[$name].'</div>';
            unset($_SESSION[$name]);
            unset($_SESSION[$name. '_class']);
        }
    }
}