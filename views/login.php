<?php

$db->use_table("users");
$foundUser = null;

// Verify user password
function verify($user) {
    return isset($_POST["password"], $user["password"]) && password_verify($_POST["password"], $user["password"]);
}

if (isset($_POST["email"])) {
	$email = $_POST['email'];
	$users = $db->find("email = '$email'");

	if (!empty($users)) {
			$enteredPassword = $_POST["password"];
			$storedHash = $users[0]["password"];

			// Добавляем вывод для отладки
			echo "Введённый пароль: " . htmlspecialchars($enteredPassword) . "<br>";
			echo "Хеш из базы данных: " . htmlspecialchars($storedHash) . "<br>";

			if (password_verify($enteredPassword, $storedHash)) {
					echo "Пароль верен!";
					$foundUser = $users[0];
			} else {
					echo "Неверный пароль!";
			}
	} else {
			echo "Пользователь не найден!";
	}
}

if (!is_null($foundUser)) {
    // Инициализация сессии пользователя, если найден
    ActiveUser::init(
        (int) $foundUser["id"],
        $foundUser["email"],
        $foundUser["login"],
        (bool) $foundUser["is_admin"]
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
                <p>Или <a href="index.php?action=registration">зарегистрируйтесь</a>, если у вас нет учетной записи</p>

                <div class="field">
                    <label for="login-email">Email</label>
                    <input type="text" name="email" id="login-email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>

                <div class="field">
                    <label for="password">Пароль</label>
                    <input type="password" name="password" id="password" value="">
                </div>

                <?php if (isset($_POST["email"], $_POST["password"])) {
                    if (empty($users)) {
                        echo '<strong style="color: red">Пользователь с таким email не найден!</strong>';
                    } elseif (!verify($users[0])) {
                        echo '<strong style="color: red">Неверный пароль!</strong>';
                    }
                } ?>

                <button class="btn primary-btn black" type="submit">Войти</button>
            </form>
        </section>
    <?php } else { ?>
        <section class="info-block">
            <h1>Удача!</h1>
            <a href="index.php" class="btn primary-btn black">На Главную</a>
        </section>
    <?php } ?>
</main>
