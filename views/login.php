<?php

$db->use_table("users");
$foundUser = null;

// Verify user password
function verify($user) {
	return isset($_POST["password"]) ? password_verify($_POST["password"], $user["password"]) : false;
}

if (isset($_POST["email"])) {
	$result = $db->find("email = '{$_POST['email']}'")[0];

	if (verify($result)) {
		$foundUser = $result;
	}
}

if (!is_null($foundUser)) {
	ActiveUser::init(
		(int) $foundUser["id"],
		$foundUser["email"],
		$foundUser["login"],
		(bool) $foundUser["admin"]
	);
}

?>

<main>
	<?php if (is_null($foundUser)) { ?>
		<section class="form-section">
			<div class="section-image">
				<img src="img/login-page-cover.jpg" alt="Registration">
			</div>

			<form class="form" action="" method="POST" novalidate>
				<h2>Войдите в свою учетную запись</h2>
				<p>Или <a href="index.php?action=registration">зарегистрируйтесь</a> ,если у вас нет учетной записи</p>

				<div class="field">
					<label for="email">Email</label>
					<input type="text" name="email" id="email" value="">
				</div>

				<div class="field">
					<label for="password">Пароль</label>
					<input type="password" name="password" id="password" value="">
				</div>

				<?php if (isset($_POST["email"]) && isset($_POST["password"])) { ?>
					<strong style="color: red">Email или пароль не валидны!</strong>
				<?php } ?>

				<button class="btn primary-btn black" type="submit">Log In</button>
			</form>
		</section>
	<?php } else { ?>
		<section class="info-block">
			<h1>Удача!</h1>
			<a href="index.php" class="btn primary-btn black">На Главную</a>
		</section>
	<?php } ?>
</main>
