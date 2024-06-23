export default class Modal {
  #id;
  #target;

  constructor(id) {
    this.#id = id;

    try {
      this.#target = document.getElementById(id);
    } catch {
      console.error(`The element with the specified ID attribute '${this.#id}' does not exist!`);
    }

    this.#listenEvents();
    this.#initModalButtons();
  }

  /**
   * The `init` function initializes a collection of modal objects
   * and assigns them to the `window.modals` object.
   */
  static init() {
    window.modals = {};

    [...document.querySelectorAll('.modal')].forEach((modal) => {
      window.modals[modal.id] = new Modal(modal.id);
    });
  }

  /**
   * The function adds event listeners to a target element
   * and its child element to handle click events.
   */
  #listenEvents() {
    this.#target.addEventListener('click', (e) => {
      const content = this.#target.querySelector('.modal-content');

      content.style.transform = 'scale(1.05)';
      setTimeout(() => (content.style.transform = 'none'), 300);
    });

    this.#target.querySelector('.modal-content').addEventListener('click', (e) => e.stopPropagation());
  }

  /**
   * The function initializes modal buttons by adding event listeners
   * to open or close the modal based on the button's data attributes.
   */
  #initModalButtons() {
    const buttonsWithTarget = document.querySelectorAll(`[data-modal-target="${this.#id}"]`);
    const buttonsWithin = this.#target.querySelectorAll('.modal-button');

    [...buttonsWithTarget, ...buttonsWithin].forEach((btn) => {
      btn.addEventListener('click', () => {
        if (btn.dataset.clickMode === 'open') {
          this.show();
        } else {
          this.hide();
        }
      });
    });
  }

  /** Show the modal window */
  show() {
    document.body.style.overflow = 'hidden';
    this.#target.setAttribute('open', '');
  }

  /** Hide the modal window */
  hide() {
    document.body.style.overflow = 'initial';
    this.#target.removeAttribute('open');
  }
}
