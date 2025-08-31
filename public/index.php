<?php
// เรียกไฟล์สำหรับเริ่มต้นระบบ
require_once '../app/bootstrap.php';

// --- Error Handling ---
if (APP_ENV === 'prod') {
    // ซ่อน Error ทั้งหมดในโหมด Production
    error_reporting(0);
    ini_set('display_errors', 0);
    
    // กำหนด custom error handler (ตัวเลือกเสริม)
    set_exception_handler(function($exception) {
        // คุณสามารถสร้างหน้า View สวยๆ สำหรับแสดง Error 500 ได้ที่นี่
        http_response_code(500);
        echo "<h1>เกิดข้อผิดพลาดในระบบ</h1>";
        echo "<p>ขออภัยในความไม่สะดวก กรุณาลองใหม่อีกครั้งในภายหลัง</p>";
        // ในความเป็นจริง ควรมีการ log error ลงไฟล์
        // error_log($exception->getMessage());
    });
} else {
    // แสดง Error ทั้งหมดในโหมด Development
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

// เริ่มการทำงานของ Core Class
$init = new Core();