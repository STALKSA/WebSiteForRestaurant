<?php

require_once("src/validation-utils.php");

$isValid = has_error("name") == "" && has_error("price") == "" && has_error("description") == "";
$isImageUploaded = false;


if (isset($_FILES["image"])) {
	$isImageUploaded = $_FILES["image"]["error"] == UPLOAD_ERR_OK;
}

if ($isValid && $isImageUploaded) {
	$name = basename($_FILES["image"]["name"]);

	$db->use_table("menu");
	$db->create([
		"published" => ActiveUser::isAdmin() ? "1" : "0",
		"author" => ActiveUser::getID(),
		"name" => get_val("name"),
		"description" => get_val("description"),
		"price" => get_val("price"),
		"image_path" => UPLOADS_DIR . $name
	]);

	if (!file_exists(UPLOADS_DIR)) {
		mkdir(UPLOADS_DIR, 0777, true);
	}

	$tmpName = $_FILES['image']['tmp_name'];
	$tmpName = str_replace("\\", "/", $tmpName);

	$res = copy($tmpName, UPLOADS_DIR . $name);
}

?>

<main>
	<?php if (!$isValid || !$isImageUploaded) { ?>
		<section class="dish-form-section create-section">
			<form class="form" action="" method="POST" enctype="multipart/form-data" novalidate>
				<h2>Добавить новое блюдо</h2>

				<div class="form-container">
					<label class="upload-image <?= isset($_FILES["image"]) ? 'error' : '' ?>">
						<img src="img/no-image.webp" alt="Dish image">
						<input type="file" name="image" id="dish-image">
					</label>

					<div class="fieldset">
						<div class="field <?= has_error("name") ?>">
							<label for="dish-name">Название</label>
							<input type="text" name="name" id="dish-name" value="<?= get_val("name") ?>">
							<i class="fa fa-solid fa-circle-exclamation" style="color: #ff0000;"></i>
							<span class="error-type required-error">Заполните поле</span>
						</div>

						<div class="field <?= has_error("price") ?>">
							<label for="dish-price">Цена</label>
							<input type="number" name="price" id="dish-price" value="<?= get_val("price") ?>">
							<i class="fa fa-solid fa-circle-exclamation" style="color: #ff0000;"></i>
							<span class="error-type required-error">Заполните поле</span>
						</div>

						<div class="field textarea <?= has_error("description") ?>">
							<label for="dish-description">Описание</label>
							<textarea name="description" id="dish-description"><?= get_val("description") ?></textarea>
							<i class="fa fa-solid fa-circle-exclamation" style="color: #ff0000;"></i>
							<span class="error-type required-error">Заполните поле</span>
						</div>
					</div>
				</div>

				<button class="btn primary-btn black" type="submit">Создать</button>
			</form>
		</section>
	<?php } else { ?>
		<section class="info-block">
			<h1>Удача!</h1>
			<div class="buttons">
				<a href="index.php" class="btn primary-btn black">На Главную</a>
				<a href="index.php?action=create-dish" class="btn secondary-btn black">Добавить еще блюдо</a>
			</div>
		</section>
	<?php } ?>
</main>
