class Validation {
  validateForm(form) {
    let inputs = form.querySelectorAll('[data-validation]');
    inputs.forEach((input) => {
      Validation.validateInput(input);
    });
  }

  isFormValid(form) {
    let valid = true;
    let inputs = form.querySelectorAll('[data-validation]');
    inputs.forEach((input) => {
      let errors = Validation.getInputErrors(input);
      if (errors.length > 0) {
        valid = false;
      }
    });
    return valid;
  }

  static validateInput(input) {
    let errors = Validation.getInputErrors(input);
    let label = input.parentNode;
    if (errors.length > 0) {
      if (!label.classList.contains('form-error')) {
        Validation.addErrorMessage(input, errors[0]);
        Validation.addErrorClass(input);
      } else {
        Validation.updateErrorMessage(input, errors[0]);
      }
    } else {
      Validation.removeErrorMessage(input);
      Validation.removeErrorClass(input);
    }
  }

  static addErrorClass(input) {
    let label = input.parentNode;
    label.classList.add('form-error');
  }

  static addErrorMessage(input, error) {
    let label = input.parentNode;
    let message = document.createElement('span');
    message.className = 'form-message';
    message.innerHTML = error;
    label.append(message);
  }

  static updateErrorMessage(input, error) {
    let label = input.parentNode;
    label.getElementsByClassName('form-message')[0].innerHTML = error;
  }

  static removeErrorMessage(input) {
    let label = input.parentNode;
    if (label.classList.contains('form-error')) {
      let message = label.getElementsByClassName('form-message')[0];
      label.removeChild(message);
    }
  }

  static removeErrorClass(input) {
    let label = input.parentNode;
    if (label.classList.contains('form-error')) {
      label.classList.remove('form-error');
    }
  }

  static getInputErrors(input) {
    let errors = [];
    let rules = input.getAttribute('data-validation').split(',');
    rules.forEach((rule) => {
      let error = Validation[rule](input);
      if (error) {
        errors.push(error);
      }
    });
    return errors;
  }

  static isNotEmpty(input) {
    if (!input.value || input.value.trim() === '') return 'Invalid Format';
  }

  static isValidEmail(input) {
    let regex = RegExp(
      /^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/);
    if (!regex.test(input.value)) {
      return 'Invalid Format';
    }
  }

  static init() {
    let inputs = document.querySelectorAll('[data-validation]');
    inputs.forEach((input) => {
      let label = input.parentNode;

      input.addEventListener('change', () => {
        if (label.classList.contains('form-error')) {
          Validation.validateInput(input);
        }
      });

      input.addEventListener('input', () => {
        if (label.classList.contains('form-error')) {
          Validation.validateInput(input);
        }
      });
    });
  }
}

Validation.init();

window.Validation = new Validation;
