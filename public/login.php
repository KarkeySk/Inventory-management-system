<?php
require_once '../config/db.php';

if(isset($_POST['login'])){
    if($_POST['username'] == "admin" && $_POST['password'] == "admin123"){
        $_SESSION['admin'] = true;
        header("Location: index.php");
        exit;
    } else {
        $error = "Invalid username or password";
    }
}

// If already logged in, redirect
if(isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - Inventory System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="login-page">
    <div class="login-wrapper">
        <div class="login-left">
            <div class="login-logo">📦</div>
            <h1>Product Inventory System</h1>
            <p>Manage your products efficiently with our powerful inventory management system.</p>
        </div>
        <div class="login-right">
            <h2>Welcome Back!</h2>
            <p class="login-subtitle">Please enter your credentials to continue</p>
            <form method="post" class="login-form">
                <div class="form-group-login">
                    <label>Username</label>
                    <input type="text" name="username" placeholder="Enter your username" required>
                </div>
                <div class="form-group-login">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Enter your password" required>
                </div>
                <button type="submit" name="login" class="login-btn">Login to Dashboard</button>
                <?php if(isset($error)): ?>
                <div class="error-login">⚠️ <?= $error ?></div>
                <?php endif; ?>
            </form>
            <p style="margin-top:30px;font-size:13px;color:#6b7280;text-align:center;">
                Default: admin / admin123
            </p>
        </div>
    </div>
</body>
</html>