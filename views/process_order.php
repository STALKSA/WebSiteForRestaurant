<?php

session_start();


require_once __DIR__ . '/../src/database-model.php';


$db = new DatabaseModel();
$db->use_table("orders");

// Получаем данные из формы
$userId = $_SESSION['user-id'] ?? null;
$address = $_POST['address'] ?? '';
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$deliveryMethod = $_POST['delivery'] ?? '';
$comments = $_POST['comments'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if ($userId && $address && $name && $phone && $deliveryMethod) {
		// Добавление заказа в таблицу
		$sql = "INSERT INTO `orders` (user_id, address, name, email, phone, delivery_method, comments)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
		$stmt = $db->db->prepare($sql);
		$stmt->bind_param("issssss", $userId, $address, $name, $email, $phone, $deliveryMethod, $comments);

		if ($stmt->execute()) {
			// Заказ успешно добавлен
			$message = '<div class="success-message">
                            <h2>Ваш заказ успешно оформлен!</h2>
                            <p>Мы свяжемся с вами для подтверждения.</p>
                        </div>';
		} else {
			$message = '<div class="error-message">
                            <h3>Ошибка при оформлении заказа. Пожалуйста, попробуйте снова.</h3>
                        </div>';
		}
	} else {
		$message = '<div class="error-message">
                        <h3>Не все обязательные поля были заполнены.</h3>
                    </div>';
	}
}


?>
<!-- 
<!DOCTYPE html>
<html lang="ru">

<head>
	<meta charset="UTF-8">
	<title>Оформление заказа</title>
	<link rel="stylesheet" href="styles.css">
</head> -->

<body>
	<main class="order-page">
		<section class="order-section">
			<div class="section-text center">
				<h2>Результат оформления заказа</h2>

			</div>

			<!-- Выводим сообщение -->
			<?php if (isset($message)) echo $message; ?>

		</section>
	</main>
</body>

<!-- </html> -->