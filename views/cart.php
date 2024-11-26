<?php
session_start();

// Используем таблицу "menu"
$db->use_table("menu");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $productId = $input['productId'] ?? null;
    $action = $input['action'] ?? null;

    if ($productId) {
        // Получаем товар из базы данных
        $product = $db->query("SELECT * FROM menu WHERE id = ?", [$productId])->fetch();

        if ($product) {
            // Проверка действия: добавление в корзину или изменение количества
            if ($action === 'add') {
                if (isset($_SESSION['cart'][$productId])) {
                    $_SESSION['cart'][$productId]['quantity'] += 1;
                } else {
                    $_SESSION['cart'][$productId] = [
                        'id' => $product['id'],
                        'name' => $product['name'],
                        'price' => $product['price'],
                        'quantity' => 1
                    ];
                }
                echo json_encode(['success' => true]);
            } elseif ($action === 'update') {
                $newQuantity = $input['quantity'] ?? 1;
                if ($newQuantity > 0) {
                    $_SESSION['cart'][$productId]['quantity'] = $newQuantity;
                    echo json_encode(['success' => true]);
                } else {
                    unset($_SESSION['cart'][$productId]);
                    echo json_encode(['success' => true, 'message' => 'Товар удален из корзины']);
                }
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Товар не найден']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Неверный ID товара']);
    }
}

// Получаем товары в корзине
$cartItems = $_SESSION['cart'] ?? [];

// Считаем общую сумму
$totalAmount = 0;
foreach ($cartItems as $item) {
    $totalAmount += $item['price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Корзина</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<main class="cart-page">
    <h1>Корзина</h1>

    <!-- Список товаров в корзине -->
    <div class="cart-items">
        <?php if (count($cartItems) > 0): ?>
            <?php foreach ($cartItems as $item): ?>
                <div class="cart-item" data-id="<?= $item['id'] ?>">
                    <div class="item-name"><?= htmlspecialchars($item['name']) ?></div>
                    <div class="item-price"><?= number_format($item['price'], 0, ',', ' ') ?> руб/шт</div>

                    <div class="item-quantity">
                        <button class="quantity-btn decrement" data-id="<?= $item['id'] ?>">-</button>
                        <input type="text" class="quantity-input" value="<?= $item['quantity'] ?>" readonly>
                        <button class="quantity-btn increment" data-id="<?= $item['id'] ?>">+</button>
                    </div>

                    <div class="item-total-price"><?= number_format($item['price'] * $item['quantity'], 0, ',', ' ') ?> руб.</div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Корзина пуста.</p>
        <?php endif; ?>
    </div>

    <!-- Общая сумма -->
    <div class="total-section">
        <div class="total-label">К оплате</div>
        <div class="total-price">Общая сумма: <span id="total-amount"><?= number_format($totalAmount, 0, ',', ' ') ?> руб.</span></div>
    </div>

    <!-- Форма оформления заказа -->
    <section class="order-form">
        <h2>Данные заказчика</h2>
        <form action="process_order.php" method="POST">
            <label for="address">Адрес доставки</label>
            <input type="text" id="address" name="address" required placeholder="Адрес доставки">

            <label for="name">Получатель</label>
            <input type="text" id="name" name="name" required placeholder="Имя">
            <input type="email" id="email" name="email" required placeholder="Email">
            <input type="tel" id="phone" name="phone" required placeholder="+7">

            <label>Способ доставки</label>
            <div class="delivery-options">
                <label><input type="radio" name="delivery" value="address" checked> Доставить по указанному адресу</label>
                <label><input type="radio" name="delivery" value="pickup"> Самовывоз</label>
            </div>

            <label for="comments">Комментарий к заказу</label>
            <textarea id="comments" name="comments" placeholder="Комментарий"></textarea>

            <div class="checkbox">
                <label>
                    <input type="checkbox" name="agreement" required>
                    Соглашаюсь с условиями использования и политикой конфиденциальности
                </label>
            </div>

            <button type="submit" class="btn primary-btn">Продолжить</button>
        </form>
    </section>
</main>

<script>
// Изменение количества товаров в корзине
document.querySelectorAll('.quantity-btn').forEach(button => {
    button.addEventListener('click', function () {
        const itemElement = this.closest('.cart-item');
        const itemId = itemElement.getAttribute('data-id');
        const quantityInput = itemElement.querySelector('.quantity-input');
        let quantity = parseInt(quantityInput.value);

        if (this.classList.contains('increment')) {
            quantity++;
        } else if (this.classList.contains('decrement') && quantity > 1) {
            quantity--;
        }

        // Обновляем количество в интерфейсе
        quantityInput.value = quantity;

        // Обновляем общую цену для товара
        const itemPrice = parseFloat(itemElement.querySelector('.item-price').innerText.replace(' руб/шт', '').replace(/\s/g, ''));
        const totalPriceElement = itemElement.querySelector('.item-total-price');
        totalPriceElement.innerText = (itemPrice * quantity).toLocaleString('ru-RU') + ' руб.';

        // Обновляем количество на сервере
        fetch('cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ productId: itemId, action: 'update', quantity })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                recalculateTotal();
            } else {
                alert('Ошибка при обновлении корзины');
            }
        });
    });
});

// Пересчет общей суммы
function recalculateTotal() {
    let total = 0;
    document.querySelectorAll('.item-total-price').forEach(priceElement => {
        total += parseFloat(priceElement.innerText.replace(' руб.', '').replace(/\s/g, ''));
    });
    document.getElementById('total-amount').innerText = total.toLocaleString('ru-RU') + ' руб.';
}
</script>

</body>
</html>
