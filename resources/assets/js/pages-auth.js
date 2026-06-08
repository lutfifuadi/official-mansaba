/**
 *  Pages Authentication
 */
'use strict';

document.addEventListener('DOMContentLoaded', function () {
  (() => {
    const formAuthentication = document.querySelector('#formAuthentication');

    // Form validation for Add new record
    if (formAuthentication && typeof FormValidation !== 'undefined') {
      FormValidation.formValidation(formAuthentication, {
        fields: {
          username: {
            validators: {
              notEmpty: {
                message: 'Please enter username'
              },
              stringLength: {
                min: 6,
                message: 'Username must be more than 6 characters'
              }
            }
          },
          email: {
            validators: {
              notEmpty: {
                message: 'Please enter your email'
              },
              emailAddress: {
                message: 'Please enter a valid email address'
              }
            }
          },
          'email-username': {
            validators: {
              notEmpty: {
                message: 'Please enter email / username'
              },
              stringLength: {
                min: 6,
                message: 'Username must be more than 6 characters'
              }
            }
          },
          password: {
            validators: {
              notEmpty: {
                message: 'Please enter your password'
              },
              stringLength: {
                min: 6,
                message: 'Password must be more than 6 characters'
              }
            }
          },
          'confirm-password': {
            validators: {
              notEmpty: {
                message: 'Please confirm password'
              },
              identical: {
                compare: () => formAuthentication.querySelector('[name="password"]').value,
                message: 'The password and its confirmation do not match'
              },
              stringLength: {
                min: 6,
                message: 'Password must be more than 6 characters'
              }
            }
          },
          terms: {
            validators: {
              notEmpty: {
                message: 'Please agree to terms & conditions'
              }
            }
          }
        },
        plugins: {
          trigger: new FormValidation.plugins.Trigger(),
          bootstrap5: new FormValidation.plugins.Bootstrap5({
            eleValidClass: '',
            rowSelector: '.mb-4'
          }),
          submitButton: new FormValidation.plugins.SubmitButton(),
          defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
          autoFocus: new FormValidation.plugins.AutoFocus()
        },
        init: instance => {
          instance.on('plugins.message.placed', e => {
            const parent = e.element.parentElement;
            if (parent && parent.classList.contains('input-group')) {
              parent.insertAdjacentElement('afterend', e.messageElement);
            }
          });
        }
      });
    }

    // Password Show/Hide Toggle
    const togglePassword = document.querySelector('#toggle-password');
    if (togglePassword) {
      togglePassword.addEventListener('click', function () {
        const passwordInput = document.querySelector('#password');
        const icon = this.querySelector('i');
        if (passwordInput && icon) {
          const isPassword = passwordInput.type === 'password';
          passwordInput.type = isPassword ? 'text' : 'password';
          icon.classList.toggle('tabler-eye-off');
          icon.classList.toggle('tabler-eye');
        }
      });
    }

    // Login Button Loading State
    const loginForm = document.querySelector('#formAuthentication');
    if (loginForm) {
      loginForm.addEventListener('submit', function () {
        const btn = document.querySelector('#btn-login');
        if (btn) {
          btn.disabled = true;
          const textEl = document.querySelector('#btn-login-text');
          const loadingEl = document.querySelector('#btn-login-loading');
          if (textEl) textEl.classList.add('d-none');
          if (loadingEl) loadingEl.classList.remove('d-none');
        }
      });
    }

    // Two Steps Verification for numeral input mask
    const numeralMaskElements = document.querySelectorAll('.numeral-mask');

    // Format function for numeral mask
    const formatNumeral = value => value.replace(/\D/g, ''); // Only keep digits

    if (numeralMaskElements.length > 0) {
      numeralMaskElements.forEach(numeralMaskEl => {
        numeralMaskEl.addEventListener('input', event => {
          numeralMaskEl.value = formatNumeral(event.target.value);
        });
      });
    }
  })();
});
