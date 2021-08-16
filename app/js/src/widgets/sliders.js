class Sliders {
  constructor(item) {
    this.nodeElement = item;
    this.swiper = null;
    this.check = false;

    this.addEvents();
    onResize(this.addEvents.bind(this))
  }

  addEvents() {
    if (isMobileLayout()) {
      if (!this.check) this.initSwiper();
    } else {
      if (this.check) {
        this.destroySwiper();
      }
    }
  }

  initSwiper() {
    this.swiper = new Swiper(this.nodeElement, {
      slidesPerView: 1.06,
      spaceBetween: 10,
      loop: true,
      speed: 1000,
      autoplay: false,
      pagination: {
        el: '.swiper-pagination',
        type: 'bullets',
        clickable: true,
        dynamicBullets: false,
        bulletActiveClass: 'active',
      },
    });
    this.check = true;
  }

  destroySwiper() {
    this.swiper.destroy(true, true);
    this.check = false;
  }

  static init(elem) {
    new Sliders(elem);
  }
}

class SliderRelated {
  constructor(item) {
    this.nodeElement = item;
    this.swiper = null;
    this.check = false;

    this.addEvents();
    onResize(this.addEvents.bind(this))
  }

  addEvents() {
    if (isMobileLayout()) {
      if (!this.check) this.initSwiper();
    } else {
      if (this.check) {
        this.destroySwiper();
      }
    }
  }

  initSwiper() {
    this.swiper = new Swiper(this.nodeElement, {
      slidesPerView: 1,
      spaceBetween: 20,
      loop: true,
      speed: 1000,
      autoplay: false,
      pagination: {
        el: '.swiper-pagination',
        type: 'bullets',
        clickable: true,
        dynamicBullets: false,
        bulletActiveClass: 'active',
      },
    });
    this.check = true;
  }

  destroySwiper() {
    this.swiper.destroy(true, true);
    this.check = false;
  }

  static init(elem) {
    new SliderRelated(elem);
  }
}

class SlidersUI {
  static init() {
    document.querySelectorAll('.js-slider-home')
      .forEach(item => {
        Sliders.init(item);
      });
    document.querySelectorAll('.js-slider-related')
      .forEach(item => {
        SliderRelated.init(item);
      });
  }
}

document.addEventListener('DOMContentLoaded', () => {
  SlidersUI.init();
});
