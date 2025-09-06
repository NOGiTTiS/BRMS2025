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

    // สร้างการจองใหม่
    public function createBooking($data){
        $this->db->beginTransaction();

        try {
            // 1. เพิ่มข้อมูลลงในตาราง bookings
            $this->db->query('
                INSERT INTO bookings (user_id, room_id, subject, department, phone, attendees, start_time, end_time, note, status, room_layout_image) 
                VALUES (:user_id, :room_id, :subject, :department, :phone, :attendees, :start_time, :end_time, :note, :status, :room_layout_image)
            ');
            $this->db->bind(':user_id', $data['user_id']);
            $this->db->bind(':room_id', $data['room_id']);
            $this->db->bind(':subject', $data['subject']);
            $this->db->bind(':department', $data['department']);
            $this->db->bind(':phone', $data['phone']);
            $this->db->bind(':attendees', $data['attendees']);
            $this->db->bind(':start_time', $data['start_time']);
            $this->db->bind(':end_time', $data['end_time']);
            $this->db->bind(':note', $data['note']);
            // ใช้ค่าจาก setting helper
            $this->db->bind(':status', setting('default_booking_status', 'pending'));
            $this->db->bind(':room_layout_image', $data['room_layout_image']);
            
            $this->db->execute();
            
            // 2. ดึง ID ของการจองที่เพิ่งสร้าง
            $bookingId = $this->db->lastInsertId();

            // 3. เพิ่มข้อมูลอุปกรณ์ (ถ้ามี)
            if(!empty($data['equipments'])){
                $this->db->query('INSERT INTO booking_equipments (booking_id, equipment_id) VALUES (:booking_id, :equipment_id)');
                
                foreach($data['equipments'] as $equipmentId){
                    $this->db->bind(':booking_id', $bookingId);
                    $this->db->bind(':equipment_id', $equipmentId);
                    $this->db->execute();
                }
            }
            
            $this->db->commit();
            //return true;
            return $bookingId; 

        } catch (Exception $e) {
            $this->db->rollBack();
            // แสดง error เพื่อดีบัก (เมื่อเสร็จแล้วให้เปลี่ยนเป็น return false;)
            die('Database Error: ' . $e->getMessage()); 
        }
    }

    // ดึงข้อมูลการจองทั้งหมดสำหรับหน้า Admin
    public function getAllBookings(){
        $this->db->query('
            SELECT 
                b.*, 
                r.name as room_name,
                u.username as user_username,
                CONCAT(u.first_name, " ", u.last_name) AS user_fullname
            FROM bookings as b
            JOIN rooms as r ON b.room_id = r.id
            JOIN users as u ON b.user_id = u.id
            ORDER BY b.created_at DESC
        ');
        return $this->db->resultSet();
    }

    // อนุมัติการจอง
    public function approveBooking($booking_id, $admin_id){
        $this->db->query('UPDATE bookings SET status = :status, admin_id = :admin_id WHERE id = :id');
        $this->db->bind(':status', 'approved');
        $this->db->bind(':admin_id', $admin_id);
        $this->db->bind(':id', $booking_id);
        return $this->db->execute();
    }

    // ปฏิเสธการจอง
    public function rejectBooking($booking_id, $admin_id){
        $this->db->query('UPDATE bookings SET status = :status, admin_id = :admin_id WHERE id = :id');
        $this->db->bind(':status', 'rejected');
        $this->db->bind(':admin_id', $admin_id);
        $this->db->bind(':id', $booking_id);
        return $this->db->execute();
    }

    // ดึงข้อมูลการจองแบบละเอียด 1 รายการด้วย ID
    public function getBookingById($id){
        $this->db->query('
            SELECT 
                b.*,
                r.name AS room_name,
                CONCAT(u.first_name, " ", u.last_name) AS user_full_name,
                GROUP_CONCAT(e.name SEPARATOR ", ") AS equipments_list
            FROM bookings AS b
            JOIN rooms AS r ON b.room_id = r.id
            JOIN users AS u ON b.user_id = u.id
            LEFT JOIN booking_equipments AS be ON b.id = be.booking_id
            LEFT JOIN equipments AS e ON be.equipment_id = e.id
            WHERE b.id = :id
            GROUP BY b.id
        ');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // ดึงข้อมูลการจองทั้งหมดของผู้ใช้คนเดียว
    public function getBookingsByUserId($user_id){
        $this->db->query('
            SELECT 
                b.*, 
                r.name as room_name
            FROM bookings as b
            JOIN rooms as r ON b.room_id = r.id
            WHERE b.user_id = :user_id
            ORDER BY b.created_at DESC
        ');
        $this->db->bind(':user_id', $user_id);
        return $this->db->resultSet();
    }

    /**
     * สร้างการจองแบบต่อเนื่อง (Recurring)
     * @return array ['success' => bool, 'message' => string]
     */
    public function createRecurringBooking($data){
        $this->db->beginTransaction();

        try {
            // 1. INSERT ข้อมูลแม่ลงในตาราง booking_series
            $this->db->query('
                INSERT INTO booking_series (user_id, room_id, subject, department, phone, attendees, note, recurrence_pattern, recurrence_end_date)
                VALUES (:user_id, :room_id, :subject, :department, :phone, :attendees, :note, :recurrence_pattern, :recurrence_end_date)
            ');
            $this->db->bind(':user_id', $data['user_id']);
            $this->db->bind(':room_id', $data['room_id']);
            $this->db->bind(':subject', $data['subject']);
            $this->db->bind(':department', $data['department']);
            $this->db->bind(':phone', $data['phone']);
            $this->db->bind(':attendees', $data['attendees']);
            $this->db->bind(':note', $data['note']);
            $this->db->bind(':recurrence_pattern', $data['recurrence_pattern']);
            $this->db->bind(':recurrence_end_date', $data['recurrence_end_date']);
            $this->db->execute();
            
            $seriesId = $this->db->lastInsertId();

            // 2. เตรียมข้อมูลสำหรับสร้างรายการจองย่อย
            $startDate = new DateTime();
            $endDate = new DateTime($data['recurrence_end_date']);
            $endDate->modify('+1 day'); // เพิ่ม 1 วันเพื่อให้รวมวันสุดท้ายเข้าไปใน loop

            $interval = new DateInterval('P1W'); // P1W = Period 1 Week
            $dateRange = new DatePeriod($startDate, $interval, $endDate);
            
            $defaultStatus = setting('default_booking_status', 'pending');

            // 3. วนลูปเพื่อสร้างรายการจองย่อย
            foreach($dateRange as $date){
                $bookingStartTimeStr = $date->format('Y-m-d') . ' ' . $data['recurring_start_time'];
                $bookingEndTimeStr = $date->format('Y-m-d') . ' ' . $data['recurring_end_time'];

                // 3.1 ตรวจสอบการจองซ้อนสำหรับแต่ละวัน
                if(!$this->isTimeSlotAvailable($data['room_id'], $bookingStartTimeStr, $bookingEndTimeStr)){
                    $this->db->rollBack();
                    return [
                        'success' => false,
                        'message' => 'ไม่สามารถสร้างการจองได้ เนื่องจากวันที่ ' . $date->format('d/m/Y') . ' ไม่ว่าง'
                    ];
                }

                // 3.2 INSERT รายการจองย่อย
                $this->db->query('
                    INSERT INTO bookings (series_id, user_id, room_id, subject, department, phone, attendees, start_time, end_time, note, status) 
                    VALUES (:series_id, :user_id, :room_id, :subject, :department, :phone, :attendees, :start_time, :end_time, :note, :status)
                ');
                $this->db->bind(':series_id', $seriesId);
                $this->db->bind(':user_id', $data['user_id']);
                $this->db->bind(':room_id', $data['room_id']);
                $this->db->bind(':subject', $data['subject']);
                $this->db->bind(':department', $data['department']);
                $this->db->bind(':phone', $data['phone']);
                $this->db->bind(':attendees', $data['attendees']);
                $this->db->bind(':start_time', $bookingStartTimeStr);
                $this->db->bind(':end_time', $bookingEndTimeStr);
                $this->db->bind(':note', $data['note']);
                $this->db->bind(':status', $defaultStatus);
                $this->db->execute();
            }

            $this->db->commit();
            return ['success' => true, 'message' => 'สร้างการจองต่อเนื่องสำเร็จ'];

        } catch (Exception $e) {
            $this->db->rollBack();
            return ['success' => false, 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()];
        }
    }

    // ตรวจสอบว่าช่วงเวลาที่ต้องการจองนั้นว่างหรือไม่
    public function isTimeSlotAvailable($room_id, $start_time, $end_time, $exclude_booking_id = null){
        // Logic: ค้นหาการจองที่ "อนุมัติแล้ว" ของห้องนี้
        // ซึ่งช่วงเวลาคาบเกี่ยวกับช่วงเวลาใหม่ที่ต้องการจอง
        // โดยที่ (เวลาเริ่มใหม่ < เวลาจบเก่า) AND (เวลาจบใหม่ > เวลาเริ่มเก่า)
        
        $sql = '
            SELECT id FROM bookings
            WHERE room_id = :room_id
            AND status = :status
            AND :start_time < end_time
            AND :end_time > start_time
        ';
        
        // ถ้ามีการแก้ไขการจอง, ให้ยกเว้น ID ของตัวเองออกจากการตรวจสอบ
        if ($exclude_booking_id) {
            $sql .= ' AND id != :exclude_booking_id';
        }

        $this->db->query($sql);
        $this->db->bind(':room_id', $room_id);
        $this->db->bind(':status', 'approved');
        $this->db->bind(':start_time', $start_time);
        $this->db->bind(':end_time', $end_time);

        if ($exclude_booking_id) {
            $this->db->bind(':exclude_booking_id', $exclude_booking_id);
        }

        $this->db->execute();

        // ถ้าผลลัพธ์มีจำนวนแถว > 0 แสดงว่ามีคนจองแล้ว (ไม่ว่าง)
        // ถ้าเป็น 0 แสดงว่าว่าง
        return $this->db->rowCount() === 0;
    }

    public function updateBooking($data){
        $this->db->beginTransaction();
        try {
            $this->db->query('
                UPDATE bookings SET room_id = :room_id, subject = :subject, department = :department, 
                phone = :phone, attendees = :attendees, start_time = :start_time, end_time = :end_time, note = :note, room_layout_image = :room_layout_image
                WHERE id = :id
            ');
            $this->db->bind(':id', $data['id']);
            $this->db->bind(':room_id', $data['room_id']);
            $this->db->bind(':subject', $data['subject']);
            $this->db->bind(':department', $data['department']);
            $this->db->bind(':phone', $data['phone']);
            $this->db->bind(':attendees', $data['attendees']);
            $this->db->bind(':start_time', $data['start_time']);
            $this->db->bind(':end_time', $data['end_time']);
            $this->db->bind(':note', $data['note']);
            $this->db->bind(':room_layout_image', $data['room_layout_image']);
            $this->db->execute();
            
            // ลบอุปกรณ์เก่าทั้งหมดของการจองนี้
            $this->db->query('DELETE FROM booking_equipments WHERE booking_id = :booking_id');
            $this->db->bind(':booking_id', $data['id']);
            $this->db->execute();

            // เพิ่มอุปกรณ์ที่เลือกใหม่
            if(!empty($data['equipments'])){
                $this->db->query('INSERT INTO booking_equipments (booking_id, equipment_id) VALUES (:booking_id, :equipment_id)');
                foreach($data['equipments'] as $equipmentId){
                    $this->db->bind(':booking_id', $data['id']);
                    $this->db->bind(':equipment_id', $equipmentId);
                    $this->db->execute();
                }
            }
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            die('DB Error: ' . $e->getMessage()); // เปิดไว้ดีบัก
            // return false;
        }
    }

    public function deleteBooking($id){
        $this->db->beginTransaction();
        try {
            // ลบจากตารางกลางก่อน
            $this->db->query('DELETE FROM booking_equipments WHERE booking_id = :id');
            $this->db->bind(':id', $id);
            $this->db->execute();
            
            // แล้วค่อยลบจากตารางหลัก
            $this->db->query('DELETE FROM bookings WHERE id = :id');
            $this->db->bind(':id', $id);
            $this->db->execute();

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function getSelectedEquipments($booking_id){
        $this->db->query('SELECT equipment_id FROM booking_equipments WHERE booking_id = :booking_id');
        $this->db->bind(':booking_id', $booking_id);
        $results = $this->db->resultSet();
        // แปลง array of objects เป็น array of IDs
        return array_map(function($row){ return $row->equipment_id; }, $results);
    }
}