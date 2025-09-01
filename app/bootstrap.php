<?php
//die('Bootstrap file is working!');
// โหลดไฟล์ตั้งค่า
require_once 'config/config.php';

// โหลด Helpers (ต้องมีบรรทัดนี้!!!)
require_once __DIR__ . '/helpers/session_helper.php';
require_once __DIR__ . '/helpers/FileHelper.php';
require_once __DIR__ . '/models/Setting.php'; // โหลด Setting Model ก่อน
require_once __DIR__ . '/helpers/SettingHelper.php'; // แล้วค่อยโหลด Helper
require_once __DIR__ . '/helpers/NotificationHelper.php';

// โหลด Core Libraries โดยอัตโนมัติ
spl_autoload_register(function ($className) {
    require_once 'core/' . $className . '.php';
});
