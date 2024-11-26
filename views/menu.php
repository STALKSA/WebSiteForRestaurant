<?php

$db->use_table("menu");

$isAdmin = ActiveUser::isAdmin();
$paginateBy = 6;

$count = $isAdmin ? $db->count() : $db->count("published = 1");
$maxPages = ceil($count / $paginateBy);

$page = isset($_GET["page"]) ? (int) $_GET["page"] : 1;
$page = min(max(1, $page), $maxPages);
$_GET["page"] = $page;

$offset = $paginateBy * ($page - 1);
$limit = "LIMIT {$paginateBy} OFFSET {$offset}";

$dishes = $isAdmin
	? $db->query("SELECT * FROM menu ORDER BY date DESC {$limit}")
	: $db->query("SELECT * FROM menu WHERE published = 1 ORDER BY date DESC {$limit}");

?>

<body>
<main>
	<section class="menu-section">
		<div class="section-text center">
			<h2>Наши фирменные блюда</h2>
			<p> Откройте для себя кулинарные шедевры, тщательно созданные нашими талантливыми поварами</p>
		</div>

		<div class="cards-container">
			<?php foreach ($dishes as $i => $dish) { ?>
				<?php if ($i > $paginateBy - 1) break; ?>
				<div class="card <?= !$dish['published'] ? 'not-published' : '' ?>" title="Created: <?= date_create($dish["date"])->format("d.m.Y") ?>">
					<?php if ($isAdmin) { ?>
						<div class="admin-buttons">
							<a class="btn edit-button" href="index.php?action=edit-dish&id=<?= $dish['id'] ?>" title="Edit">
								<i class="fa fa-solid fa-pen-to-square" style="color: #ffffff;"></i>
							</a>
							<button class="btn delete-button" title="Delete" data-id="<?= $dish['id'] ?>" data-modal-target="confirm-delete-modal" data-click-mode="open">
								<i class="fa fa-solid fa-trash-can" style="color: #ffffff;"></i>
							</button>
						</div>
					<?php } ?>

					<div class="card-content">
						<div class="card-body">
							<div class="cover-image">
								<img src="<?= $dish["image_path"] ?>" alt="<?= $dish["name"] ?>">
							</div>

							<div class="info">
								<h3><?= $dish["name"] ?></h3>
								<p><?= $dish["description"] ?></p>
							</div>
						</div>

						<div class="card-footer">
							<a href="index.php?action=view-dish&id=<?= $dish['id'] ?>" class="btn secondary-btn black">Подробнее</a>
							<div class="price-and-cart">
                        <strong>₽<?= $dish["price"] ?></strong>
                        <button class="btn cart-btn" data-product-id="<?= $dish['id'] ?>" title="Добавить в корзину">
                            <i class="fa fa-solid fa-cart-plus"></i>
                        </button>
                    </div>
						</div>
					</div>
				</div>
			<?php } ?>
		</div>

		<div class="pagination">
			<button class="prev-page">
				<i class="fa fa-solid fa-angle-left"></i>
			</button>

			<?php for ($i = 1; $i <= $maxPages; $i++) { ?>
				<button class="page <?= $i == $_GET['page'] ? 'current-page' : '' ?>" data-page-number="<?= $i ?>"><?= $i ?></button>
			<?php } ?>

			<button class="next-page">
				<i class="fa fa-solid fa-angle-right"></i>
			</button>
		</div>
	</section>
</main>


<script>
document.addEventListener('DOMContentLoaded', function() {
    // Получаем все кнопки для добавления в корзину
    const cartButtons = document.querySelectorAll('.cart-btn');

    cartButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Получаем ID продукта из атрибута data-product-id
            const productId = this.getAttribute('data-product-id');
            
            // Отправляем запрос на сервер для добавления товара в корзину
            fetch('add-to-cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `product_id=${productId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Обновляем счетчик корзины
                    const cartCount = document.getElementById('cart-count');
                    cartCount.textContent = data.cartTotalItems;

                    // Выводим уведомление об успешном добавлении
                    alert('Товар добавлен в корзину!');
                } else {
                    alert('Ошибка при добавлении товара в корзину.');
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
});

document.querySelectorAll('.delete-button').forEach(button => {
    button.addEventListener('click', function() {
        const dishId = this.getAttribute('data-id');
        document.getElementById('confirm-delete-button').href = `index.php?action=delete-dish&id=${dishId}`;
    });
});
</script>


</body>

<div class="modal" id="confirm-delete-modal">
	<div class="modal-content">
		<h3>Вы уверены, что хотите удалить эту запись?</h3>

		<div class="buttons">
			<button class="modal-button btn primary-btn black" data-click-mode="close">Нет</button>
			<a class="modal-button btn secondary-btn black" data-click-mode="close" href="index.php">Да</a>
		</div>
	</div>
</div>
