function validateEmail(email) {
  const re = /^\d{11}$/;
  return re.test(email);
}

function validatePhoneNumber(phoneNumber) {
  const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return re.test(phoneNumber);
}

const form = document.querySelector('#registerForm');
const emailInput = form.querySelector('#email');
const emailErrorMessage = form.querySelector('#email-error-message');
const phoneNumberInput = form.querySelector('#phoneNumber');
const phoneNumberErrorMessage = form.querySelector('#phone-number-error-message');

form.addEventListener('submit', (event) => {
  event.preventDefault();
  if (validateEmail(emailInput.value)) {
    form.submit();
  } else {
    emailErrorMessage.style.display = 'inline';
  }
  if (validatePhoneNumber(phoneNumberInput.value)) {
    form.submit();
  } else {
    phoneNumberErrorMessage.style.display = 'inline';
  }
});
