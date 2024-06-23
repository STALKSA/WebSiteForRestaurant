<?php

$db->use_table('menu');
$success = false;

if (ActiveUser::isAdmin() && isset($_GET["id"])) {
	$id = (int) $_GET["id"];
	$success = $db->delete($id);
}

?>

<main>
	<?php if ($success) { ?>
		<section class="info-block">
			<h1>Запись успешно удалена!</h1>
			<div class="buttons">
				<a href="index.php" class="btn primary-btn black">На Главную</a>
				<a href="index.php?action=menu" class="btn secondary-btn black">Посмотреть меню</a>
			</div>
		</section>
	<?php } else { ?>
		<section class="info-block">
			<h1>Страница не найдена...</h1>
			<a href="index.php" class="btn primary-btn black">На Главную</a>
		</section>
	<?php } ?>
</main>
