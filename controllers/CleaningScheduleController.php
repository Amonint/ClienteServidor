<?php

require_once __DIR__ . '/../models/CleaningSchedule.php';

class CleaningScheduleController {
    private $cleaningScheduleModel;

    public function __construct() {
        $this->cleaningScheduleModel = new CleaningSchedule();
    }

    public function index() {
        $schedules = $this->cleaningScheduleModel->getAll();
        require_once __DIR__ . '/../views/cleaning_schedules/index.php';
    }

    public function create() {
        $errors = [];
        require_once __DIR__ . '/../views/cleaning_schedules/create.php';
    }

    public function store() {
        $errors = $this->validate($_POST);

        if (empty($errors)) {
            $this->cleaningScheduleModel->create($_POST);
            header('Location: /index.php?controller=cleaning&action=index&success=created');
            exit;
        }

        require_once __DIR__ . '/../views/cleaning_schedules/create.php';
    }

    public function edit() {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            header('Location: /index.php?controller=cleaning&action=index&error=not_found');
            exit;
        }

        $schedule = $this->cleaningScheduleModel->getById($id);

        if (!$schedule) {
            header('Location: /index.php?controller=cleaning&action=index&error=not_found');
            exit;
        }

        $errors = [];
        require_once __DIR__ . '/../views/cleaning_schedules/edit.php';
    }

    public function update() {
        $id = $_POST['id'] ?? null;

        if (!$id) {
            header('Location: /index.php?controller=cleaning&action=index&error=not_found');
            exit;
        }

        $errors = $this->validate($_POST);

        if (empty($errors)) {
            $this->cleaningScheduleModel->update($id, $_POST);
            header('Location: /index.php?controller=cleaning&action=index&success=updated');
            exit;
        }

        $schedule = $this->cleaningScheduleModel->getById($id);
        require_once __DIR__ . '/../views/cleaning_schedules/edit.php';
    }

    public function delete() {
        $id = $_GET['id'] ?? null;

        if ($id) {
            $this->cleaningScheduleModel->delete($id);
            header('Location: /index.php?controller=cleaning&action=index&success=deleted');
        } else {
            header('Location: /index.php?controller=cleaning&action=index&error=delete_failed');
        }
        exit;
    }

    private function validate($data) {
        $errors = [];

        if (empty($data['area'])) {
            $errors['area'] = 'El área es requerida';
        }

        if (empty($data['assigned_staff'])) {
            $errors['assigned_staff'] = 'El responsable es requerido';
        }

        if (empty($data['cleaning_date'])) {
            $errors['cleaning_date'] = 'La fecha es requerida';
        }

        if (empty($data['start_time'])) {
            $errors['start_time'] = 'La hora de inicio es requerida';
        }

        if (empty($data['end_time'])) {
            $errors['end_time'] = 'La hora de fin es requerida';
        }

        if (!empty($data['start_time']) && !empty($data['end_time'])) {
            $start = strtotime('1970-01-01 ' . $data['start_time']);
            $end = strtotime('1970-01-01 ' . $data['end_time']);
            if ($start === false || $end === false) {
                $errors['start_time'] = $errors['start_time'] ?? 'Hora inválida';
            } elseif ($end <= $start) {
                $errors['end_time'] = 'La hora de fin debe ser mayor que la hora de inicio';
            }
        }

        $allowedStatuses = ['scheduled', 'completed', 'cancelled'];
        if (isset($data['status']) && !in_array($data['status'], $allowedStatuses, true)) {
            $errors['status'] = 'Estado inválido';
        }

        return $errors;
    }
}
