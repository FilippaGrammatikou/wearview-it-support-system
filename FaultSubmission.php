<?php

session_start();

if (($_SESSION['role'] ?? '') !== 'staff') {
    header('Location: LoginPage.php');
    exit;
}

require_once __DIR__ . '/lib/wvdb.php';

$errors      = [];
$fullname    = '';
$email       = '';
$faultTitle  = '';
$location    = '';
$description = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname    = trim($_POST['fullname']    ?? '');
    $email       = trim($_POST['email']       ?? '');
    $faultTitle  = trim($_POST['faultTitle']  ?? '');
    $location    = trim($_POST['location']    ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($fullname === '') {
        $errors['fullname'] = true;
    }

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = true;
    }

    if ($faultTitle === '') {
        $errors['faultTitle'] = true;
    }

    if ($location === '') {
        $errors['location'] = true;
    }

    $words = preg_split('/\s+/', $description, -1, PREG_SPLIT_NO_EMPTY);

    if ($description === '' || count($words) < 5 || count($words) > 200) {
        $errors['description'] = true;
    }

    if (empty($errors)) {
        try {
            $db = Database::getConnection();

            $stmt = $db->prepare(<<<'SQL'
                INSERT INTO issues
                  (reporter_name, reporter_email, fault_title, location, description)
                VALUES
                  (:name, :email, :title, :loc, :desc)
            SQL
            );

            $stmt->execute([
                ':name'  => $fullname,
                ':email' => $email,
                ':title' => $faultTitle,
                ':loc'   => $location,
                ':desc'  => $description,
            ]);

            header('Location: SubmissionConfirmation.html');
            exit;
        } catch (PDOException $e) {
            error_log('[FAULT-SUBMIT ERROR] ' . $e->getMessage());
            $errors['general'] = true;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Submit Fault</title>

  <link
    href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@400;500;600&display=swap"
    rel="stylesheet"
  >

  <link
    href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;700&family=Libre+Baskerville:wght@400;700&display=swap"
    rel="stylesheet"
  >

  <link rel="stylesheet" href="FaultSubmission.css">
</head>

<body>
  <div class="overlay">
    <header>
      <div class="logo">
        <img src="assets/Wearview_Academy_Logo_Cleaned_Transparent.png" alt="WearView Logo">

        <div>
          <h1 style="margin:0;font-size:2em">WEARVIEW ACADEMY</h1>
          <p style="margin:0;font-size:1.5em">IT TECHNICAL SUPPORT SYSTEM</p>
        </div>
      </div>
    </header>

    <main class="form-container">
      <div class="title">Submit Fault</div>

      <?php if (!empty($errors['general'])): ?>
        <div class="error-message">
          A server error occurred. Please try again later.
        </div>
      <?php endif; ?>

      <form id="faultForm" method="post" action="FaultSubmission.php" novalidate>
        <label for="fullname">Full Name</label>

        <input
          type="text"
          id="fullname"
          name="fullname"
          placeholder="Enter full name (min. 2 words)"
          value="<?= isset($errors['fullname']) ? '' : htmlspecialchars($fullname) ?>"
          class="<?= isset($errors['fullname']) ? 'error' : '' ?>"
        >

        <label for="email">Email Address</label>

        <input
          type="email"
          id="email"
          name="email"
          placeholder="(e.g. name@example.com)"
          value="<?= isset($errors['email']) ? '' : htmlspecialchars($email) ?>"
          class="<?= isset($errors['email']) ? 'error' : '' ?>"
        >

        <label for="faultTitle">Fault Title</label>

        <input
          type="text"
          id="faultTitle"
          name="faultTitle"
          placeholder="Enter a fault title"
          value="<?= isset($errors['faultTitle']) ? '' : htmlspecialchars($faultTitle) ?>"
          class="<?= isset($errors['faultTitle']) ? 'error' : '' ?>"
        >

        <label for="location">Location of Fault</label>

        <input
          type="text"
          id="location"
          name="location"
          placeholder="Enter location of fault"
          value="<?= isset($errors['location']) ? '' : htmlspecialchars($location) ?>"
          class="<?= isset($errors['location']) ? 'error' : '' ?>"
        >

        <label for="description">Fault Description</label>

        <div class="textarea-wrapper">
          <textarea
            id="description"
            name="description"
            placeholder="Describe the issue (min. 5 words)…"
            class="<?= isset($errors['description']) ? 'error' : '' ?>"
          ><?= isset($errors['description']) ? '' : htmlspecialchars($description) ?></textarea>

          <div id="wordCount" class="word-count">200 words remaining</div>
        </div>

        <button type="submit" class="submit-button">REPORT ISSUE</button>
      </form>

      <div class="button-group">
        <button
          type="button"
          class="logout-button"
          onclick="location.href='LoginPage.php?logout=1'"
        >LOG OUT</button>
      </div>
    </main>

    <footer>
      <p class="footer-text">WearView IT Support System · Internal Use Only</p>
    </footer>
  </div>

  <script src="FaultSubmission.js"></script>
</body>
</html>