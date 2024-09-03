<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

error_log('Before require_once');
require_once("../src/database-model.php");
error_log('After require_once');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if ($data) {
        try {
            $db = new DatabaseModel();
            $db->use_table('bookings');

            $db->create([
                'name' => $data['name'],
                'phone' => $data['phone'],
                'date' => $data['date'],
                'time' => $data['time'],
                'guests' => $data['guests']
            ]);

            echo json_encode(['status' => 'success']);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
