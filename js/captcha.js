import { Random } from './utils.js';

const canvas = document.querySelector('.captcha-image canvas');

if (canvas) {
  const ctx = canvas.getContext('2d');
  ctx.strokeStyle = 'black';

  const captchaBackground = new Image(300, 100);
  captchaBackground.src = 'img/captcha-bg.png';

  captchaBackground.onload = function () {
    ctx.drawImage(captchaBackground, 0, 0);

    const str = Random.string(6);

    document.getElementById('captcha-value-encoded').value = btoa(str);

    for (let i = 0; i < str.length; i++) {
      ctx.save();

      ctx.translate(40 * (i + 1), 60 + Random.numberBetween(-10, 10));
      ctx.rotate(Random.numberBetween(-0.5, 0.5));
      ctx.font = `${Random.numberBetween(40, 50)}px serif`;
      ctx.fillStyle = Random.hexColor();

      ctx.fillText(str.charAt(i), 0, 0);
      ctx.strokeText(str.charAt(i), 0, 0);

      ctx.restore();
    }
  };

  document.querySelector('.reload-captcha').addEventListener('click', function (e) {
    e.preventDefault();
    captchaBackground.onload();
  });
}
