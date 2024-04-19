<?php include('templates/header.php'); ?>

<link rel="stylesheet" href="<?php echo base_url('assets/css/style.css'); ?>">

<!-- Замените 'path_to_your_interior_image' на путь к вашему изображению интерьера -->
<section id="interior">
    <div class="background-overlay"></div> <!-- Новый элемент для фона -->
    <div id="interior-header">
        <h2>Интерьер</h2>
        <h3>Наши фотографии</h3>
    </div>
</section>

    <section id="photos">
        <div class="photo-row">
            <div class="photo-item">
                <img src="<?php echo base_url('assets/img/interier1.jpg'); ?>" alt="Photo 1">
            </div>
            <div class="photo-item">
                <img src="<?php echo base_url('assets/img/interier2.jpg'); ?>" alt="Photo 2">
            </div>
            <div class="photo-item">
                <img src="<?php echo base_url('assets/img/interier3.jpg'); ?>" alt="Photo 1">
            </div>
            <div class="photo-item">
                <img src="<?php echo base_url('assets/img/interier4.jpg'); ?>" alt="Photo 1">
            </div>
            <div class="photo-item">
                <img src="<?php echo base_url('assets/img/interier5.jpg'); ?>" alt="Photo 1">
            </div>
            <!-- Добавьте остальные фотографии здесь -->
        </div>
    </section>

<?php include('templates/footer.php'); ?>
