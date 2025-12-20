<?php
session_start();

$cartItems = $_SESSION['cart'] ?? [];
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

		<!-- Список товаров -->
		<div class="cart-items">
			<?php if (!empty($cartItems)): ?>
				<?php foreach ($cartItems as $item): ?>
					<div class="cart-item" data-id="<?= $item['id'] ?>">
						<div class="item-name"><?= htmlspecialchars($item['name']) ?></div>

						<div class="item-price">
							<?= number_format($item['price'], 0, ',', ' ') ?> руб/шт
						</div>

						<div class="item-quantity">
							<button class="quantity-btn decrement" data-id="<?= $item['id'] ?>">-</button>
							<input type="text" class="quantity-input" value="<?= $item['quantity'] ?>" readonly>
							<button class="quantity-btn increment" data-id="<?= $item['id'] ?>">+</button>
						</div>

						<div class="item-total-price">
							<?= number_format($item['price'] * $item['quantity'], 0, ',', ' ') ?> руб.
						</div>
					</div>
				<?php endforeach; ?>
			<?php else: ?>
				<p>Корзина пуста.</p>
			<?php endif; ?>
		</div>

		<!-- Общая сумма -->
		<div class="total-section">
			<div class="total-label">К оплате</div>
			<div class="total-price">
				Общая сумма: <span id="total-amount"><?= number_format($totalAmount, 0, ',', ' ') ?> руб.</span>
			</div>
		</div>

		<!-- Форма оформления заказа -->
		<?php if (!empty($cartItems)): ?>
			<section class="order-form">
				<h2>Данные заказчика</h2>

				<form action="/restoran/views/process_order.php" method="POST">
					<label for="address">Адрес доставки</label>
					<input type="text" id="address" name="address" required placeholder="Адрес доставки">

					<label for="name">Получатель</label>
					<input type="text" id="name" name="name" required placeholder="Имя">
					<input type="email" id="email" name="email" placeholder="Email">
					<input type="tel" id="phone" name="phone" required placeholder="+7">

					<label>Способ доставки</label>
					<div class="delivery-options">
						<label><input type="radio" name="delivery" value="address" checked> Доставить по адресу</label>
						<label><input type="radio" name="delivery" value="pickup"> Самовывоз</label>
					</div>

					<label for="comments">Комментарий</label>
					<textarea id="comments" name="comments" placeholder="Комментарий"></textarea>

					<div class="checkbox">
						<label>
							<input type="checkbox" name="agreement" required>
							Соглашаюсь с условиями и политикой конфиденциальности
						</label>
					</div>

					<button type="submit" class="btn primary-btn">Оформить заказ</button>
				</form>
			</section>
		<?php endif; ?>
	</main>

	<script>
		// Кнопки + и -
		document.querySelectorAll('.quantity-btn').forEach(button => {
			button.addEventListener('click', function() {

				const itemElement = this.closest('.cart-item');
				const itemId = itemElement.dataset.id;

				const quantityInput = itemElement.querySelector('.quantity-input');
				let quantity = parseInt(quantityInput.value);

				if (this.classList.contains('increment')) {
					quantity++;
				} else if (this.classList.contains('decrement')) {
					if (quantity > 1) {
						quantity--; // уменьшаем
					} else {
						// quantity === 1 => удаляем товар полностью
						removeItem(itemId, itemElement);
						return;
					}
				}

				// Обновляем UI
				quantityInput.value = quantity;

				const price = parseFloat(
					itemElement.querySelector('.item-price')
					.innerText.replace(' руб/шт', '').replace(/\s/g, '')
				);

				const totalPriceEl = itemElement.querySelector('.item-total-price');
				totalPriceEl.innerText = (price * quantity).toLocaleString('ru-RU') + ' руб.';

				// Отправляем запрос на API
				fetch('/restoran/api/cart.php', {
						method: 'POST',
						headers: {
							'Content-Type': 'application/json'
						},
						body: JSON.stringify({
							action: 'update',
							productId: itemId,
							quantity: quantity
						})
					})
					.then(res => res.json())
					.then(data => {
						if (data.success) {
							recalcTotal();
						}
					});
			});
		});


		// ---------------------------
		// УДАЛЕНИЕ ТОВАРА
		// ---------------------------
		function removeItem(itemId, element) {

			fetch('/restoran/api/cart.php', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json'
					},
					body: JSON.stringify({
						action: 'remove',
						productId: itemId
					})
				})
				.then(res => res.json())
				.then(data => {
					if (data.success) {

						// Удаляем товар из DOM
						element.remove();

						// Пересчитываем сумму
						recalcTotal();

						// обновляем счётчик корзины
						document.getElementById('cart-count').innerText = data.cartTotalItems;

						// Если товаров нет — показываем "Корзина пуста"
						const items = document.querySelectorAll('.cart-item');
						if (items.length === 0) {
							document.querySelector('.cart-items').innerHTML = '<p>Корзина пуста.</p>';
							document.getElementById('total-amount').innerText = '0 руб.';
						}
					}
				});
		}


		// ---------------------------
		// Пересчёт общей суммы
		// ---------------------------
		function recalcTotal() {
			let total = 0;

			document.querySelectorAll('.item-total-price').forEach(el => {
				const val = parseFloat(el.innerText.replace(' руб.', '').replace(/\s/g, ''));
				total += val;
			});

			document.getElementById('total-amount').innerText =
				total.toLocaleString('ru-RU') + ' руб.';
		}


		//успешное сообщение после формирования заказа

		document.querySelector('form').addEventListener('submit', function(event) {
			event.preventDefault(); // Останавливаем отправку формы

			// Получаем данные формы
			const formData = new FormData(this);

			// Логирование данных формы для отладки
			console.log(formData);

			fetch('/restoran/views/process_order.php', {
					method: 'POST',
					body: formData
				})
				.then(response => response.json())
				.then(data => {
					if (data.success) {
						alert(data.message); // Показываем сообщение о успешном оформлении
						// Перенаправляем на страницу с благодарностью или главную страницу
						window.location.href = "index.php";
					} else {
						alert(data.message); // Показываем сообщение об ошибке
					}
				})
				.catch(error => {
					console.error('Ошибка:', error);
					alert('Произошла ошибка. Попробуйте снова.');
				});
		});
	</script>

</body>

</html>