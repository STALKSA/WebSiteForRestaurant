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
