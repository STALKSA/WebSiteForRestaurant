/** A class with methods for generating random data */
export class Random {
  /**
   * Generate random string
   *
   * @param {number} length - The length of a random string
   * @returns A random ASCII string with the specified length
   */
  static string(length) {
    const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    let result = '';

    // Create an array of 32-bit unsigned integers
    const randomValues = new Uint32Array(length);

    // Generate random values
    window.crypto.getRandomValues(randomValues);
    randomValues.forEach((value) => {
      result += characters.charAt(value % characters.length);
    });

    return result;
  }

  /**
   * Generate random color in the HEX format
   * @returns A random HEX color
   */
  static hexColor() {
    return `#${Math.floor(Math.random() * (Math.pow(256, 3) - 1)).toString(16)}`;
  }

  /**
   * Generate a random number within the specified range
   *
   * @param {number} min - A lower boundary of the range
   * @param {number} max - A higher boundary of the range
   * @returns A random number between `min` and `max`
   */
  static numberBetween(min, max) {
    return Math.random() * (max - min) + min;
  }
}

/**
 * Scroll the window to bring a specified element into view with an optional offset.
 *
 * @param {HTMLElement} element - The element that you want to scroll into view.
 *
 * @param {number} offset - The offset parameter is a number that represents the additional
 * distance (in pixels) from the top of the element to scroll to. It allows you to scroll
 * to a position that is slightly above or below the element's top position.
 */
export function scrollIntoView(element, offset) {
  const y = element.getBoundingClientRect().top + window.scrollY - offset;
  window.scrollTo({ top: y, behavior: 'smooth' });
}
