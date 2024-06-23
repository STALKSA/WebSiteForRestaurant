<main>
	<section class="page-cover about-us-page">
		<div class="inner">
			<h1>О Нас</h1>
			<p>
			Добро пожаловать в MyRestaurant, где кулинарное мастерство сочетается с теплой и уютной атмосферой.
  		Мы больше, чем просто ресторан; мы кулинарное путешествие, праздник вкусов,
  		и место, где создаются незабываемые моменты.
			</p>
		</div>
	</section>

	<?php require_once("layout/components/about-sections.php"); ?>

	<section class="achievements-section">
		<h2>Наши Достижения</h2>
		<p class="description">
		В MyRestaurant мы гордимся своим путешествием и вехами, которых мы достигли на этом пути.
    Эти достижения являются свидетельством нашей приверженности кулинарному совершенству и удовлетворению
    наших уважаемых гостей. Для нас большая честь поделиться с вами некоторыми из наших самых ярких достижений:
		</p>

		<div class="achievements-container">
			<div class="achievement">
				<h3>+200</h3>
				<p>Обслуженных клиентов</p>
			</div>

			<div class="achievement">
				<h3>50K</h3>
				<p>Сетей</p>
			</div>

			<div class="achievement">
				<h3>370k</h3>
				<p>Поставщиков</p>
			</div>

			<div class="achievement">
				<h3>100+</h3>
				<p>Признаний</p>
			</div>
		</div>
	</section>

	<section class="feedbacks-section">
		<h2>Наши счастливые клиенты</h2>
		<p class="description">Сердце и душа MyRestaurant – улыбки и удовлетворение наших дорогих гостей.</p>

		<!-- TODO. It's only for testing -->
		<div id="feedbacks-slider" class="splide">
			<div class="splide__track">
				<ul class="splide__list">
					<?php for ($i = 0; $i < 4; $i++) { ?>
						<li class="splide__slide">
							<div class="feedback-card">
								<div class="rating" data-rate="5"></div>
								<q>MyRestaurantой любимый ресторан уже 2 года. Отличная атмосфера и приятный персонал. Интерьер сделан с душой как и еда.</q>
								<div class="author">
									<div class="author-image">
										<img src="img/avatar-1.jpg" alt="">
									</div>

									<div class="author-info">
										<h3>Павел</h3>
										<time datetime="20.04.2024">20.04.2024</time>
									</div>
								</div>
							</div>
						</li>

						<li class="splide__slide">
							<div class="feedback-card">
								<div class="rating" data-rate="3"></div>
								<q>Все классно, но бывает не успеваб на завтраки, быстро заканчиваются, и хотелось бы разнообразить напитки, например добавить больше соков</q>
								<div class="author">
									<div class="author-image">
										<img src="img/avatar-2.jpg" alt="">
									</div>

									<div class="author-info">
										<h3>Мари</h3>
										<time datetime="04.05.2024">04.05.2024</time>
									</div>
								</div>
							</div>
						</li>

						<li class="splide__slide">
							<div class="feedback-card">
								<div class="rating" data-rate="4"></div>
								<q>Хорошая атмосфера. Люблю посидеть и спокойно поработать. Обычно в таких заведениях это нереально. Но в MyRerstaurant все как надо</q>
								<div class="author">
									<div class="author-image">
										<img src="img/avatar-3.jpg" alt="">
									</div>

									<div class="author-info">
										<h3>Антон Г.</h3>
										<time datetime="12.06.2024">12.06.2024</time>
									</div>
								</div>
							</div>
						</li>
					<?php } ?>
				</ul>
			</div>
		</div>
	</section>
</main>
