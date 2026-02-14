<?php
session_start();

require_once __DIR__ . '/../src/database-model.php';

$db = new DatabaseModel();
$db->use_table("orders");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	header("Location: /restoran/views/cart.php");
	exit;
}

// Проверяем корзину
$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
	header("Location: /restoran/views/cart.php");
	exit;
}

// Получаем данные
$userId = $_SESSION['user-id'] ?? null;
$address = trim($_POST['address'] ?? '');
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$deliveryMethod = $_POST['delivery'] ?? '';
$comments = trim($_POST['comments'] ?? '');

if (!$userId || !$address || !$name || !$phone || !$deliveryMethod) {
	header("Location: /restoran/views/cart.php?error=1");
	exit;
}

// Добавляем заказ
$sql = "INSERT INTO orders 
        (user_id, address, name, email, phone, delivery_method, comments)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $db->db->prepare($sql);
$stmt->bind_param("issssss", $userId, $address, $name, $email, $phone, $deliveryMethod, $comments);

if ($stmt->execute()) {

	// 🔥 ОЧИЩАЕМ КОРЗИНУ
	unset($_SESSION['cart']);
	$_SESSION['cart'] = [];

	// Можно обнулить счётчик если используешь
	$_SESSION['cart_total_items'] = 0;

	// Перенаправляем на страницу успеха
	header("Location: /restoran/index.php?action=order-success");
	exit;
} else {

	header("Location: /restoran/views/cart.php?error=db");
	exit;
}
