<?php
class Report {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    // ดึงข้อมูลการจองตามช่วงวันที่
    public function getBookingsByDateRange($startDate, $endDate){
        // เพิ่มเวลา 23:59:59 ให้กับ endDate เพื่อให้รวมข้อมูลของวันนั้นทั้งวัน
        $endDate = $endDate . ' 23:59:59';
        
        // เพิ่ม $ เข้าไปหน้า this->db->query
        $this->db->query('
            SELECT 
                b.id,
                b.subject,
                b.start_time,
                b.end_time,
                b.status,
                b.attendees,
                r.name as room_name,
                CONCAT(u.first_name, " ", u.last_name) AS user_fullname
            FROM bookings as b
            JOIN rooms as r ON b.room_id = r.id
            JOIN users as u ON b.user_id = u.id
            WHERE b.start_time BETWEEN :start_date AND :end_date
            ORDER BY b.start_time ASC
        ');
        
        $this->db->bind(':start_date', $startDate);
        $this->db->bind(':end_date', $endDate);
        
        return $this->db->resultSet();
    }
}