<?php

$db->use_table('menu');

$isAdmin = ActiveUser::isAdmin(); 

$dish = null;

if (isset($_GET["id"])) {
    $id = (int) $_GET["id"];
    $result = $db->query("
        SELECT menu.*, users.login 
        FROM menu 
        LEFT JOIN users ON menu.author = users.id 
        WHERE menu.id = {$id}
    ");
    $dish = $result->fetch_array(MYSQLI_ASSOC);
}

?>

<main>
    <?php if ($dish) { ?>
        <section class="view-dish-section">
            <div class="dish-container">
                <div class="dish-image">
                    <img src="<?= $dish['image_path'] ?>" alt="">
                </div>

                <div class="dish-details">
                    <h2><?= $dish['name'] ?></h2>
                    <p class="description"><?= $dish['description'] ?></p>
                    <strong class="price">₽<?= $dish['price'] ?></strong>

                    <!-- Отображаем информацию об авторе и дате только если пользователь - админ -->
                    <?php if ($isAdmin) { ?>
                        <div class="info">
                            <i>добавлено <u><?= $dish['login'] ?></u></i>
                            <time datetime="<?= $dish['date'] ?>"><?= date_create($dish['date'])->format('d.m.Y') ?></time>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </section>
    <?php } else { ?>
        <section class="info-block">
            <h1>Блюдо не существует...</h1>
            <a href="index.php" class="btn primary-btn black">На Главную</a>
        </section>
    <?php } ?>
</main>