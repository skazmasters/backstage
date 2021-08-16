class Header {
  constructor(nodeElement) {
    this.nodeElement = nodeElement;
    this.addEvents();
  }

  addEvents() {
    onScroll(this.headerScroll.bind(this));
  }

  setAsFixed() {
    this.nodeElement.classList.add('fixed');
  }

  setAsNotFixed() {
    this.nodeElement.classList.remove('fixed');
  }

  headerScroll() {
    const scrollTop = getScrollPos();
    if (scrollTop > 0) {
      this.setAsFixed();
    } else {
      this.setAsNotFixed();
    }
  }

  static init(elem) {
    new Header(elem);
  }
}

class HeaderUI {
  static init() {
    const header = document.querySelector('.js-header');
    if (header) Header.init(header);
  }
}

document.addEventListener('DOMContentLoaded', () => {
  HeaderUI.init();
});
