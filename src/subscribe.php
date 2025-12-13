<?php
require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

// Загрузка .env файла
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$servername = $_ENV['DB_HOST'];
$username = $_ENV['DB_USER'];
$password = $_ENV['DB_PASSWORD'];
$dbname = $_ENV['DB_NAME'];
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка соединения
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);

	if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
		// Проверяем, есть ли email в базе
		$stmt = $conn->prepare("SELECT COUNT(*) FROM subscribers WHERE email = ?");
		if ($stmt === false) {
			die("Prepare failed: " . $conn->error);
		}

		$stmt->bind_param("s", $email);
		$stmt->execute();
		$stmt->bind_result($count);
		$stmt->fetch();
		$stmt->close();

		if ($count > 0) {
			// Email уже существует
			echo "Вы уже подписаны.";
		} else {
			// Добавляем новый email
			$stmt = $conn->prepare("INSERT INTO subscribers (email) VALUES (?)");
			if ($stmt === false) {
				die("Prepare failed: " . $conn->error);
			}

			$stmt->bind_param("s", $email);

			if ($stmt->execute()) {
				echo "success";
			} else {
				echo "Error: " . $stmt->error;
			}

			$stmt->close();
		}
	} else {
		echo "invalid";
	}
}

$conn->close();
