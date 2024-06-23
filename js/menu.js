import Modal from './components/modal.js';
import { scrollIntoView } from './utils.js';

// Image preview

const fileTypes = [
  'image/apng',
  'image/bmp',
  'image/gif',
  'image/jpeg',
  'image/pjpeg',
  'image/png',
  'image/svg+xml',
  'image/tiff',
  'image/webp',
  'image/x-icon',
];

const fileInput = document.getElementById('dish-image');
const img = document.querySelector('.upload-image > img');

fileInput?.addEventListener('change', () => {
  const [file] = fileInput.files;

  if (file && fileTypes.includes(file.type)) {
    img.src = URL.createObjectURL(file);
  }
});

// Pagination

async function getPageContent(pageNumber) {
  const response = await fetch(`index.php?action=menu&page=${pageNumber}`);

  const parser = new DOMParser();
  const dom = parser.parseFromString(await response.text(), 'text/html');

  return dom.querySelector('.cards-container').innerHTML;
}

document.querySelector('.pagination')?.addEventListener('click', async (e) => {
  const element = e.target;
  const currentPage = document.querySelector('.pagination .current-page');
  const pageNum = parseInt(currentPage.dataset.pageNumber);
  const maxPage = parseInt(document.querySelector('.pagination .page:nth-last-of-type(2)').dataset.pageNumber);

  let response;

  const goToPage = async (number) => {
    currentPage.classList.remove('current-page');
    document.querySelector(`.pagination [data-page-number="${number}"]`).classList.add('current-page');
    return await getPageContent(number);
  };

  if (element.matches('.prev-page, .prev-page > *') && pageNum > 1) {
    response = await goToPage(pageNum - 1);
  } else if (element.matches('.next-page, .next-page > *') && pageNum < maxPage) {
    response = await goToPage(pageNum + 1);
  } else if (element.matches('.page')) {
    response = await goToPage(element.dataset.pageNumber);
  } else {
    return;
  }

  document.querySelector('.cards-container').innerHTML = response;

  updateDeleteButtons();
  Modal.init();

  scrollIntoView(document.querySelector('.cards-container'), 130);
});

// Delete buttons

function updateDeleteButtons() {
  const deleteButtons = document.querySelectorAll('.card .admin-buttons > .delete-button');

  [...deleteButtons].forEach((btn) => {
    btn.addEventListener('click', () => {
      document.querySelector(
        '#confirm-delete-modal .buttons a'
      ).href = `index.php?action=delete-dish&id=${btn.dataset.id}`;
    });
  });
}

document.addEventListener('DOMContentLoaded', () => {
  updateDeleteButtons();
  Modal.init();
});
