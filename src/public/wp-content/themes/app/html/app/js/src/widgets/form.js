class Form {
  constructor(form) {
    this.form = form;
    this.busy = false;
    this.mode = 'submit';
    this.action = this.form.dataset.apiUrl;
    this.inputs = this.form.querySelectorAll('input');
    this.textarea = this.form.querySelector('textarea');
    this.submitButton = this.form.querySelector('button[type=\"submit\"]');
    this.section = document.querySelector('.js-section-contacts');
    this.grid = this.section.querySelector('.js-section-contacts__grid');
    this.title = this.section.querySelector('.js-section-contacts__title');
    this.buttonBack = this.section.querySelector('button[data-success-button]');
    this.previousTitleText = this.title.textContent;

    this.addEvents();
  }

  addEvents() {
    this.form.addEventListener('submit', e => {
      if (this.busy) return e.preventDefault();
      this.busy = true;

      Validation.validateForm(this.form);

      if (Validation.isFormValid(this.form)) {
        this.loading();

        let data = {};
        const queryString = new FormData(this.form);

        for (let [key, value] of queryString.entries()) {
          if (value !== '') {
            data[`${key}`] = `${value}`;
          }
        }

        fetch(this.action, {
          method: 'POST',
          mode: 'cors',
          headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
          },
          body: JSON.stringify(data),
        }).then(res => {

          this.busy = false;
          this.toSuccess();

          setTimeout(() => {
            this.addSuccessState();
            this.form.reset();
          }, 2600);
        });
      } else {
        this.busy = false;
      }

      e.preventDefault();
    });

    this.buttonBack.addEventListener('click', this.handlerBackToForm.bind(this));
  }

  addSuccessState() {
    startScrollTo(document.documentElement);

    if (this.mode === 'submit') {
      this.title.textContent = this.title.dataset.successTitle;

      this.section.classList.add('_success');
      this.grid.classList.add('grid-contacts--success');
      this.section.classList.remove('_loading');
      this.section.classList.remove('_hide');
    } else {
      this.title.textContent = this.previousTitleText;

      this.section.classList.add('_success');
      this.grid.classList.remove('grid-contacts--success');
      this.section.classList.remove('_loading');
      this.section.classList.remove('_hide');
      this.mode = 'submit';
    }
  }

  handlerBackToForm() {
    this.mode = 'back';
    const backToFormPromise = new Promise((resolve, reject) => {
      resolve(this.loading());
    });

    backToFormPromise
      .then(this.toSuccess())
      .then(
        setTimeout(() => {
          this.addSuccessState();
          this.form.reset();
        }, 2600)
      )
  }

  loading() {
    setTimeout(() => {
      this.section.classList.add('_loading');
      if (this.mode === 'submit') {
        this.submitButton.disabled = true;
        this.inputs.forEach(item => { item.disabled = true; })
        this.textarea.disabled = true;
        this.buttonBack.disabled = false;
      } else {
        this.buttonBack.disabled = true;
        this.submitButton.disabled = false;
        this.inputs.forEach(item => { item.disabled = false; })
        this.textarea.disabled = false;
      }
    }, 300);
  }

  toSuccess() {
    setTimeout(() => {
      this.section.classList.add('_hide');
    }, 1800);
  }

  static init(element) {
    new Form(element);
  }
}

class SearchForm {
  constructor(node) {
    this.node = node;
    this.resetBtn = this.node.querySelector('[type="reset"]');
    this.input = this.node.querySelector('[type="text"]');

    this.events();
  }

  events() {
    this.resetBtn.addEventListener('click', () => {
      this.input.getAttribute('value') !== '' ? this.input.setAttribute('value', '') : null;
    });
  }

  static init(elem) {
    new SearchForm(elem);
  }
}

class FormUI {
  static init() {
    const contactForm = document.querySelector('.js-contacts-form');
    const searchForm = document.querySelector('.js-search-form');
    if (contactForm) Form.init(contactForm);
    if (searchForm) SearchForm.init(searchForm);
  }
}

document.addEventListener('DOMContentLoaded', () => {
  FormUI.init();
});

window.Form = Form;
window.FormUI = FormUI;

