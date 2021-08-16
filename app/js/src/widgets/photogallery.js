class PhotoGallery {
  constructor(node) {
    this.node = node;
    this.body = document.querySelector('body');
    this.images = this.node.querySelectorAll('.js-photogallery-image');
    this.gallery = null;
    this.galleryOverlay = null;
    this.galleryButtonClose = null;
    this.gallerySharing = null;

    this.activeIndex = null;
    this.gallerySliderContainer = null;

    this.galleryThumbnails = null;
    this.galleryThumbnailsContainer = null;
    this.galleryThumbnailsWrapper = null;
    this.galleryThumbnailsListItems = null;

    this.scrollableBarItem = null;
    this.scrollbarContainer = null;

    this.galleryInitialized = false;
    this.gallerySlider = null;
    this.currentGalleryMode = null;

    this.events();
  }

  events() {
    this.checkImageHeight();
    this.setImagesIndex();

    if (this.gallery === null) this.buildGalleryHtml();
    if (this.galleryOverlay === null) this.createOverlay();

    this.node.addEventListener('click', this.galleryHandler.bind(this));

    this.initThumbnailsImages();
    this.initGallery();
    onResize(this.initGallery.bind(this));
    this.closeGallery();
    if (this.gallery !== null) this.initSharing()
  }

  initGallery() {
    this.initGallerySlider();
    if (this.galleryThumbnails) this.initScrollBarOnScroll();
    this.gallerySlider.updateSize();
  }

  galleryHandler(e) {
    let target = e.target;

    if (target.classList.contains('js-photogallery-image')) {
      hideScrollbar();
      this.activeIndex = target.getAttribute('data-image');
      this.galleryInitialized = true;

      this.setScrollBarHeight();

      this.revealElement(this.galleryOverlay);
      this.revealElement(this.gallery);
      this.slideToCertainIndex();
    }
  }

  buildGalleryHtml() {
    this.gallery = document.createElement('section');
    this.gallery.classList.add('gallery');

    this.galleryContent = document.createElement('section');
    this.galleryContent.classList.add('gallery__content');

    this.galleryButtonClose = document.createElement('button');
    this.galleryButtonClose.setAttribute('type', 'button');
    this.galleryButtonClose.classList.add('gallery__close');

    this.galleryLogoLink = document.createElement('a');
    this.galleryLogoLink.setAttribute('href', '/');
    this.galleryLogoLink.classList.add('gallery__logo-link');

    this.galleryLogoImage = document.createElement('img');
    this.galleryLogoImage.setAttribute('src', String(this.node.dataset.imagesPath));
    this.galleryLogoImage.classList.add('gallery__logo-image');

    this.gallerySliderContainer = document.createElement('div');
    this.gallerySliderContainer.classList.add('gallery__container');
    this.gallerySliderContainer.classList.add('swiper-container');

    this.swiperWrapper = document.createElement('div');
    this.swiperWrapper.classList.add('gallery__wrapper');
    this.swiperWrapper.classList.add('swiper-wrapper');

    const swiperPagination = document.createElement('div');
    swiperPagination.classList.add('gallery__pagination');
    swiperPagination.setAttribute('data-slider', 'gallery-pagination');

    this.galleryThumbnails = document.createElement('div');
    this.galleryThumbnails.classList.add('gallery__thumbnails');
    this.galleryThumbnails.classList.add('gallery-thumbnails');

    this.galleryThumbnailsContainer = document.createElement('div');
    this.galleryThumbnailsContainer.classList.add('gallery-thumbnails__container');

    this.galleryThumbnailsWrapper = document.createElement('div');
    this.galleryThumbnailsWrapper.classList.add('gallery-thumbnails__wrapper');

    this.galleryThumbnailsList = document.createElement('ul');
    this.galleryThumbnailsList.classList.add('gallery-thumbnails__list');

    this.images.forEach((item, index) => {
      this.swiperWrapper
        .insertAdjacentHTML('beforeend',
          `<div class="gallery__slide swiper-slide">
                    <img srcset="${this.images[index].srcset}" alt="${index}">
                </div>`,
        )
      this.galleryThumbnailsList
        .insertAdjacentHTML('beforeend',
          `<li class="gallery-thumbnails__item" data-hover-index="${index + 1}" data-image-index="${this.images[index].dataset.image}">
                    <img class="gallery-thumbnails__image" src="${this.images[index].dataset.thumbs}" alt="${index}">
                </li>`,
        )
    });

    const swiperNext = document.createElement('button');
    swiperNext.setAttribute('type', 'button');
    swiperNext.classList.add('gallery__control');
    swiperNext.classList.add('swiper-button-next');
    const swiperPrev = document.createElement('button');
    swiperPrev.setAttribute('type', 'button');
    swiperPrev.classList.add('gallery__control');
    swiperPrev.classList.add('swiper-button-prev');

    this.gallerySliderContainer.appendChild(swiperNext);
    this.gallerySliderContainer.appendChild(swiperPrev);

    this.gallerySliderContainer.appendChild(this.swiperWrapper);

    this.galleryContent.appendChild(swiperPagination);
    this.galleryContent.appendChild(this.gallerySliderContainer);
    this.galleryContent.insertAdjacentHTML('beforeend',
      `
      <div class="gallery__sharing">
        <span>Share</span>
        <ul class="sharing-list js-gallery-sharing">
          <li class="sharing-list__item">
            <button class="js-gallery-share" data-gallery-social="twitter" type="button">
              <svg class="icon icon-twitter">
                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href=${this.node.dataset.spriteUrl}#twitter></use>
              </svg>
            </button>
          </li>
          <li class="sharing-list__item">
            <button class="sharing-list__icon" data-gallery-social="pinterest" type="button">
               <svg class="icon icon-pinterest">
                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href=${this.node.dataset.spriteUrl}#pinterest></use>
              </svg>
            </button>
          </li>
          <li class="sharing-list__item">
            <button class="js-gallery-share" data-gallery-social="facebook" type="button">
              <svg class="icon icon-facebook">
                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href=${this.node.dataset.spriteUrl}#facebook></use>
              </svg>
            </button>
          </li>
        </ul>
      </div>`);

    this.galleryLogoLink.appendChild(this.galleryLogoImage);
    this.gallery.appendChild(this.galleryButtonClose);
    this.gallery.appendChild(this.galleryLogoLink);
    this.gallery.appendChild(this.galleryContent);

    this.scrollbarContainer = document.createElement('div');
    this.scrollbarContainer.classList.add('gallery-thumbnails__scrollbar');
    this.scrollableBarItem = document.createElement('div');
    this.scrollableBarItem.classList.add('gallery-thumbnails__scrollableBar');

    this.scrollbarContainer.appendChild(this.scrollableBarItem);
    this.galleryThumbnails.appendChild(this.galleryThumbnailsContainer);
    this.galleryThumbnails.appendChild(this.scrollbarContainer);
    this.galleryThumbnailsContainer.appendChild(this.galleryThumbnailsWrapper);
    this.galleryThumbnailsWrapper.appendChild(this.galleryThumbnailsList);
    this.gallery.appendChild(this.galleryThumbnails);

    this.body.appendChild(this.gallery);
  }

  desktopGallerySlider() {
    return new Swiper(this.gallerySliderContainer, {
      slidesPerView: 1,
      loop: true,
      loopedSlides: this.images.length,
      effect: 'fade',
      on: {
        slideChange: this.onSlideChange.bind(this)
      },
      fadeEffect: {
        crossFade: true,
      },
      pagination: {
        el: '[data-slider=\"gallery-pagination\"]',
        type: 'fraction',
      },
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
    });
  }

  mobileGallerySlider() {
    return new Swiper(this.gallerySliderContainer, {
      centeredSlides: true,
      spaceBetween: 10,
      loop: true,
      loopedSlides: this.images.length,
      initialSlide: Number(this.activeIndex),
      on: {
        slideChange: this.onSlideChange.bind(this)
      },
      pagination: {
        el: '[data-slider=\"gallery-pagination\"]',
        type: 'fraction',
      },
      breakpoints: {
        767: {
          spaceBetween: 40,
        },
      }
    });
  }

  initGallerySlider() {
    !isMobileLayout() ?
      this.currentGalleryMode = 'desktop' :
      this.currentGalleryMode = 'mobile';

    if (this.currentGalleryMode === 'desktop') {
      if (this.gallerySlider !== null) this.gallerySlider.destroy(true, true);
      this.gallerySlider = this.desktopGallerySlider();
    } else {
      if (this.gallerySlider !== null) this.gallerySlider.destroy(true, true);
      this.gallerySlider = this.mobileGallerySlider();
    }

    this.slideToCertainIndex();
  }

  slideToCertainIndex() {
    this.gallerySlider.slideTo(Number(this.activeIndex) || 0, 0);
  }

  onSlideChange() {
    if (!this.gallerySlider) return;

    if (this.gallerySlider.realIndex !== null || undefined) this.activeIndex = this.gallerySlider.realIndex;

    if (isMobileLayout()) return;

    this.scrollElementBy(this.offsetThumbs());

    this.galleryThumbnailsListItems.forEach(item => {
      if (item.classList.contains('active')) item.classList.remove('active');
      if (Number(item.dataset.imageIndex) === this.gallerySlider.realIndex) item.classList.add('active');
    });
  }

  offsetThumbs() {
    let offset = 0;

    for (let i = 0; i < this.gallerySlider.realIndex; i++) {
      offset += this.galleryThumbnailsListItems[i].getBoundingClientRect().height + 20;
    }
    return offset;
  }

  scrollElementBy(pos) {
    this.galleryThumbnails.scrollTo({
      top: pos,
      behavior: 'smooth'
    });
  }

  initThumbnailsImages() {
    if (!this.galleryThumbnails) return;

    this.galleryThumbnailsListItems = this.galleryThumbnailsContainer.querySelectorAll('.gallery-thumbnails__item');

    this.galleryThumbnails.addEventListener('click', (e) => {
      let target = e.target;

      if (target.classList.contains('gallery-thumbnails__item')) {
        this.galleryThumbnailsListItems.forEach(item => {
          if (item.classList.contains('active')) item.classList.remove('active');
        });

        target.classList.add('active');
        this.activeIndex = Number(target.getAttribute('data-image-index'));
        this.gallerySlider.slideTo(Number(target.getAttribute('data-image-index')) || 0);
      }
    });
  }

  calcScrollBarHeight() {
    let containerHeight = this.scrollbarContainer.getBoundingClientRect().height;
    let scrollableHeight = this.galleryThumbnails.scrollHeight;
    let difference = containerHeight - scrollableHeight;
    let result = -1 * (containerHeight / difference) * 100;

    if (scrollableHeight === containerHeight) return this.scrollbarContainer.style.display = 'none';
    if (-1 * difference < containerHeight) return -1 * (difference / containerHeight) * 100;
    return result;
  }

  setScrollBarHeight() {
    if (!this.galleryThumbnails) return;
    this.scrollableBarItem.style.height = `${this.calcScrollBarHeight()}%`;
  }

  getOffsetWhileScroll() {
    let height = this.galleryThumbnails.scrollHeight;
    let visibleHeight = this.galleryThumbnails.clientHeight;
    let scrollableHeight = height - visibleHeight;
    let movingDifference = scrollableHeight - this.galleryThumbnails.scrollTop;
    let coefficient = (movingDifference / scrollableHeight * 100) - 100;
    const barHeight = (this.calcScrollBarHeight() / 100) * this.galleryThumbnails.clientHeight;
    const remains = this.galleryThumbnails.clientHeight - barHeight;

    return -1 * remains * coefficient / 100;
  }

  initScrollBarOnScroll() {
    this.galleryThumbnails.addEventListener('scroll', () => {
      this.scrollableBarItem ?
        this.scrollableBarItem.style.transform = `translateY(${this.getOffsetWhileScroll()}px)` :
        this.scrollableBarItem.style.transform = `translateY(0)`;
    });
  }

  createOverlay() {
    this.galleryOverlay = document.createElement('div');
    this.galleryOverlay.classList.add('gallery-overlay');
    this.body.appendChild(this.galleryOverlay);
  }

  closeGallery() {
    if (this.galleryOverlay) this.galleryOverlay.addEventListener('click', (e) => {
      let target = e.target;
      if (target === this.galleryOverlay) this.hideGallery();
      showScrollbar();
    });
    if (this.galleryButtonClose) this.galleryButtonClose.addEventListener('click', () => {
      this.hideGallery();
      showScrollbar();
    });
  }

  hideGallery() {
    this.gallery.classList.remove('visible');
    this.galleryOverlay.classList.remove('visible');

    this.galleryInitialized = false;
  }

  revealElement(element) {
    element.classList.add('visible');
  }

  hideElement(element) {
    element.classList.remove('visible');
  }

  setImagesIndex() {
    this.images.forEach((item, index) => {
      item.setAttribute('data-image', index);
    })
  }

  checkImageHeight() {
    function getMeta(url) {
      return new Promise((resolve, reject) => {
        let img = new Image();
        img.onload = () => resolve(img);
        img.onerror = reject;
        img.src = url;
      });
    }

    async function run(incoming_url, index, element) {
      let img = await getMeta(incoming_url);
      let w = img.width;
      let h = img.height;

      if (h > w) element.parentNode.classList.add('photogallery__item--long');
    }

    this.images.forEach((item, index) => {
      run(item.dataset.original, index, item)
    });
  }

  initSharing() {
    this.gallerySharing = this.gallery.querySelector('.js-gallery-sharing');

    this.gallerySharing.addEventListener('click', this.initSharingHandler.bind(this));
  }

  initSharingHandler(e) {
    if (e.target.closest('button')) this[e.target.closest('button').dataset.gallerySocial]()
  }

  facebook() {
    const _url = [];

    _url.push(`${encodeURIComponent(`${this.images[this.activeIndex].src}`)}`);

    this.popup(`https://www.facebook.com/sharer/sharer.php?u=${_url.join('')}`);
  }

  twitter() {
    const _url = [];

    _url.push(`${encodeURIComponent(`${this.images[this.activeIndex].src}`)}`);
    _url.push(`&text=${encodeURIComponent(this.getTwitterParam('title') || this.getOgParam('title'))}`);
    if (this.getTwitterParam('creator')) {
      _url.push(`&via=${encodeURIComponent(this.getTwitterParam('creator'))}`);
    }

    this.popup(`https://twitter.com/intent/tweet?url=${_url.join('')}`);
  }

  getOgParam(param) {
    const elem = document.querySelector(`meta[property="og:${param}"]`);

    if (elem) return elem.getAttribute('content');
  }

  pinterest() {
    const _url = [];

    _url.push(`media=${encodeURIComponent(`${this.images[this.activeIndex].src}`)}`);

    this.popup(`https://pinterest.com/pin/create/button/?${_url.join('')}`);
  }

  getTwitterParam(param) {
    const elem = document.querySelector(`meta[name="twitter:${param}"]`);

    if (elem) return elem.getAttribute('content');
  }

  popup(url) {
    window.open(url, '', 'toolbar=0,status=0,width=626,height=436');
  }

  static init(item) {
    new PhotoGallery(item);
  }
}

class PhotoGalleryUI {
  static init() {
    const gallery = document.querySelector('.js-photogallery');
    if (gallery) PhotoGallery.init(gallery);
  }
}

document.addEventListener('DOMContentLoaded', () => {
  PhotoGalleryUI.init();
})
