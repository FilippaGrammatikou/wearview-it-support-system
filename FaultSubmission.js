document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('faultForm');
  const fullname = document.getElementById('fullname');
  const email = document.getElementById('email');
  const faultTitle = document.getElementById('faultTitle');
  const locationInput = document.getElementById('location');
  const description = document.getElementById('description');
  const wordCount = document.getElementById('wordCount');

  const MIN_DESCRIPTION_WORDS = 5;
  const MAX_DESCRIPTION_WORDS = 200;
  const WARNING_DESCRIPTION_WORDS = 195;

  const defaultPlaceholders = new Map([
    [fullname, 'Enter full name (min. 2 words)'],
    [email, '(e.g. name@example.com)'],
    [faultTitle, 'Enter a fault title'],
    [locationInput, 'Enter location of fault'],
    [description, 'Describe the issue (min. 5 words)…']
  ]);

  function getWords(value) {
    return value.trim().split(/\s+/).filter(Boolean);
  }

  function updateWordCount() {
    const words = getWords(description.value);
    const remaining = MAX_DESCRIPTION_WORDS - words.length;

    wordCount.textContent =
      remaining >= 0
        ? `${remaining} words remaining`
        : `${remaining} words`;

    wordCount.classList.toggle(
      'word-count-warning',
      words.length >= WARNING_DESCRIPTION_WORDS && words.length <= MAX_DESCRIPTION_WORDS
    );

    wordCount.classList.toggle(
      'word-count-over-limit',
      words.length > MAX_DESCRIPTION_WORDS
    );
  }

  function markEmpty(input, placeholderMessage) {
    input.classList.add('error');
    input.placeholder = placeholderMessage;
  }

  function showInlineError(input, message) {
    input.classList.add('error', 'has-inline-error');

    const existingError = getExistingInlineError(input);
    if (existingError) {
      existingError.textContent = message;
      return;
    }

    const msg = document.createElement('div');
    msg.className = 'inline-error';
    msg.textContent = message;

    if (input === description) {
      const wrapper = input.closest('.textarea-wrapper');
      wrapper.classList.add('has-inline-error');
      wrapper.insertAdjacentElement('afterend', msg);
      return;
    }

    input.insertAdjacentElement('afterend', msg);
  }

  function getExistingInlineError(input) {
    if (input === description) {
      const wrapper = input.closest('.textarea-wrapper');
      const next = wrapper.nextElementSibling;

      return next && next.classList.contains('inline-error')
        ? next
        : null;
    }

    const next = input.nextElementSibling;

    return next && next.classList.contains('inline-error')
      ? next
      : null;
  }

  function clearErrors() {
    form.querySelectorAll('.error').forEach(el => {
      el.classList.remove('error');
    });

    form.querySelectorAll('.has-inline-error').forEach(el => {
      el.classList.remove('has-inline-error');
    });

    form.querySelectorAll('.inline-error').forEach(el => {
      el.remove();
    });

    defaultPlaceholders.forEach((placeholder, input) => {
      input.placeholder = placeholder;
    });
  }

  description.addEventListener('input', updateWordCount);
  updateWordCount();

  form.addEventListener('submit', (e) => {
    clearErrors();

    let valid = true;

    const nameValue = fullname.value.trim();
    const emailValue = email.value.trim();
    const faultTitleValue = faultTitle.value.trim();
    const locationValue = locationInput.value.trim();
    const descriptionValue = description.value.trim();
    const descriptionWords = getWords(descriptionValue);

    if (nameValue === '') {
      markEmpty(fullname, 'Enter full name (min. 2 words)');
      valid = false;
    } else if (getWords(nameValue).length < 2) {
      showInlineError(fullname, 'Please enter at least two words for your full name.');
      valid = false;
    }

    if (emailValue === '') {
      markEmpty(email, '(e.g. name@example.com)');
      valid = false;
    } else if (!email.checkValidity()) {
      showInlineError(email, 'Please enter a valid email address.');
      valid = false;
    }

    if (faultTitleValue === '') {
      markEmpty(faultTitle, 'Enter a fault title');
      valid = false;
    }

    if (locationValue === '') {
      markEmpty(locationInput, 'Enter location of fault');
      valid = false;
    }

    if (descriptionValue === '') {
      markEmpty(description, 'Describe the issue (min. 5 words)…');
      valid = false;
    } else if (descriptionWords.length < MIN_DESCRIPTION_WORDS) {
      showInlineError(description, 'Description must contain at least 5 words.');
      valid = false;
    } else if (descriptionWords.length > MAX_DESCRIPTION_WORDS) {
      showInlineError(description, 'Description cannot exceed 200 words.');
      valid = false;
    }

    updateWordCount();

    if (!valid) {
      e.preventDefault();
    }
  });
});