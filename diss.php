<?php
session_start();

// Enable error reporting for debugging (remove on production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'Database.php';

$db = new Database();
$conn = $db->getConnection();

// Handle new post
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_SESSION['user']) && !empty($_POST['content'])) {
    $stmt = $conn->prepare("INSERT INTO discussion (user_id, content) VALUES (?, ?)");
    $stmt->execute([$_SESSION['user']['id'], $_POST['content']]);
    // Redirect to the same page to avoid form resubmission and blank page
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Handle delete if admin
if (!empty($_SESSION['user']) && $_SESSION['user']['is_admin'] && isset($_GET['delete'])) {
    $stmt = $conn->prepare("DELETE FROM discussion WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Fetch posts with usernames
$posts = $conn->query("SELECT discussion.id, content, users.username, discussion.created_at FROM discussion JOIN users ON discussion.user_id = users.id ORDER BY discussion.created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Discussion - Motorbike Eshop</title>
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/fontawesome.css">
  <link rel="stylesheet" href="assets/css/templatemo-cyborg-gaming.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="container mt-5">
  <div class="page-content">
    <div class="heading-section text-center mb-4">
      <h4><em>Community</em> Discussion</h4>
    </div>

    <?php if (!empty($_SESSION['user']) && !$_SESSION['user']['is_admin']): ?>
      <form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" class="mb-4">
        <div class="form-group">
          <textarea name="content" class="form-control" rows="3" placeholder="Write your discussion post..." required></textarea>
        </div>
        <button type="submit" class="btn btn-primary mt-2">Post</button>
      </form>
    <?php endif; ?>

    <?php foreach ($posts as $post): ?>
      <div class="card mb-3">
        <div class="card-body">
          <h6 class="card-subtitle mb-2 text-muted">
            Posted by <?= htmlspecialchars($post['username']) ?> on <?= date('Y-m-d H:i', strtotime($post['created_at'])) ?>
          </h6>
          <p class="card-text"><?= nl2br(htmlspecialchars($post['content'])) ?></p>
          <?php if (!empty($_SESSION['user']) && $_SESSION['user']['is_admin']): ?>
            <a href="edit-discussion.php?id=<?= $post['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
            <a href="?delete=<?= $post['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
          <?php endif; ?>
        </div>
      </div>
    <?php endforeach; ?>

  </div>
</div>

<?php include 'footer.php'; ?>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/js/custom.js"></script>
</body>
</html>
