<?php

require_once("src/validation-utils.php");


$db->use_table('menu');

$dish = null;

if (isset($_GET["id"])) {
	$id = (int) $_GET["id"];
	$result = $db->query("SELECT * FROM users INNER JOIN menu ON author = users.id WHERE menu.id = {$id}");
	$dish = $result->fetch_array(MYSQLI_ASSOC);
}

function new_value(string $name) {
	global $dish;

	$value = get_val($name);
	return $value == "" ? $dish[$name] : $value;
}

$isValid = has_error("name") == "" && has_error("price") == "" && has_error("description") == "";
$isImageUploaded = false;


if (isset($_FILES["image"])) {
	$isImageUploaded = $_FILES["image"]["error"] == UPLOAD_ERR_OK;
}

$success = false;

if ($isValid) {
	$name = $dish['image_path'];

	if ($isImageUploaded) {
		$name = UPLOADS_DIR . basename($_FILES["image"]["name"]);

		if (!file_exists(UPLOADS_DIR)) {
			mkdir(UPLOADS_DIR, 0777, true);
		}

		$tmpName = $_FILES['image']['tmp_name'];
		$tmpName = str_replace("\\", "/", $tmpName);

		$res = copy($tmpName, $name);
	}

	$success = $db->update($dish["id"], [
		"published" => isset($_POST["published"]) ? "1" : "0",
		"name" => get_val("name"),
		"description" => get_val("description"),
		"price" => get_val("price"),
		"image_path" => $name
	]);
}

?>

<main>
	<?php if (!ActiveUser::isAdmin() || !$success) { ?>
		<section class="info-block">
			<h1>Страница не найдена...</h1>
			<a href="index.php" class="btn primary-btn black">На Главную</a>
		</section>
	<?php } else if (!$isValid) { ?>
		<section class="dish-form-section edit-section">
			<form class="form" action="" method="POST" enctype="multipart/form-data" novalidate>
				<h2>Редактировать блюдо</h2>

				<div class="form-container">
					<label class="upload-image">
						<img src="<?= $dish['image_path'] ?>" alt="Dish image">
						<input type="file" name="image" id="dish-image">
					</label>

					<div class="fieldset">
						<div class="field <?= has_error("name") ?>">
							<label for="dish-name">Название</label>
							<input type="text" name="name" id="dish-name" value="<?= new_value('name') ?>">
							<i class="fa fa-solid fa-circle-exclamation" style="color: #ff0000;"></i>
							<span class="error-type required-error">Required</span>
						</div>

						<div class="field <?= has_error("price") ?>">
							<label for="dish-price">Цена</label>
							<input type="number" name="price" id="dish-price" value="<?= new_value('price') ?>">
							<i class="fa fa-solid fa-circle-exclamation" style="color: #ff0000;"></i>
							<span class="error-type required-error">Required</span>
						</div>

						<div class="field textarea <?= has_error("description") ?>">
							<label for="dish-description">Описание</label>
							<textarea name="description" id="dish-description"><?= new_value('description') ?></textarea>
							<i class="fa fa-solid fa-circle-exclamation" style="color: #ff0000;"></i>
							<span class="error-type required-error">Required</span>
						</div>

						<div class="field checkbox">
							<input type="checkbox" name="published" class="fa fa-solid fa-check" id="dish-published" value="<?= new_value('published') ?>" <?= new_value('published') ? "checked" : "" ?>>
							<label for="dish-published">Published</label>
						</div>
					</div>
				</div>

				<button class="btn primary-btn black" type="submit">Update</button>
			</form>
		</section>
	<?php } else { ?>
		<section class="info-block">
			<h1>Удача!</h1>
			<div class="buttons">
				<a href="index.php" class="btn primary-btn black">На Главную</a>
				<a href="index.php?action=menu" class="btn secondary-btn black">Посмотреть меню</a>
			</div>
		</section>
	<?php } ?>
</main>
