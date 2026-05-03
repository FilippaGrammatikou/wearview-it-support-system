function checkLogin() {
  const username = document.getElementById('username');
  const password = document.getElementById('password');

  username.classList.remove('input-error');
  password.classList.remove('input-error');

  if (!username.value.trim()) {
    username.classList.add('input-error');
  }
  if (!password.value.trim()) {
    password.classList.add('input-error');
  }

  return true;
}

function togglePasswordVisibility() {
  const pwField = document.getElementById('password');
  const toggleIcon = document.getElementById('togglePassword');

  if (pwField.type === 'password') {
    pwField.type = 'text';
    toggleIcon.classList.remove('fa-eye-slash');
    toggleIcon.classList.add('fa-eye');
  } else {
    pwField.type = 'password';
    toggleIcon.classList.remove('fa-eye');
    toggleIcon.classList.add('fa-eye-slash');
  }
}