<?php

// โหลดไฟล์ตั้งค่า
require_once 'config/config.php';

// โหลด Core Libraries โดยอัตโนมัติ
spl_autoload_register(function($className){
    require_once 'core/' . $className . '.php';
});

?>