<?php

require_once __DIR__ . '/../config/database.php';

class CleaningSchedule {
    private $db;

    public function __construct() {
        $database = Database::getInstance();
        $this->db = $database->getConnection();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM cleaning_schedules ORDER BY cleaning_date DESC, start_time ASC, created_at DESC");
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM cleaning_schedules WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $sql = "INSERT INTO cleaning_schedules (area, assigned_staff, cleaning_date, start_time, end_time, status, notes)
                VALUES (:area, :assigned_staff, :cleaning_date, :start_time, :end_time, :status, :notes)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'area' => $data['area'],
            'assigned_staff' => $data['assigned_staff'],
            'cleaning_date' => $data['cleaning_date'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'status' => $data['status'] ?? 'scheduled',
            'notes' => $data['notes'] ?? null
        ]);

        return $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $sql = "UPDATE cleaning_schedules
                SET area = :area,
                    assigned_staff = :assigned_staff,
                    cleaning_date = :cleaning_date,
                    start_time = :start_time,
                    end_time = :end_time,
                    status = :status,
                    notes = :notes
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'area' => $data['area'],
            'assigned_staff' => $data['assigned_staff'],
            'cleaning_date' => $data['cleaning_date'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'status' => $data['status'] ?? 'scheduled',
            'notes' => $data['notes'] ?? null
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM cleaning_schedules WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
