<?php

// --- ตั้งค่าฐานข้อมูล ---
define('DB_HOST', 'localhost');
define('DB_USER', 'root');                   // <-- แก้ไขเป็น username ของคุณ
define('DB_PASS', '');                       // <-- แก้ไขเป็น password ของคุณ
define('DB_NAME', 'db_brms'); // <-- ชื่อฐานข้อมูลตามรูป

// --- ตั้งค่า Path และ URL ---
// App Root (เช่น C:\xampp\htdocs\brms\app)
define('APPROOT', dirname(dirname(__FILE__)));
// URL Root (เช่น http://localhost/brms)
define('URLROOT', 'http://localhost/brms/public'); // <-- แก้ไขให้ตรงกับ URL โปรเจคของคุณ
// Site Name
define('SITENAME', 'BRMS - Booking Room Management System');

// --- App Environment ---
// ตั้งเป็น 'dev' ตอนกำลังพัฒนา, เปลี่ยนเป็น 'prod' ตอนใช้งานจริง
define('APP_ENV', 'dev');
