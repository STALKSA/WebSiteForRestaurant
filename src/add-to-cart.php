<?php
session_start();
require_once __DIR__ . '/../src/database-model.php'; 

$db = new DatabaseModel(); 
$db->use_table('menu'); // Указываем таблицу menu для работы с ней

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = $_POST['product_id'];

    // Получаем товар из базы данных по id
    $dish = $db->find("id = {$productId}");

    if (!empty($dish)) {
        $dish = $dish[0]; // Поскольку find возвращает массив, получаем первый элемент
        
        // Если корзины еще нет в сессии, создаем ее
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Проверяем, есть ли уже этот товар в корзине
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity']++;
        } else {
            // Добавляем товар в корзину
            $_SESSION['cart'][$productId] = [
                'id' => $dish['id'],
                'name' => $dish['name'],
                'price' => $dish['price'],
                'quantity' => 1
            ];
        }

        // Подсчитываем общее количество товаров в корзине
        $totalItems = 0;
        foreach ($_SESSION['cart'] as $item) {
            $totalItems += $item['quantity'];
        }

        // Возвращаем ответ в формате JSON
        echo json_encode([
            'success' => true,
            'cartTotalItems' => $totalItems
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Товар не найден'
        ]);
    }
}
