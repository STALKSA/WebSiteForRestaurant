<?php
// Загрузка переменных окружения
require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__);
$dotenv->load();

// Проверка метода запроса
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $date = trim($_POST['date']);
    $time = trim($_POST['time']);
    $guests = intval($_POST['guests']);

    // Проверка обязательных полей
    if (empty($name) || empty($email) || empty($phone) || empty($date) || empty($time) || $guests <= 0) {
        http_response_code(400);
        echo 'Пожалуйста, заполните все поля корректно.';
        exit();
    }

    // Подключение к базе данных с использованием переменных окружения
    $conn = new mysqli(
        $_ENV['DB_HOST'], 
        $_ENV['DB_USER'], 
        $_ENV['DB_PASSWORD'], 
        $_ENV['DB_NAME'], 
        $_ENV['DB_PORT']
    );

    // Проверка подключения
    if ($conn->connect_error) {
        http_response_code(500);
        echo 'Ошибка подключения к базе данных.';
        exit();
    }

    // Подготовка и выполнение запроса
    $stmt = $conn->prepare('INSERT INTO reservations (name, email, phone, date, time, guests) VALUES (?, ?, ?, ?, ?, ?)');
    $stmt->bind_param('sssssi', $name, $email, $phone, $date, $time, $guests);

    // Выполнение и проверка результата
    if ($stmt->execute()) {
        echo 'Бронирование успешно!';
    } else {
        http_response_code(500);
        echo 'Ошибка при сохранении бронирования.';
    }

    // Закрытие соединения
    $stmt->close();
    $conn->close();
}
?>