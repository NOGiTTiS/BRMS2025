<?php
class Setting {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    // ดึงการตั้งค่าทั้งหมดออกมาเป็น an associative array
    public function getSettings(){
        $this->db->query('SELECT * FROM settings');
        $results = $this->db->resultSet();
        
        $settings = [];
        foreach($results as $row){
            $settings[$row->setting_name] = $row->setting_value;
        }
        return $settings;
    }

    // อัปเดตการตั้งค่าทีละตัว
    public function updateSetting($name, $value){
        $this->db->query('UPDATE settings SET setting_value = :value WHERE setting_name = :name');
        $this->db->bind(':value', $value);
        $this->db->bind(':name', $name);
        return $this->db->execute();
    }
}