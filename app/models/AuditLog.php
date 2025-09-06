<?php
class AuditLog {
    private $db;
    public function __construct(){ $this->db = new Database; }
    public function getLogs(){
        $this->db->query('SELECT * FROM audit_logs ORDER BY created_at DESC');
        return $this->db->resultSet();
    }
}