<?php

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;


$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();


$servername = $_ENV['DB_HOST'];
$username = $_ENV['DB_USER'];
$password = $_ENV['DB_PASSWORD'];
$dbname = $_ENV['DB_NAME'];
$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Получаем ID последнего добавленного блюда
$lastDishId = $conn->insert_id;
$dishQuery = $conn->prepare("SELECT name, description, price FROM menu WHERE id = ?");
$dishQuery->bind_param("i", $lastDishId);
$dishQuery->execute();
$dishQuery->bind_result($dishName, $dishDescription, $dishPrice);
$dishQuery->fetch();
$dishQuery->close();

// Получаем всех подписчиков
$subscribersQuery = $conn->query("SELECT email FROM subscribers");

if ($subscribersQuery->num_rows > 0) {
    // Подготавливаем письмо
    $subject = "Новое блюдо: $dishName";
    $message = "
        <html>
        <head>
            <title>Новое блюдо в меню</title>
        </head>
        <body>
            <h1>$dishName</h1>
            <p>$dishDescription</p>
            <p>Цена: $dishPrice</p>
        </body>
        </html>
    ";
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: your-email@gmail.com" . "\r\n";

    // Отправляем письмо каждому подписчику
    while ($row = $subscribersQuery->fetch_assoc()) {
        $to = $row['email'];
        mail($to, $subject, $message, $headers);
    }
}

$conn->close();