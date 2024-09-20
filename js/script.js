import Modal from './components/modal.js';

if (document.getElementById('feedbacks-slider')) {
  const feedbacksSlider = new Splide('#feedbacks-slider', {
    perPage: 3,
    perMove: 1,
    gap: '2rem',
    pagination: true,
    arrows: true,
    arrowPath:
      'M12 4.88892L10.59 6.29892L16.17 11.8889H4V13.8889H16.17L10.59 19.4789L12 20.8889L20 12.8889L12 4.88892Z',
    breakpoints: {
      1200: {
        perPage: 2,
      },
      768: {
        perPage: 1,
      },
    },
  });

  feedbacksSlider.mount();
}

document.addEventListener('DOMContentLoaded', () => {
  [...document.querySelectorAll('.rating')].forEach((rating) => {
    let n = parseInt(rating.dataset.rate);
    n = Math.min(Math.max(0, n), 5); // Clamp between 0 and 5

    rating.innerHTML =
      '<img src="img/icons/filled-star.svg">'.repeat(n) + '<img src="img/icons/star.svg">'.repeat(5 - n);
  });
});

// Page loader
document.addEventListener('readystatechange', function () {
  const state = document.readyState;
  const loader = document.querySelector('.loader-container');

  if (loader === null) return;

  if (state === 'complete') {
    document.body.style.overflow = 'initial';
    loader.style.display = 'none';
  }
});

// Searching
document.querySelector('.search-container').addEventListener('submit', (e) => {
  e.preventDefault();
  window.location.href = `index.php?action=search&q=${e.target.elements['search-query'].value}`;
});






document.addEventListener('DOMContentLoaded', function() {
	// Инициализация модальных окон
	Modal.init();

	// Устанавливаем минимальную дату на текущую дату для поля выбора даты
	const dateInput = document.getElementById('date');
	const today = new Date().toISOString().split('T')[0]; // Получаем текущую дату в формате YYYY-MM-DD
	dateInput.setAttribute('min', today);

	// Обработчик формы бронирования
	const bookingForm = document.getElementById('booking-form');
	if (bookingForm) {
			bookingForm.addEventListener('submit', function(event) {
					event.preventDefault();

					const formData = new FormData(event.target);
					const data = Object.fromEntries(formData);

					// Дополнительная проверка на количество гостей
					const guests = parseInt(data.guests, 10);
					if (guests < 1) {
							alert('Количество гостей должно быть не менее 1.');
							return;
					}

					// Проверка, чтобы дата не была в прошлом
					const selectedDate = new Date(data.date);
					const currentDate = new Date();
					currentDate.setHours(0, 0, 0, 0); // Убираем время, чтобы сравнивать только даты

					if (selectedDate < currentDate) {
							alert('Вы не можете выбрать дату из прошлого.');
							return;
					}

					fetch('src/book-table.php', {
							method: 'POST',
							headers: {
									'Content-Type': 'application/json'
							},
							body: JSON.stringify(data)
					})
					.then(response => response.json())
					.then(data => {
							if (data.status === 'success') {
									alert('Ваш стол успешно забронирован!');
									window.modals['booking-modal'].hide(); // Закрываем модальное окно
									bookingForm.reset(); // Сброс формы после успешного бронирования
							} else {
									alert('Произошла ошибка. Попробуйте снова.');
							}
					})
					.catch(error => {
							alert('Произошла ошибка: ' + error.message);
					});
			});
	}
});
