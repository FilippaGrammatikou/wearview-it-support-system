<?php

session_start();

if (($_SESSION['role'] ?? '') !== 'tech') {
    header('Location: LoginPage.php');
    exit;
}

require_once __DIR__ . '/lib/wvdb.php';

$db      = Database::getConnection();
$allowed = ['incomplete', 'complete'];
$view    = $_GET['view'] ?? 'incomplete';

if (!in_array($view, $allowed, true)) {
    $view = 'incomplete';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jobId  = filter_input(INPUT_POST, 'job_id', FILTER_VALIDATE_INT);
    $action = $_POST['action'] ?? '';

    if ($jobId) {
        try {
            if ($action === 'complete') {
                $stmt = $db->prepare(
                    'UPDATE issues
                        SET status     = :newStatus,
                            updated_at = NOW()
                      WHERE id         = :id'
                );

                $stmt->execute([
                    ':newStatus' => 'complete',
                    ':id'        => $jobId,
                ]);

                header('Location: TechnicianTab.php?view=incomplete');
                exit;
            }

            if ($action === 'delete') {
                $stmt = $db->prepare(
                    'DELETE FROM issues
                      WHERE id = :id'
                );

                $stmt->execute([
                    ':id' => $jobId,
                ]);

                header('Location: TechnicianTab.php?view=complete');
                exit;
            }
        } catch (PDOException $e) {
            error_log('[TECHNICIAN ACTION ERROR] ' . $e->getMessage());
        }
    }
}

try {
    $stmt = $db->prepare(
        'SELECT id, reporter_name, reporter_email,
                location, fault_title, description, created_at
           FROM issues
          WHERE status = :status
       ORDER BY created_at DESC'
    );

    $stmt->execute([
        ':status' => $view,
    ]);

    $jobs = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log('[TECHNICIAN FETCH ERROR] ' . $e->getMessage());
    $jobs = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Technician Dashboard</title>

  <link
    href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@400;500;600&display=swap"
    rel="stylesheet"
  >

  <link
    href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;700&family=Libre+Baskerville:wght@400;700&display=swap"
    rel="stylesheet"
  >

  <link rel="stylesheet" href="TechnicianTab.css">
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

    <main class="dashboard-container">
      <form method="get" class="tab-buttons">
        <button
          type="submit"
          name="view"
          value="incomplete"
          class="<?= $view === 'incomplete' ? 'active' : '' ?>"
        >
          Incomplete Jobs
        </button>

        <button
          type="submit"
          name="view"
          value="complete"
          class="<?= $view === 'complete' ? 'active' : '' ?>"
        >
          Complete Jobs
        </button>
      </form>

      <div class="job-list">
        <?php if (empty($jobs)): ?>
          <p>No <?= htmlspecialchars($view) ?> jobs found.</p>
        <?php else: ?>
          <?php foreach ($jobs as $job): ?>
            <div class="job-card">
              <h3><?= htmlspecialchars($job['fault_title']) ?></h3>

              <p>
                <strong>Reported By:</strong>
                <?= htmlspecialchars($job['reporter_name']) ?>
              </p>

              <p>
                <strong>Email:</strong>
                <?= htmlspecialchars($job['reporter_email']) ?>
              </p>

              <p>
                <strong>Location:</strong>
                <?= htmlspecialchars($job['location']) ?>
              </p>

              <p>
                <strong>Description:</strong><br>
                <?= nl2br(htmlspecialchars($job['description'])) ?>
              </p>

              <p>
                <strong>Submitted:</strong>
                <?= date('d-m-Y', strtotime($job['created_at'])) ?>
              </p>

              <?php if ($view === 'incomplete'): ?>
                <form
                  method="post"
                  action="TechnicianTab.php?view=incomplete"
                  class="inline-form"
                >
                  <input type="hidden" name="job_id" value="<?= htmlspecialchars((string) $job['id']) ?>">
                  <input type="hidden" name="action" value="complete">

                  <button type="submit">Mark Complete</button>
                </form>
              <?php else: ?>
                <button
                  type="button"
                  class="delete-btn"
                  data-id="<?= htmlspecialchars((string) $job['id']) ?>"
                >
                  Delete
                </button>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>

      <button
        type="button"
        class="logout-button"
        onclick="location.href='LoginPage.php?logout=1'"
      >
        LOG OUT
      </button>
    </main>

    <footer>
      <p class="footer-text">WearView IT Support System &bull; Internal Use Only</p>
    </footer>
  </div>

  <div id="confirmModal" class="modal">
    <div class="modal-content">
      <p>Are you sure you want to delete the selected job?</p>

      <div class="modal-buttons">
        <button id="confirmYes">Yes</button>
        <button id="confirmNo">Cancel</button>
      </div>
    </div>
  </div>

  <script src="TechnicianTab.js"></script>
</body>
</html>