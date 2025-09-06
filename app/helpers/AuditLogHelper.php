<?php
class AuditLogHelper {
    public static function logAction($action, $details = '') {
        // สร้าง instance ของ Database โดยตรง
        $db = new Database();

        $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
        $username = isset($_SESSION['user_username']) ? $_SESSION['user_username'] : null;
        $ipAddress = $_SERVER['REMOTE_ADDR'];

        $db->query('
            INSERT INTO audit_logs (user_id, username, action, details, ip_address) 
            VALUES (:user_id, :username, :action, :details, :ip_address)
        ');
        $db->bind(':user_id', $userId);
        $db->bind(':username', $username);
        $db->bind(':action', $action);
        $db->bind(':details', $details);
        $db->bind(':ip_address', $ipAddress);
        
        // ใช้ try-catch เพื่อป้องกันไม่ให้การ log ล้มเหลวไปกระทบการทำงานหลัก
        try {
            $db->execute();
        } catch (Exception $e) {
            // ในระบบจริงควร log error นี้ลงไฟล์แทน
            // error_log('Failed to write to audit log: ' . $e->getMessage());
        }
    }
}