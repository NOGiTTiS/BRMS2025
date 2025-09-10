<?php

// --- ตั้งค่าฐานข้อมูล ---
define('DB_HOST', 'localhost');
define('DB_USER', 'root');                   // <-- แก้ไขเป็น username ของคุณ
define('DB_PASS', '');                       // <-- แก้ไขเป็น password ของคุณ
define('DB_NAME', 'db_brms'); // <-- ชื่อฐานข้อมูลตามรูป

//Deployment
// define('DB_HOST', 'localhost');
// define('DB_USER', 'tndbadmin');
// define('DB_PASS', 'hVLcvCI17kc.r6XZ');
// define('DB_NAME', 'db_brms'); 

// --- ตั้งค่า Path และ URL ---
// App Root (เช่น C:\xampp\htdocs\brms\app)
define('APPROOT', dirname(dirname(__FILE__)));


// --- Dynamic URL Root (Proxy Aware) ---
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
    $protocol = 'https://';
} else {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
}
$host = $_SERVER['HTTP_HOST'];
$base_path = rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '/\\'); // ปรับให้ชี้ไปที่ Root ของโปรเจค
define('URLROOT', $protocol . $host . $base_path);
// --- End Dynamic URL Root ---


// Site Name
define('SITENAME', 'BRMS - Booking Room Management System');

// --- App Environment ---
// ตั้งเป็น 'dev' ตอนกำลังพัฒนา, เปลี่ยนเป็น 'prod' ตอนใช้งานจริง
define('APP_ENV', 'dev');
