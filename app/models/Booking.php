<?php
class Booking {
    private $db;

    public function __construct(){
        // สร้าง instance ของคลาส Database ที่เราทำไว้
        $this->db = new Database;
    }

    // เมธอดสำหรับดึงข้อมูลการจองทั้งหมดที่อนุมัติแล้ว
    public function getApprovedBookings(){
        // เราจะ JOIN ทั้ง 5 ตาราง และใช้ GROUP_CONCAT เพื่อรวมชื่ออุปกรณ์ทั้งหมด
        $this->db->query('
            SELECT 
                b.id,
                b.subject AS title,
                b.start_time AS start,
                b.end_time AS end,
                b.department,
                b.phone,
                b.attendees,
                b.note,
                b.status,
                b.room_layout_image,
                r.name AS room_name,
                r.color AS color,
                CONCAT(u.first_name, " ", u.last_name) AS user_full_name,
                GROUP_CONCAT(e.name SEPARATOR ", ") AS equipments_list
            FROM bookings AS b
            JOIN rooms AS r ON b.room_id = r.id
            JOIN users AS u ON b.user_id = u.id
            LEFT JOIN booking_equipments AS be ON b.id = be.booking_id
            LEFT JOIN equipments AS e ON be.equipment_id = e.id
            WHERE b.status = :status
            GROUP BY b.id
        ');
        $this->db->bind(':status', 'approved');
        
        $results = $this->db->resultSet(); // <--- แก้ไขจุดเป็นลูกศร
        return $results;
    }
}