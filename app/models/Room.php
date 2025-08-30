<?php
class Room {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    // ดึงข้อมูลห้องทั้งหมด
    public function getRooms(){
        $this->db->query('SELECT * FROM rooms ORDER BY id ASC');
        return $this->db->resultSet();
    }

    // เพิ่มห้องใหม่
    public function addRoom($data){
        $this->db->query('INSERT INTO rooms (name, capacity, description, color) VALUES (:name, :capacity, :description, :color)');
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':capacity', $data['capacity']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':color', $data['color']);

        return $this->db->execute();
    }
    
    // ดึงข้อมูลห้องเดียวด้วย ID
    public function getRoomById($id){
        $this->db->query('SELECT * FROM rooms WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // อัปเดตข้อมูลห้อง
    public function updateRoom($data){
        $this->db->query('UPDATE rooms SET name = :name, capacity = :capacity, description = :description, color = :color WHERE id = :id');
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':capacity', $data['capacity']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':color', $data['color']);

        return $this->db->execute();
    }

    // ลบห้อง
    public function deleteRoom($id){
        $this->db->query('DELETE FROM rooms WHERE id = :id');
        $this->db->bind(':id', $id);

        return $this->db->execute();
    }
}