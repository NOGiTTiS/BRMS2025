<?php
class Dashboard {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    public function getTotalBookings(){
        $this->db->query('SELECT COUNT(*) as count FROM bookings');
        $row = $this->db->single();
        return $row->count;
    }

    public function getPendingBookings(){
        $this->db->query("SELECT COUNT(*) as count FROM bookings WHERE status = 'pending'");
        $row = $this->db->single();
        return $row->count;
    }
    
    public function getTotalRooms(){
        $this->db->query('SELECT COUNT(*) as count FROM rooms');
        $row = $this->db->single();
        return $row->count;
    }

    public function getTotalUsers(){
        $this->db->query('SELECT COUNT(*) as count FROM users');
        $row = $this->db->single();
        return $row->count;
    }

    // ฟังก์ชันสำหรับดึงข้อมูลการจองรายเดือนเพื่อใช้ในกราฟ
    public function getMonthlyBookings(){
        $this->db->query("
            SELECT 
                -- ใช้ MAX() หรือ MIN() เพื่อเลือกค่าชื่อเดือนมา 1 ค่าจากกลุ่มนั้นๆ
                MAX(MONTHNAME(start_time)) as month, 
                COUNT(id) as total 
            FROM bookings 
            WHERE YEAR(start_time) = YEAR(CURDATE())
            -- GROUP BY ทั้งตัวเลขเดือน และ ชื่อเดือน
            GROUP BY MONTH(start_time), MONTHNAME(start_time)
            -- ORDER BY ตัวเลขเดือน เพื่อให้เรียงลำดับถูกต้อง
            ORDER BY MONTH(start_time)
                    ");
        return $this->db->resultSet();
    }
}