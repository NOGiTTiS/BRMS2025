<?php
class Equipment {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    // ดึงข้อมูลอุปกรณ์ทั้งหมด
    public function getEquipments(){
        $this->db->query('SELECT * FROM equipments ORDER BY id ASC');
        return $this->db->resultSet();
    }
}