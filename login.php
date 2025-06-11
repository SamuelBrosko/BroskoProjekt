<?php
ini_set('session.gc_maxlifetime', 1800); // Session lasts 30 minutes
session_set_cookie_params(1800);
session_start();

$pdo = new PDO('mysql:host=localhost;dbname=eshop', 'root', '');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && hash('sha256', $password) === $user['password']) {
        $_SESSION['user'] = $user;
        header("Location: index.php"); // Redirect to homepage after login
        exit;
    } else {
        $error = "Invalid login.";
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Login</title></head>
<body>
<h2>Login</h2>
<form method="POST">
  Username: <input type="text" name="username" required><br>
  Password: <input type="password" name="password" required><br>
  <button type="submit">Login</button>
</form>
<?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
<p>Don't have an account? <a href="register.php">Register here</a></p>
</body>

<!-- Footer -->
<footer>
    <div class="container">
        <p>&copy; 2024 Cyborg Gaming. All rights reserved.</p>
    </div>
</footer>

</html></div>
<style>
/* [Your CSS stays unchanged] */
<?php // Keeping this part unchanged from your original CSS ?>
body {
    background: #181818;
    color: #fff;
    font-family: 'Segoe UI', Arial, sans-serif;
    margin: 0;
    padding: 0;
}
h2 {
    text-align: center;
    margin-top: 60px;
    font-size: 2.2em;
    letter-spacing: 2px;
    color: #00ffea;
}
form {
    background: #232323;
    max-width: 350px;
    margin: 40px auto 0 auto;
    padding: 32px 28px 24px 28px;
    border-radius: 12px;
    box-shadow: 0 4px 24px rgba(0,0,0,0.5);
    display: flex;
    flex-direction: column;
    gap: 18px;
}
input[type="text"], input[type="password"] {
    width: 100%;
    padding: 12px 10px;
    border: 1px solid #333;
    border-radius: 6px;
    background: #181818;
    color: #fff;
    font-size: 1em;
    transition: border 0.2s;
}
input[type="text"]:focus, input[type="password"]:focus {
    border: 1.5px solid #00ffea;
    outline: none;
}
button[type="submit"] {
    background: linear-gradient(90deg, #00ffea 0%, #0077ff 100%);
    color: #181818;
    border: none;
    border-radius: 6px;
    padding: 12px 0;
    font-size: 1.1em;
    font-weight: bold;
    cursor: pointer;
    transition: background 0.2s, color 0.2s;
}
button[type="submit"]:hover {
    background: linear-gradient(90deg, #0077ff 0%, #00ffea 100%);
    color: #fff;
}
form p, form a {
    color: #aaa;
    font-size: 0.98em;
    text-align: center;
}
form a {
    color: #00ffea;
    text-decoration: none;
    transition: color 0.2s;
}
form a:hover {
    color: #fff;
    text-decoration: underline;
}
footer {
    background: #232323;
    color: #aaa;
    text-align: center;
    padding: 18px 0 10px 0;
    position: fixed;
    width: 100%;
    bottom: 0;
    left: 0;
    font-size: 1em;
    letter-spacing: 1px;
    z-index: 10;
}
.container {
    max-width: 1200px;
    margin: 0 auto;
}
p {
    text-align: center;
    margin-top: 18px;
}
</style>
