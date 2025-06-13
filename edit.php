<?php
session_start();

// Redirect non-admins
if (empty($_SESSION['user']) || $_SESSION['user']['is_admin'] != 1) {
    header("Location: index.php");
    exit;
}

// Connect to database
$pdo = new PDO('mysql:host=localhost;dbname=eshop', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Check for bike ID
if (!isset($_GET['id'])) {
    header("Location: admin-panel.php");
    exit;
}

$id = (int)$_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM motorbikes WHERE id = ?");
$stmt->execute([$id]);
$bike = $stmt->fetch();

if (!$bike) {
    echo "Motorbike not found.";
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $additional_info = $_POST['additional_info'] ?? '';
    $image = $bike['image'];

    // Optional image upload
    if (!empty($_FILES['image']['name'])) {
        $target = 'uploads/' . basename($_FILES['image']['name']);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $image = $_FILES['image']['name'];
        }
    }

    $update = $pdo->prepare("UPDATE motorbikes SET title = ?, description = ?, additional_info = ?, image = ? WHERE id = ?");
    $update->execute([$title, $description, $additional_info, $image, $id]);

    header("Location: admin-panel.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Motorbike</title>
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #1f2122;
            color: #fff;
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .container {
            background-color: #2c2f33;
            padding: 30px;
            border-radius: 10px;
            max-width: 600px;
        }
        label {
            margin-top: 15px;
        }
        input, textarea {
            background-color: #1f1f1f;
            color: #fff;
            border: 1px solid #555;
        }
        button {
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Edit Motorbike</h2>
    <form method="POST" enctype="multipart/form-data">
        <label for="title">Title</label>
        <input class="form-control" type="text" name="title" value="<?= htmlspecialchars($bike['title']) ?>" required>

        <label for="description">Description</label>
        <textarea class="form-control" name="description" rows="4" required><?= htmlspecialchars($bike['description']) ?></textarea>

        <label for="additional_info">Additional Info</label>
        <textarea class="form-control" name="additional_info" rows="2"><?= htmlspecialchars($bike['additional_info']) ?></textarea>

        <label>Current Image</label><br>
        <img src="uploads/<?= htmlspecialchars($bike['image']) ?>" alt="Bike Image" width="100"><br>

        <label for="image">Upload New Image</label>
        <input class="form-control" type="file" name="image">

        <button class="btn btn-success" type="submit">Save Changes</button>
    </form>
</div>
</body>
</html>
