<?php
session_start();

require_once 'Database.php';

// Redirect non-admin users away
if (empty($_SESSION['user']) || !$_SESSION['user']['is_admin']) {
    header("Location: discussion.php");
    exit;
}

$db = new Database();
$conn = $db->getConnection();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    // Invalid or missing id, redirect back
    header("Location: discussion.php");
    exit;
}

$post_id = (int) $_GET['id'];

// Handle form submission to update the post
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['content'])) {
    $stmt = $conn->prepare("UPDATE discussion SET content = ? WHERE id = ?");
    $stmt->execute([$_POST['content'], $post_id]);
    header("Location: discussion.php");
    exit;
}

// Fetch the current post content
$stmt = $conn->prepare("SELECT content FROM discussion WHERE id = ?");
$stmt->execute([$post_id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    // Post not found, redirect back
    header("Location: discussion.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Edit Discussion Post - Motorbike Eshop</title>
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="assets/css/fontawesome.css" />
  <link rel="stylesheet" href="assets/css/templatemo-cyborg-gaming.css" />
</head>
<body>

<?php include 'header.php'; ?>

<div class="container mt-5">
  <div class="page-content">
    <div class="heading-section text-center mb-4">
      <h4>Edit Discussion Post</h4>
    </div>

    <form method="POST" action="">
      <div class="form-group">
        <textarea name="content" class="form-control" rows="5" required><?= htmlspecialchars($post['content']) ?></textarea>
      </div>
      <button type="submit" class="btn btn-primary mt-2">Save Changes</button>
      <a href="diss.php" class="btn btn-secondary mt-2">Cancel</a>
    </form>
  </div>
</div>

<?php include 'footer.php'; ?>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/js/custom.js"></script>
</body>
</html>
