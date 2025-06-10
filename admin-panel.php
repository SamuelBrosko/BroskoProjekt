<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=eshop', 'root', '');

if (!isset($_SESSION['user']) || !$_SESSION['user']['is_admin']) {
    die("Access denied.");
}

if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM motorbikes WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: admin-panel.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $additional_info = $_POST['info'];
    $filename = $_FILES['image']['name'] ?? '';
    $uploadDir = 'uploads/';

    if (!empty($filename)) {
        move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $filename);
    }

    if (!empty($_POST['id'])) {
        $query = "UPDATE motorbikes SET title=?, description=?, additional_info=?" .
                 (!empty($filename) ? ", image=?" : "") .
                 " WHERE id=?";
        $params = [$title, $description, $additional_info];
        if (!empty($filename)) $params[] = $filename;
        $params[] = $_POST['id'];
        $pdo->prepare($query)->execute($params);
    } else {
        $stmt = $pdo->prepare("INSERT INTO motorbikes (title, description, image, additional_info) VALUES (?, ?, ?, ?)");
        $stmt->execute([$title, $description, $filename, $additional_info]);
    }

    header("Location: admin-panel.php");
    exit;
}

$editing = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM motorbikes WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $editing = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html>
<head><title>Admin Panel</title></head>
<body>
<h2><?php echo $editing ? "Edit Motorbike" : "Add New Motorbike"; ?></h2>
<form method="POST" enctype="multipart/form-data">
  <input type="hidden" name="id" value="<?php echo $editing['id'] ?? ''; ?>">
  Title: <input type="text" name="title" value="<?php echo $editing['title'] ?? ''; ?>" required><br><br>
  Description:<br>
  <textarea name="description" required><?php echo $editing['description'] ?? ''; ?></textarea><br><br>
  Image: <input type="file" name="image"><br><br>
  Additional Info:<br>
  <textarea name="info"><?php echo $editing['additional_info'] ?? ''; ?></textarea><br><br>
  <button type="submit">Save</button>
</form>
<p><a href="browse.php">← Back to Browse</a></p>
</body>
</html>
