<?php

session_start();

if (isset($_GET['logout'])) {
    $_SESSION = [];
    session_destroy();

    header('Location: LoginPage.php');
    exit;
}

require_once __DIR__ . '/lib/wvdb.php';

$username = '';

$errors = [
    'username'    => '',
    'password'    => '',
    'credentials' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '') {
        $errors['username'] = 'Username required';
    }

    if ($password === '') {
        $errors['password'] = 'Password required';
    }

    if ($errors['username'] === '' && $errors['password'] === '') {
        try {
            $db = Database::getConnection();

            $stmt = $db->prepare(
                'SELECT password, role
                   FROM users
                  WHERE username = :username'
            );

            $stmt->execute([
                ':username' => $username,
            ]);

            $user = $stmt->fetch();

            if ($user && $password === $user['password']) {
                session_regenerate_id(true);

                $_SESSION['username'] = $username;
                $_SESSION['role'] = $user['role'];

                if ($user['role'] === 'staff') {
                    header('Location: FaultSubmission.php');
                    exit;
                }

                if ($user['role'] === 'tech') {
                    header('Location: TechnicianTab.php');
                    exit;
                }

                $errors['credentials'] = 'Invalid user role';
            } else {
                $errors['credentials'] = 'Incorrect login details';
            }
        } catch (PDOException $e) {
            error_log('[LOGIN ERROR] ' . $e->getMessage());
            $errors['credentials'] = 'Server error, please try again later';
        }
    }
}
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>WearView IT Support Login</title>

  <link
    href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@400;500;600&display=swap"
    rel="stylesheet"
  >

  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
  >

  <link
    href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;700&family=Libre+Baskerville:wght@400;700&display=swap"
    rel="stylesheet"
  >

  <link rel="stylesheet" href="LoginPage.css">
</head>

<body>
  <div class="overlay">
    <header>
      <div class="logo">
        <img src="assets/Wearview_Academy_Logo_Cleaned_Transparent.png" alt="WearView Logo">

        <div>
          <h1 style="margin:0;font-size:2em;">WEARVIEW ACADEMY</h1>
          <p style="margin:0;font-size:1.5em;">IT TECHNICAL SUPPORT SYSTEM</p>
        </div>
      </div>
    </header>

    <main class="form-container">
      <div class="title">Login</div>

      <form method="post" action="LoginPage.php" onsubmit="return checkLogin()" novalidate>
        <label for="username">Username</label>

        <input
          type="text"
          id="username"
          name="username"
          value="<?= htmlspecialchars($username) ?>"
          placeholder="<?= htmlspecialchars($errors['username'] ?: 'Enter username') ?>"
          class="<?= $errors['username'] ? 'input-error' : '' ?>"
        >

        <label for="password">Password</label>

        <div class="password-wrapper">
          <input
            type="password"
            id="password"
            name="password"
            placeholder="<?= htmlspecialchars($errors['password'] ?: 'Enter password') ?>"
            class="<?= ($errors['password'] || $errors['credentials']) ? 'input-error' : '' ?>"
          >

          <i
            id="togglePassword"
            class="fa-solid fa-eye-slash toggle-password"
            onclick="togglePasswordVisibility()"
          ></i>
        </div>

        <div class="credentials-error-line <?= $errors['credentials'] ? '' : 'credentials-error-line--hidden' ?>">
          <?= htmlspecialchars($errors['credentials']) ?>
        </div>

        <button type="submit">NEXT</button>
      </form>
    </main>

    <footer>
      <p class="footer-text">WearView IT Support System &bull; Internal Use Only</p>
    </footer>
  </div>

  <script src="LoginPage.js"></script>
</body>
</html>