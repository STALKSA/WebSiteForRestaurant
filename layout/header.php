<!DOCTYPE html>

<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>
		<?= PAGE_TITLES[isset($_GET["action"]) ? $_GET["action"] : "main"] ?>
	</title>
	<link rel="shortcut icon" href="img/favicon.png" type="image/x-icon">

	<!-- Fonts -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

	<!-- Font Awesome -->
	<script src="https://kit.fontawesome.com/4f1ad13275.js" crossorigin="anonymous"></script>

	<!-- Styles -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css" integrity="sha256-5uKiXEwbaQh9cgd2/5Vp6WmMnsUr3VZZw0a8rKnOKNU=" crossorigin="anonymous">
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/components/modal.css">
</head>

<body style="overflow: hidden;">
	<div class="loader-container">
		<div class="loader"></div>
	</div>

	<header>
		<a href="index.php">
			<img src="img/icons/logomak_logo-_1_.svg" alt="Restaurant Logo" style="width: 200px; height: auto;">
		</a>

		<svg class="hamburger-icon" viewBox="0 0 100 100" width="60" onclick="this.classList.toggle('active')">
			<path class="line top" d="m 70,33 h -40 c 0,0 -8.5,-0.149796 -8.5,8.5 0,8.649796 8.5,8.5 8.5,8.5 h 20 v -20" />
			<path class="line middle" d="m 70,50 h -40" />
			<path class="line bottom" d="m 30,67 h 40 c 0,0 8.5,0.149796 8.5,-8.5 0,-8.649796 -8.5,-8.5 -8.5,-8.5 h -20 v 20" />
		</svg>

		<div class="navbar">
			<nav class="menu">
				<ul>
					<li><a href="index.php">Главная</a></li>
					<li><a href="index.php?action=about">О нас</a></li>
					<li><a href="index.php?action=menu">Меню</a></li>
				</ul>
			</nav>

			<form class="search-container" method="post">
				<input type="search" placeholder="Поиск" name="search-query">
				<button type="submit"></button>
			</form>

			<?php if (ActiveUser::getLogin()) { ?>
				<div class="dropdown">
					<div class="dropdown-button">
						<i class="fa fa-duotone fa-circle-user"></i>
					</div>

					<div class="dropdown-body">
						<ul>
							<?php if (ActiveUser::isAdmin()) { ?>
								<li>
									<a href="index.php?action=create-dish">Создать новое блюдо</a>
								</li>
							<?php } ?>
							<li>
								<a href="index.php?action=logout" style="color: red;">Выйти</a>
							</li>
						</ul>
					</div>
				<?php } else { ?>
					<a class="btn primary-btn black" href="index.php?action=login">Войти</a>
				<?php } ?>
				</div>
	</header>