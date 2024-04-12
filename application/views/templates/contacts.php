<?php include('templates/header.php'); ?>

<!-- Подключаем файл стилей -->
<link rel="stylesheet" href="<?php echo base_url('assets/css/style.css'); ?>">


<section id="contacts">
    <div class="container">
        <h2>Контакты</h2>
        <!-- <p>Свяжитесь или посетите нас</p> -->
    </div>
</section>

<section id="contact-details">
    <div class="container">
        <div class="contact-info">
            <div class="contact-item">
                <h3>Адрес</h3>
                <p>Ваш адрес</p>
            </div>
            <div class="contact-item">
                <h3>Телефон</h3>
                <p>Ваш телефон</p>
            </div>
            <div class="contact-item">
                <h3>Email</h3>
                <p>Ваш email</p>
            </div>
        </div>

        <div class="image-collage">
            <img src="<?php echo base_url('assets/img/back_img_for_head_module.jpg'); ?>" alt="Image 1" class="collage-image">
            <img src="<?php echo base_url('assets/img/back_img_for_footer_module.jpg'); ?>" alt="Image 2" class="collage-image">
            <!-- Добавьте сколько угодно изображений -->
        </div>

    </div>
</section>

<section id="map">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1999.6535427967215!2d30.32586821572889!3d59.93290698187876!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x46963155bde5a0d1%3A0x65ad918b9f094b3c!2z0L_RgNC-0YHQv9C10LrRgiDQkNGA0LDRgtGL0LUg0LggMzktNSwg0J7Qu9C70LDRgtC10YDRgdC60LDRjyDQvtCx0LsuLCAyMTI2NjM!5e0!3m2!1sen!2suk!4v1648989969390!5m2!1sen!2suk" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
</section>

<script src="<?php echo base_url('assets/js/script.js'); ?>"></script>

<!-- Модуль "Футер" -->
<?php include('templates/footer.php'); ?>