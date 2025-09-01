<?php
class NotificationHelper {
    public static function sendTelegram($message){
        // 1. ตรวจสอบว่าเปิดใช้งานหรือไม่
        if (setting('telegram_enabled', '0') !== '1') {
            // die('Telegram is disabled in settings.'); // For debugging
            return false;
        }

        // 2. ดึง Token และ Chat ID พร้อมกับ trim() เพื่อตัดช่องว่าง
        $token = trim(setting('telegram_token'));
        $chatId = trim(setting('telegram_chat_id'));

        if (empty($token) || empty($chatId)) {
            // die('Token or Chat ID is empty.'); // For debugging
            return false;
        }

        // 3. เข้ารหัสข้อความ
        $encodedMessage = urlencode($message);
        
        // 4. สร้าง URL ที่สมบูรณ์
        $url = "https://api.telegram.org/bot{$token}/sendMessage?chat_id={$chatId}&text={$encodedMessage}&parse_mode=HTML";

        // --- ส่วนดีบัก: แสดง URL ที่สร้างขึ้นมา ---
        // ให้ยกเลิกคอมเมนต์บรรทัดข้างล่างนี้เพื่อดูว่า URL ที่ PHP สร้างขึ้นมานั้น
        // หน้าตาเหมือนกับ URL ที่คุณทดสอบในเบราว์เซอร์หรือไม่
        // die('Generated URL: ' . $url); 
        // --- จบส่วนดีบัก ---

        // 5. ส่ง Request ด้วย cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5); 
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return $httpcode == 200;
    }
}