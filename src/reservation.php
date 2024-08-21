<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $guests = $_POST['guests'];

    $conn = new mysqli('localhost', 'username', 'password', 'database');

    if ($conn->connect_error) {
        http_response_code(500);
        echo 'Ошибка подключения к базе данных.';
        exit();
    }

    $stmt = $conn->prepare('INSERT INTO reservations (name, email, phone, date, time, guests) VALUES (?, ?, ?, ?, ?, ?)');
    $stmt->bind_param('sssssi', $name, $email, $phone, $date, $time, $guests);

    if ($stmt->execute()) {
        echo 'Бронирование успешно!';
    } else {
        http_response_code(500);
        echo 'Ошибка при сохранении бронирования.';
    }

    $stmt->close();
    $conn->close();
}
?>
