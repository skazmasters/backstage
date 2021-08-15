class Sharing {
  constructor(node) {
    this.node = node;

    this.events();
  }

  events() {
    this.node.addEventListener('click', this.onClick.bind(this), true);
  }

  facebook() {
    const _url = [];

    _url.push(`${encodeURIComponent(`${document.location.href}`)}`);
    _url.push(`&p[title]=${encodeURIComponent(this.getOgParam('title'))}`);
    _url.push(`&p[images][0]=${encodeURIComponent(this.getOgParam('image'))}`);
    _url.push(`&p[summary]=${encodeURIComponent(this.getOgParam('description'))}`);

    this.popup(`https://www.facebook.com/sharer/sharer.php?u=${_url.join('')}`);
  }

  twitter() {
    const _url = [];

    _url.push(`${encodeURIComponent(`${document.location.href}`)}`);
    _url.push(`&text=${encodeURIComponent(this.getTwitterParam('title') || this.getOgParam('title'))}`);
    if (this.getTwitterParam('creator')) {
      _url.push(`&via=${encodeURIComponent(this.getTwitterParam('creator'))}`);
    }

    this.popup(`https://twitter.com/intent/tweet?url=${_url.join('')}`);
  }

  pinterest() {
    const _url = [];

    _url.push(`url=${encodeURIComponent(`${document.location.href}`)}`);
    _url.push(`&description=${encodeURIComponent(this.getOgParam('description'))}`);
    _url.push(`&media=${encodeURIComponent(this.getOgParam('image'))}`);

    this.popup(`https://pinterest.com/pin/create/button/?${_url.join('')}`);
  }

  getOgParam(param) {
    const elem = document.querySelector(`meta[property="og:${param}"]`);

    if (elem) return elem.getAttribute('content');
  }

  getTwitterParam(param) {
    const elem = document.querySelector(`meta[name="twitter:${param}"]`);

    if (elem) return elem.getAttribute('content');
  }

  popup(url) {
    window.open(url, '', 'toolbar=0,status=0,width=626,height=436');
  }

  onClick(e) {
    let action;
    if (e.target.closest('button')) action = e.target.closest('button').dataset.action;

    if (action) this[action]();
  }

  static init(elem) {
    new Sharing(elem);
  }
}

class SharingUI {
  static init() {
    document.querySelectorAll('.js-sharing-section')
      .forEach(item => Sharing.init(item))
  }
}

document.addEventListener('DOMContentLoaded', () => {
  SharingUI.init();
});
