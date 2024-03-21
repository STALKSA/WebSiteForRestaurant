<?php include('templates/header.php'); ?>

<link rel="stylesheet" href="<?php echo base_url('assets/css/style.css'); ?>">

<!-- Модуль "О Нас" -->
<section id="about-us">
    <div class="container">
        <h2>О Нас</h2>
    </div>
</section>

<!-- Модуль "История" -->
<section id="history">
    <div class="container">
        <h3>История</h3>
        <div class="history-content">
            <div class="history-text">
                <p>Здесь будет текст истории нашего ресторана...</p>
                <a href="#" class="btn-reserve">Забронировать столик</a>
            </div>
            <div class="history-images">
                <img src="path_to_image1" alt="Image 1">
                <img src="path_to_image2" alt="Image 2">
            </div>
        </div>
    </div>
</section>

<!-- Модуль "Еще немного слов" -->
<section id="more-words">
    <div class="container">
        <h3>Еще немного слов</h3>
        <p>Дополнительный текст о нашем ресторане...</p>
        <a href="#" class="btn-menu">Посмотреть меню</a>
    </div>
</section>

<?php include('templates/footer.php'); ?>