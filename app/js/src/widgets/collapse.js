class Collapse {
  constructor(item) {
    this.nodeElement = item;
    this.addEvents();
  }

  addEvents() {
    const toggleButton = this.nodeElement.querySelector('.collapse-btn');
    const content = this.nodeElement.querySelector('.collapse-content');

    toggleButton.addEventListener('click', () => {
      if (this.nodeElement.classList.contains('active')) {
        Collapse.collapse(content);
        Collapse.removeActive(this.nodeElement);
      } else {
        Collapse.setActive(this.nodeElement);
        Collapse.expand(content);
      }
    });
  }

  static collapse(elem) {
    const height = {
      from: elem.scrollHeight,
      to: 0,
    };

    Collapse.animate(elem, height);
  }

  static expand(elem) {
    const height = {
      from: 0,
      to: elem.scrollHeight,
    };

    Collapse.animate(elem, height);
  }

  static animate(elem, height) {
    const handler = ({target, currentTarget}) => {
      if (target !== currentTarget) return false;

      elem.removeEventListener(endEvents.transition, handler);
      elem.classList.remove('animate');
      elem.style.height = '';
    };
    elem.addEventListener(endEvents.transition, handler);

    elem.classList.add('animate');
    elem.style.height = `${height.from}px`;
    raf2x(() => {
      elem.style.height = `${height.to}px`;
    });
  }

  static setActive(elem) {
    elem.classList.add('active');
  }

  static removeActive(elem) {
    elem.classList.remove('active');
  }

  static init(elem) {
    new Collapse(elem);
  }
}

class CollapseMobile {
  constructor(item) {
    this.nodeElement = item;
    this.button = this.nodeElement.querySelector('.collapse-btn');
    this.content = this.nodeElement.querySelector('.collapse-content');
    this.inited = false;

    this.events();
    onResize(this.events.bind(this))
  }

  events() {
    if (!isMobileLayout()) {
      if (!this.nodeElement.classList.contains('active')) this.nodeElement.classList.add('active');
      this.button.removeEventListener('click', this.handler);
      return;
    }

    if (isMobileLayout() && !this.inited) this.button.addEventListener('click', this.handler.bind(this));
  }

  handler() {
    if(!isMobileLayout()) return;

    if (this.nodeElement.classList.contains('active')) {
      Collapse.collapse(this.content);
      Collapse.removeActive(this.nodeElement);
    } else {
      Collapse.setActive(this.nodeElement);
      Collapse.expand(this.content);
    }

    this.inited = true;
  }

  static init(elem) {
    new CollapseMobile(elem);
  }
}

class CollapseUI {
  static init() {
    const collapseItems = document.querySelectorAll('.js-collapse');
    collapseItems.forEach(item => {
      Collapse.init(item);
    });
    const collapseMobileItems = document.querySelectorAll('.js-mobile-collapse');
    collapseMobileItems.forEach(item => {
      CollapseMobile.init(item);
    });
  }
}

document.addEventListener('DOMContentLoaded', () => {
  CollapseUI.init();
});

window.Collapse = Collapse;
window.CollapseUI = CollapseUI;
