<?php
require_once("../src/database-model.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if ($data) {
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
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}