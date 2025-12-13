<?php
session_start();
header('Content-Type: application/json');

ini_set('display_errors', 0);

require_once __DIR__ . '/../src/database-model.php';

// -------------------------
// Подключение к базе
// -------------------------
try {
	$db = new DatabaseModel();
	$db->use_table('menu');
} catch (Exception $e) {
	echo json_encode(['success' => false, 'message' => 'Ошибка подключения к БД']);
	exit;
}

// -------------------------
// Инициализация корзины
// -------------------------
if (!isset($_SESSION['cart'])) {
	$_SESSION['cart'] = [];
}

// -------------------------
// Входящие данные
// -------------------------
$raw = file_get_contents('php://input');
$input = json_decode($raw, true);

if (!$input) {
	echo json_encode(['success' => false, 'message' => 'Неверные данные (JSON)']);
	exit;
}

$productId = isset($input['productId']) ? (int)$input['productId'] : 0;
$action    = $input['action'] ?? '';

if ($productId <= 0 || !$action) {
	echo json_encode(['success' => false, 'message' => 'Неверные данные']);
	exit;
}

// -------------------------
// Получение товара
// -------------------------
$sql = "SELECT * FROM menu WHERE id = {$productId}";
$result = $db->query($sql);

if (!$result) {
	echo json_encode(['success' => false, 'message' => 'Ошибка запроса к БД']);
	exit;
}

$product = $result->fetch_assoc();

if (!$product) {
	echo json_encode(['success' => false, 'message' => 'Товар не найден']);
	exit;
}

// -------------------------
// ADD
// -------------------------
if ($action === 'add') {

	if (isset($_SESSION['cart'][$productId])) {
		$_SESSION['cart'][$productId]['quantity'] += 1;
	} else {
		$_SESSION['cart'][$productId] = [
			'id'       => $product['id'],
			'name'     => $product['name'],
			'price'    => (int)$product['price'],
			'quantity' => 1
		];
	}

	echo json_encode([
		'success' => true,
		'cartTotalItems' => array_sum(array_column($_SESSION['cart'], 'quantity'))
	]);
	exit;
}

// -------------------------
// UPDATE
// -------------------------
if ($action === 'update') {

	$quantity = isset($input['quantity']) ? (int)$input['quantity'] : 0;

	if ($quantity > 0) {
		$_SESSION['cart'][$productId]['quantity'] = $quantity;
	} else {
		unset($_SESSION['cart'][$productId]);
	}

	echo json_encode([
		'success' => true,
		'cartTotalItems' => array_sum(array_column($_SESSION['cart'], 'quantity'))
	]);
	exit;
}

// -------------------------
// REMOVE (НОВЫЙ БЛОК)
// -------------------------
if ($action === 'remove') {

	if (isset($_SESSION['cart'][$productId])) {
		unset($_SESSION['cart'][$productId]);
	}

	echo json_encode([
		'success' => true,
		'cartTotalItems' => array_sum(array_column($_SESSION['cart'], 'quantity'))
	]);
	exit;
}

// -------------------------
// Unknown action
// -------------------------
echo json_encode(['success' => false, 'message' => 'Неизвестное действие']);
exit;
