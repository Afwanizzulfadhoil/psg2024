<?php
include 'connect.php';
session_start();
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id_users'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['username'] = $user['username'];

        if ($user['role'] == 'admin') {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: user_dashboard.php");
        }
    } else {
        $error_message = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Website Personal for My Project">
    <link rel="icon" href="/img/icn.png">
    <title>FadoiruLexiana | Log In</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #1c1273;
        }

        .box {
            background-color: #8d87bd;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }

        .box h2 {
            margin-bottom: 20px;
            color: #fff;
        }

        .box img {
            display: block;
            margin: 0 auto;
            width: 100px;
        }

        .textbox {
            margin-bottom: 15px;
        }

        .textbox input {
            width: auto;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .btn-container {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-top: 10px;
        }

        .btn {
            flex: 1;
            padding: 10px;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .btn a {
            color: white;
            text-decoration: none;
        }

        .error-message {
            color: red;
            margin-bottom: 15px;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="box">
        <h2>Log In</h2>
        <img src="/assets/img/icn.png" alt="FadoiruLexiana-repa-mioza">
        <?php if ($error_message != ''): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <form method="POST" action="login.php">
            <div class="textbox">
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="textbox">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <div class="btn-container">
                <button class="btn" type="submit">Login</button>
                <a href="register.php" class="btn">Sign Up</a>
            </div>
        </form>
    </div>
</body>

</html>
