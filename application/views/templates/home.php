<!-- Здесь разместите модули и разделы главной страницы -->
<div id="header">
    <!-- Здесь разместите логотип и навигацию -->
</div>

<div id="restaurant-module">
    <!-- Здесь разместите модуль с заголовком ресторан -->
</div>

<div id="about-module">
    <!-- Здесь разместите модуль "О нас" с кнопкой связаться с нами -->
</div>

<!-- Добавьте другие модули аналогичным образом -->

<div id="footer">
    <!-- Здесь добавьте футер, контакты и форму заказа столика -->
    <h2>Забронировать столик</h2>
    <?php echo form_open('restaurant/reserve_table'); ?>

    <label for="name">Ваше имя:</label>
    <input type="text" name="name" required>

    <label for="phone">Ваш телефон:</label>
    <input type="tel" name="phone" required>

    <label for="date">Дата:</label>
    <input type="date" name="date" required>

    <label for="time">Время:</label>
    <input type="time" name="time" required>

    <label for="message">Сообщение:</label>
    <textarea name="message"></textarea>

    <button type="submit">Забронировать столик</button>

    <?php echo form_close(); ?>

</div>
