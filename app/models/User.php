<?php
class User {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    // ฟังก์ชันสำหรับลงทะเบียนผู้ใช้ใหม่
    public function register($data){
        $this->db->query('INSERT INTO users (username, email, password, first_name, last_name) VALUES(:username, :email, :password, :first_name, :last_name)');
        // Bind values
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':first_name', $data['first_name']);
        $this->db->bind(':last_name', $data['last_name']);

        // Execute
        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }

    // ฟังก์ชันสำหรับค้นหาผู้ใช้ด้วย email เพื่อป้องกันการสมัครซ้ำ
    public function findUserByEmail($email){
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);

        $this->db->single();

        // ตรวจสอบว่ามีแถวข้อมูลหรือไม่
        if($this->db->rowCount() > 0){
            return true;
        } else {
            return false;
        }
    }
    
    // ฟังก์ชันสำหรับค้นหาผู้ใช้ด้วย username เพื่อป้องกันการสมัครซ้ำ
    public function findUserByUsername($username){
        $this->db->query('SELECT * FROM users WHERE username = :username');
        $this->db->bind(':username', $username);

        $this->db->single();

        // ตรวจสอบว่ามีแถวข้อมูลหรือไม่
        if($this->db->rowCount() > 0){
            return true;
        } else {
            return false;
        }
    }

    // ฟังก์ชันสำหรับเข้าสู่ระบบ
    public function login($username_email, $password){
        // อนุญาตให้ผู้ใช้กรอกได้ทั้ง username หรือ email
        $this->db->query('SELECT * FROM users WHERE username = :username_email OR email = :username_email');
        $this->db->bind(':username_email', $username_email);

        $row = $this->db->single();

        if($row){
            $hashed_password = $row->password;
            // ตรวจสอบรหัสผ่านที่กรอกกับรหัสผ่านที่เข้ารหัสไว้ในฐานข้อมูล
            if(password_verify($password, $hashed_password)){
                return $row; // คืนค่าข้อมูลผู้ใช้ทั้งหมดถ้าถูกต้อง
            } else {
                return false; // รหัสผ่านผิด
            }
        } else {
            return false; // ไม่พบผู้ใช้
        }
    }

    // ดึงข้อมูลผู้ใช้ทั้งหมด
    public function getUsers(){
        $this->db->query('SELECT id, username, first_name, last_name, email, role, created_at FROM users ORDER BY created_at DESC');
        return $this->db->resultSet();
    }

    // ดึงข้อมูลผู้ใช้คนเดียวด้วย ID
    public function getUserById($id){
        $this->db->query('SELECT id, username, first_name, last_name, email, role FROM users WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // อัปเดตข้อมูลผู้ใช้ (โดย Admin)
    public function updateUser($data){
        // ตรวจสอบว่ามีการส่งรหัสผ่านใหม่มาหรือไม่
        if(!empty($data['password'])){
            $this->db->query('UPDATE users SET first_name = :first_name, last_name = :last_name, email = :email, role = :role, password = :password WHERE id = :id');
            $this->db->bind(':password', $data['password']);
        } else {
            $this->db->query('UPDATE users SET first_name = :first_name, last_name = :last_name, email = :email, role = :role WHERE id = :id');
        }

        $this->db->bind(':id', $data['id']);
        $this->db->bind(':first_name', $data['first_name']);
        $this->db->bind(':last_name', $data['last_name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':role', $data['role']);

        return $this->db->execute();
    }

    // ลบผู้ใช้ (ต้องระวังเรื่อง Foreign Key Constraints กับตาราง bookings)
    public function deleteUser($id){
        // หมายเหตุ: หากผู้ใช้คนนี้เคยมีการจอง จะไม่สามารถลบได้โดยตรงเพราะติด Foreign Key
        // ในระบบจริงอาจจะต้องเปลี่ยนเป็นการ "ปิดการใช้งาน" บัญชีแทนการลบ
        // แต่ในที่นี้เราจะใช้การลบแบบตรงไปตรงมา
        $this->db->query('DELETE FROM users WHERE id = :id');
        $this->db->bind(':id', $id);

        try {
            return $this->db->execute();
        } catch (PDOException $e) {
            // ถ้าเกิด Error (เช่น ติด Foreign Key) ให้คืนค่า false
            return false;
        }
    }
}