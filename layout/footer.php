<footer>
	<div class="newsletter">
		<div class="newsletter-info">
			<div class="content-block">
				<h3 id="subscribe-title">Подпишитесь на нашу рассылку</h3>
				<p>Получайте эксклюзивные обновления: подпишитесь на нашу рассылку новостей</p>
			</div>

			<div class="content-block">
				<h3>Часы работы</h3>
				<div class="opening-hours">
					<p>Понедельник – Пятница</p>
					<p>Суббота – Воскресенье</p>
					<p>10:00 до 22:00</p>
					<p>10:00 до 00:00</p>
				</div>
			</div>
		</div>

		<div class="subscribe-form">
			<form id="subscribe-form">
				<input id="email" type="email" placeholder="Введите свой e-mail" aria-labelledby="subscribe-title" required>
				<button class="btn secondary-btn white" type="submit">Подписаться</button>
			</form>
			<p id="subscription-message" style="display: none;"></p>
			<p>Подписываясь, вы соглашаетесь с нашей <a href="index.php?action=privacy-policy">Политикой конфиденциальности</a></p>
		</div>
	</div>

	<div class="information">
		<div class="system-pages">
			<p>2024 MyRestaurant. Все права защищены.</p>
			<div>
				<a href="index.php?action=privacy-policy">Политика конфиденциальности</a>
				<!-- <a href="">Terms of Service</a>
				<a href="">Cookies Settings</a> -->
			</div>
		</div>

		<div class="social-links">
			<a href="https://www.facebook.com/">
				<i class="fa-brands fa-facebook-f" style="color: #ffffff;"></i>
			</a>

			<a href="https://www.instagram.com/">
				<i class="fa-brands fa-instagram" style="color: #ffffff;"></i>
			</a>

			<a href="https://twitter.com/">
				<i class="fa-brands fa-twitter" style="color: #ffffff;"></i>
			</a>

			<a href="https://www.linkedin.com/">
				<i class="fa-brands fa-linkedin" style="color: #ffffff;"></i>
			</a>
		</div>
	</div>
</footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js" integrity="sha256-FZsW7H2V5X9TGinSjjwYJ419Xka27I8XPDmWryGlWtw=" crossorigin="anonymous"></script>
<script src="js/components/modal.js" type="module"></script>
<script src="js/script.js" type="module"></script>
<script src="js/menu.js" type="module"></script>
<script src="js/captcha.js" type="module"></script>
<script>
	document.getElementById('subscribe-form').addEventListener('submit', function(event) {
		event.preventDefault(); // Предотвращаем перезагрузку страницы

		var email = document.getElementById('email').value;
		var messageElement = document.getElementById('subscription-message');

		if (email) {
			fetch('src/subscribe.php', { // Указание правильного пути к subscribe.php
					method: 'POST',
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded'
					},
					body: 'email=' + encodeURIComponent(email)
				})
				.then(response => response.text())
				.then(data => {
					if (data === 'success') {
						messageElement.textContent = 'Спасибо за подписку!';
						messageElement.style.display = 'block';
						messageElement.style.color = 'green';
					} else if (data === 'invalid') {
						messageElement.textContent = 'Пожалуйста, введите корректный email.';
						messageElement.style.display = 'block';
						messageElement.style.color = 'red';
					} else {
						messageElement.textContent = 'Произошла ошибка. Попробуйте позже. ' + data;
						messageElement.style.display = 'block';
						messageElement.style.color = 'red';
					}
				})
				.catch(error => {
					messageElement.textContent = 'Произошла ошибка. Попробуйте позже. ' + error;
					messageElement.style.display = 'block';
					messageElement.style.color = 'red';
				});
		} else {
			messageElement.textContent = 'Пожалуйста, введите корректный email.';
			messageElement.style.display = 'block';
			messageElement.style.color = 'red';
		}
	});
</script>


<div class="modal" id="booking-modal">
    <div class="modal-content">
        <h2>Бронирование стола</h2>
        <form id="booking-form">
            <label for="name">Имя:</label>
            <input type="text" id="name" name="name" required>
            
            <label for="phone">Телефон:</label>
            <input type="text" id="phone" name="phone" required>
            
            <label for="date">Дата:</label>
            <input type="date" id="date" name="date" required>
            
            <label for="time">Время:</label>
            <input type="time" id="time" name="time" required>
            
            <label for="guests">Количество гостей:</label>
            <input type="number" id="guests" name="guests" required>
            
            <button type="submit" class="btn primary-btn black">Забронировать</button>
            <button type="button" class="btn secondary-btn black modal-button" data-click-mode="close">Отмена</button>


        </form>
    </div>
</div>


</body>
</html>