<main>
	<section class="main-banner">
		<div class="banner-content">
			<h1>Почувствуйте настоящий вкус <strong>Лучших блюд</strong></h1>
			<p class="section-description">Ощутите настоящий вкус <strong>блюд</strong></p>
			<div class="buttons">
				<button class="btn primary-btn white">Забронировать столик</button>
				<button class="btn secondary-btn white">Заказать онлайн</button>
			</div>
		</div>
	</section>

	<?php require_once("layout/components/about-sections.php"); ?>
</main>


<!-- Модальное окно для main.php -->
<div id="bookingModalMain" class="modal">
    <div class="modal-content">
        <span class="closeBtn">&times;</span>
        <h2>Бронирование стола</h2>
        <form id="bookingFormMain" method="POST">
            <label for="name">Ваше имя:</label>
            <input type="text" id="name" name="name" required>

            <label for="phone">Номер телефона:</label>
            <input type="text" id="phone" name="phone" required>

            <label for="date">Дата:</label>
            <input type="date" id="date" name="date" required>

            <label for="time">Время:</label>
            <input type="time" id="time" name="time" required>

            <label for="guests">Количество гостей:</label>
            <input type="number" id="guests" name="guests" required>

            <input type="submit" value="Забронировать">
        </form>
    </div>
</div>