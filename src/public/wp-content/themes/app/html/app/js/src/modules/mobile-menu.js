class MenuToggler {
  constructor(nodeElement) {
    this.nodeElement = nodeElement;
    this.menu = document.querySelector('.js-menu-container');
    this.content = document.querySelector('.js-menu-content');
    this.addEvents();
  }

  addEvents() {
    this.insertFooterElement();
    this.toggleMenu();
    this.update();
    onResize(this.update.bind(this));
    onScroll(this.update.bind(this));
  }

  toggleMenu() {
    this.nodeElement.addEventListener('click', () => {
      if (this.nodeElement.classList.contains('opened')) {
        this.hideMenu(this.menu);
      } else {
        this.showMenu(this.menu);
      }
    });
  }

  update() {
    this.setHeight(this.content);
  }

  setHeight() {
    if (Layout.isTabletLayout() === false) return;
    this.content.style.maxHeight = `${document.documentElement.clientHeight - 60}px`;
  }

  showMenu(elem) {
    MenuToggler.setOpened(elem);
    MenuToggler.setOpened(this.nodeElement);
    Collapse.expand(elem);
    hideScrollbar();
  }

  hideMenu(elem) {
    Collapse.collapse(elem);
    MenuToggler.removeOpened(elem);
    MenuToggler.removeOpened(this.nodeElement);
    showScrollbar();
  }

  insertFooterElement() {
    const headerBottom = document.createElement('div');
    headerBottom.classList.add('footer__content');
    headerBottom.classList.add('_smallTablet-visible');

    let clonedNode = document.querySelector('.js-footer-bottom').cloneNode(true);
    headerBottom.append(clonedNode);
    this.content.insertAdjacentElement('beforeend', headerBottom);
  }

  static setOpened(elem) {
    elem.classList.add('opened')
  }

  static removeOpened(elem) {
    elem.classList.remove('opened');
  }

  static init(elem) {
    new MenuToggler(elem);
  }
}

class MenuTogglerUI {
  static init() {
    const element = document.querySelector('.js-mobile-menu');
    if (element) MenuToggler.init(element);
  }
}

document.addEventListener('DOMContentLoaded', () => {
  MenuTogglerUI.init();
});
