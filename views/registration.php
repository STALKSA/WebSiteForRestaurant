<?php require_once("src/validation-utils.php");

// Error for the second input
function error_if($firstInput, $secondInput, $errorType = "invalid", $predicate = null) {
	if (is_null($predicate)) $predicate = fn ($value1, $value2) => $value1 != $value2;

	if (!isset($_POST[$firstInput]) || !isset($_POST[$secondInput])) {
		return "unset";
	} else if ($_POST[$secondInput] == "") {
		return "error required";
	} else if ($predicate($_POST[$firstInput], $_POST[$secondInput])) {
		return "error " . $errorType;
	}

	return "";
}

$ERRORS = [
	"login" => has_error("login", "/^[a-zA-Zа-яА-Я0-9_-]{4,}$/"),
	"password" => has_error("password", "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{7,}$/"),
	"confirm-password" => error_if("password", "confirm-password"),
	"email" => has_error("email", "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/"),
	"captcha" => error_if("captcha-value-encoded", "captcha", "invalid", fn ($val1, $val2) => base64_decode($val1) != $val2),
];

$formValid = !in_array(true, array_map(fn ($item) => str_starts_with($item, "error") || $item == "unset", $ERRORS));

// Add to the database
if ($formValid) {
	$db->use_table("users");
	$db->create([
		"login" => get_val("login"),
		"password" => password_hash(get_val("password"), PASSWORD_BCRYPT),
		"email" => get_val("email")
	]);

	$currentUser = $db->find("email = '" . get_val("email") . "'")[0];

	ActiveUser::init(
		(int) $currentUser["id"],
		$currentUser["email"],
		$currentUser["login"],
		(bool) $currentUser["admin"]
	);
}

?>

<main>
	<?php if (!$formValid) { ?>
		<section class="form-section">
			<form class="form" action="" method="POST" novalidate>
				<h2>Регистрация</h2>

				<div class="field <?= $ERRORS["login"] ?>">
					<label for="reg-login">Login</label>
					<input type="text" name="login" id="reg-login" value="<?= get_val("login") ?>" autocomplete="given-name">
					<i class="fa fa-solid fa-circle-exclamation" style="color: #ff0000;"></i>
					<span class="error-type required-error">Required</span>
					<span class="error-type invalid-error">Login не валиден!</span>
				</div>

				<div class="field <?= $ERRORS["password"] ?>">
					<label for="reg-password">Пароль</label>
					<input type="password" name="password" id="reg-password" value="<?= get_val("password") ?>" autocomplete="new-password">
					<i class="fa fa-solid fa-circle-exclamation" style="color: #ff0000;"></i>
					<span class="error-type required-error">Required</span>
					<span class="error-type invalid-error">Пароль не валиден!</span>
				</div>

				<div class="field <?= $ERRORS["confirm-password"] ?>">
					<label for="reg-confirm-password">Подтвердите пароль</label>
					<input type="password" name="confirm-password" id="reg-confirm-password" value="<?= get_val("confirm-password") ?>" autocomplete="new-password">
					<i class="fa fa-solid fa-circle-exclamation" style="color: #ff0000;"></i>
					<span class="error-type required-error">Required</span>
					<span class="error-type invalid-error">Пароль не совпал!</span>
				</div>

				<div class="field <?= $ERRORS["email"] ?>">
					<label for="reg-email">Email</label>
					<input type="email" name="email" id="reg-email" value="<?= get_val("email") ?>" autocomplete="email">
					<i class="fa fa-solid fa-circle-exclamation" style="color: #ff0000;"></i>
					<span class="error-type required-error">Required</span>
					<span class="error-type invalid-error">Email должпен быть формата username@example.com</span>
				</div>

				<div class="field <?= $ERRORS["captcha"] ?>">
					<label for="captcha">Введите символы с картинки</label>

					<div class="captcha-container">
						<div class="captcha-image">
							<canvas width="300" height="100"></canvas>
						</div>
						<div class="captcha-content">
							<input type="text" name="captcha" id="captcha">
							<input type="hidden" name="captcha-value-encoded" id="captcha-value-encoded">
							<button class="reload-captcha btn secondary-btn black">
								<i class="fa fa-solid fa-rotate" style="color: #000000;"></i>
							</button>
						</div>
					</div>

					<span class="error-type required-error">Required</span>
					<span class="error-type invalid-error">Попробуйте еще раз!</span>
				</div>

				<button class="btn primary-btn black" type="submit">Зарегистрироваться</button>
			</form>

			<div class="section-image">
				<img src="img/registration-cover.jpg" alt="Registration">
			</div>
		</section>
	<?php } else { ?>
		<section class="info-block">
			<h1>Удача!</h1>
			<a href="index.php" class="btn primary-btn black">На Главную</a>
		</section>
	<?php } ?>
</main>
