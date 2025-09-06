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

        // getBookingsByDateRange() จะถูกปรับปรุงใน Controller แทน

    // สรุปข้อมูลตัวเลขสำคัญ
    public function getSummaryStats($startDate, $endDate, $status){
        $sql = "SELECT 
                    COUNT(id) as total_bookings,
                    SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved_bookings,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_bookings,
                    SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected_bookings
                FROM bookings 
                WHERE start_time BETWEEN :start_date AND :end_date";
        
        if($status != 'all'){
            $sql .= " AND status = :status";
        }
        
        $this->db->query($sql);
        $this->db->bind(':start_date', $startDate);
        $this->db->bind(':end_date', $endDate . ' 23:59:59');
        if($status != 'all'){
            $this->db->bind(':status', $status);
        }
        return $this->db->single();
    }

    // ข้อมูลสำหรับกราฟวงกลม: สัดส่วนการใช้ห้อง
    public function getRoomUsage($startDate, $endDate, $status){
        $sql = "SELECT r.name as room_name, COUNT(b.id) as booking_count 
                FROM bookings as b 
                JOIN rooms as r ON b.room_id = r.id
                WHERE b.start_time BETWEEN :start_date AND :end_date";

        if($status != 'all'){
            $sql .= " AND b.status = :status";
        }

        $sql .= " GROUP BY r.name ORDER BY booking_count DESC";

        $this->db->query($sql);
        $this->db->bind(':start_date', $startDate);
        $this->db->bind(':end_date', $endDate . ' 23:59:59');
        if($status != 'all'){
            $this->db->bind(':status', $status);
        }
        return $this->db->resultSet();
    }

    // ข้อมูลสำหรับกราฟเส้น: แนวโน้มการจองรายวัน
    public function getDailyTrend($startDate, $endDate, $status){
        $sql = "SELECT DATE(start_time) as booking_date, COUNT(id) as booking_count
                FROM bookings
                WHERE start_time BETWEEN :start_date AND :end_date";

        if($status != 'all'){
            $sql .= " AND status = :status";
        }

        $sql .= " GROUP BY DATE(start_time) ORDER BY booking_date ASC";
        
        $this->db->query($sql);
        $this->db->bind(':start_date', $startDate);
        $this->db->bind(':end_date', $endDate . ' 23:59:59');
        if($status != 'all'){
            $this->db->bind(':status', $status);
        }
        return $this->db->resultSet();
    }
}