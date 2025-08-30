<?php
//die('Bootstrap file is working!');
// โหลดไฟล์ตั้งค่า
require_once 'config/config.php';

// โหลด Helpers (ต้องมีบรรทัดนี้!!!)
require_once __DIR__ . '/helpers/session_helper.php';

// โหลด Core Libraries โดยอัตโนมัติ
spl_autoload_register(function ($className) {
    require_once 'core/' . $className . '.php';
});
