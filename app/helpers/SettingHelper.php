<?php
// Setting Helper
// ใช้สำหรับดึงค่า Setting มาใช้งานทั่วทั้งเว็บไซต์
function setting($key, $default = null){
    // ใช้ static variable เพื่อให้ดึงข้อมูลจาก DB แค่ครั้งเดียว
    static $settings = null;

    if(is_null($settings)){
        $settingModel = new Setting();
        $settings = $settingModel->getSettings();
    }

    return isset($settings[$key]) ? $settings[$key] : $default;
}