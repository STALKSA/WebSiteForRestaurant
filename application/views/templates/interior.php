<?php include('templates/header.php'); ?>

<link rel="stylesheet" href="<?php echo base_url('assets/css/style.css'); ?>">

<!-- Замените 'path_to_your_interior_image' на путь к вашему изображению интерьера -->
<section id="interior" style="background-image: url('path_to_your_interior_image');">
    <div class="container">
        <h2>Интерьер</h2>
        <h3>Наши фотографии</h3>
    </div>
</section>

<section id="photos">
    <div class="container">
        <!-- Здесь вставьте фотографии интерьера -->
        <div class="photo-row">
            <div class="photo-item">
                <img src="path_to_your_photo1" alt="Photo 1">
            </div>
            <div class="photo-item">
                <img src="path_to_your_photo2" alt="Photo 2">
            </div>
            <!-- Продолжайте добавлять фотографии в таком же формате -->
        </div>
        <!-- Продолжайте добавлять ряды с фотографиями по вашему желанию -->
    </div>
</section>

<?php include('templates/footer.php'); ?>
