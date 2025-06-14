<?php
session_start();

// Enable error reporting (remove on production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'Database.php';

$db = new Database();
$conn = $db->getConnection();

// Handle new post submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_SESSION['user'])) {
    if (isset($_POST['content']) && empty($_POST['discussion_id'])) {
        // New discussion post (no discussion_id set)
        $stmt = $conn->prepare("INSERT INTO discussion (user_id, content) VALUES (?, ?)");
        $stmt->execute([$_SESSION['user']['id'], $_POST['content']]);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } elseif (isset($_POST['content']) && !empty($_POST['discussion_id'])) {
        // New comment on a post
        $stmt = $conn->prepare("INSERT INTO comments (discussion_id, user_id, content) VALUES (?, ?, ?)");
        $stmt->execute([$_POST['discussion_id'], $_SESSION['user']['id'], $_POST['content']]);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Handle delete if admin (for posts)
if (!empty($_SESSION['user']) && $_SESSION['user']['is_admin'] && isset($_GET['delete'])) {
    $stmt = $conn->prepare("DELETE FROM discussion WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Handle delete if admin (for comments)
if (!empty($_SESSION['user']) && $_SESSION['user']['is_admin'] && isset($_GET['delete_comment'])) {
    $stmt = $conn->prepare("DELETE FROM comments WHERE id = ?");
    $stmt->execute([$_GET['delete_comment']]);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Fetch discussion posts with usernames
$posts = $conn->query("SELECT discussion.id, content, users.username, discussion.created_at FROM discussion JOIN users ON discussion.user_id = users.id ORDER BY discussion.created_at DESC")->fetchAll(PDO::FETCH_ASSOC);

// Fetch all comments, indexed by discussion_id
$commentsStmt = $conn->query("SELECT comments.id, discussion_id, content, users.username, comments.created_at FROM comments JOIN users ON comments.user_id = users.id ORDER BY comments.created_at ASC");
$commentsRaw = $commentsStmt->fetchAll(PDO::FETCH_ASSOC);

// Organize comments by discussion post id for easier display
$commentsByPost = [];
foreach ($commentsRaw as $comment) {
    $commentsByPost[$comment['discussion_id']][] = $comment;
}
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
  <style>
    .comment-box { margin-left: 2rem; margin-top: 1rem; }
    .comment-meta { font-size: 0.85rem; color: #555; }
  </style>
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

          <!-- Comments -->
          <div class="comments mt-3">
            <?php if (!empty($commentsByPost[$post['id']])): ?>
              <?php foreach ($commentsByPost[$post['id']] as $comment): ?>
                <div class="comment-box card p-2 mb-2">
                  <div class="comment-meta">
                    <?= htmlspecialchars($comment['username']) ?> commented on <?= date('Y-m-d H:i', strtotime($comment['created_at'])) ?>
                    <?php if (!empty($_SESSION['user']) && $_SESSION['user']['is_admin']): ?>
                      <a href="?delete_comment=<?= $comment['id'] ?>" class="btn btn-sm btn-danger" style="float:right;" onclick="return confirm('Delete this comment?')">Delete</a>
                    <?php endif; ?>
                  </div>
                  <p class="mb-0"><?= nl2br(htmlspecialchars($comment['content'])) ?></p>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>

          <!-- Comment form (for logged in users) -->
          <?php if (!empty($_SESSION['user'])): ?>
            <form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" class="mt-3">
              <input type="hidden" name="discussion_id" value="<?= $post['id'] ?>">
              <div class="form-group">
                <textarea name="content" class="form-control" rows="2" placeholder="Write a comment..." required></textarea>
              </div>
              <button type="submit" class="btn btn-secondary btn-sm mt-1">Comment</button>
            </form>
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
