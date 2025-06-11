<?php
$pdo = new PDO('mysql:host=localhost;dbname=eshop', 'root', '');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $pass1 = $_POST['password'];
    $pass2 = $_POST['confirm_password'];

    if ($pass1 !== $pass2) {
        $error = "Passwords do not match.";
    } else {
        $password = hash('sha256', $pass1);
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        try {
            $stmt->execute([$username, $password]);
            header("Location: login.php");
        } catch (PDOException $e) {
            $error = "Username already taken.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Register</title></head>
<body>
<h2>Register</h2>
<form method="POST">
  Username: <input type="text" name="username" required><br><br>
  Password: <input type="password" name="password" required><br><br>
  Confirm Password: <input type="password" name="confirm_password" required><br><br>
  <button type="submit">Register</button>
</form>
<?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
</body>
</html>

<style>
body {
    background: #1f2122;
    color: #fff;
    font-family: Arial, sans-serif;
    margin: 0;
    height: 100vh;
}
.center-container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 90vh;
}
.form-box {
    background: #27292a;
    padding: 40px 30px;
    border-radius: 10px;
    box-shadow: 0 0 10px #0006;
    min-width: 320px;
}
.form-box h2 {
    text-align: center;
    margin-bottom: 25px;
    color: #ff4655;
}
.form-box input[type="text"],
.form-box input[type="password"] {
    width: 100%;
    padding: 10px;
    margin: 8px 0 20px 0;
    border: none;
    border-radius: 5px;
    background: #232526;
    color: #fff;
}
.form-box button {
    width: 100%;
    padding: 10px;
    background: #ff4655;
    color: #fff;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.2s;
}
.form-box button:hover {
    background: #e03a4d;
}
.form-box p {
    text-align: center;
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    var form = document.querySelector("form");
    var container = document.createElement("div");
    container.className = "center-container";
    var box = document.createElement("div");
    box.className = "form-box";
    box.innerHTML = form.outerHTML;
    form.parentNode.replaceChild(container, form);
    container.appendChild(box);

    var h2 = document.querySelector("h2");
    box.insertBefore(h2, box.firstChild);
    
    var error = document.querySelector("body > p");
    if (error) box.appendChild(error);
});
</script>
