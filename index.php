<?php
include_once('config.php');
include_once('functions.php');
include_once('User.php');

$userModel = new User($conn);

if (isset($_SESSION['user_id'])) {
    header("Location: home.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!validateToken($_POST['csrf_token'])) {
        $_SESSION['message'] = "CSRF token validation failed";
    } else {
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['message'] = "Invalid email format";
        } elseif (isset($_POST['register'])) {
            $_SESSION['message'] = $userModel->register($email, $password);
        } elseif (isset($_POST['login'])) {
            $result = $userModel->login($email, $password);
            if (is_array($result)) {
                $_SESSION['user_id'] = $result['id'];
                $_SESSION['last_activity'] = time();
                header("Location: home.php");
                exit();
            } else {
                $_SESSION['message'] = $result;
            }
        }
    }
    header("Location: index.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php
    if (isset($_SESSION['message'])) {
        echo '<p>' . $_SESSION['message'] . '</p>';
        unset($_SESSION['message']);
    }
    ?>

    <h2>Register</h2>
    <form id="registerForm" action="index.php" method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo generateToken(); ?>">
        <input type="email" name="email" id="registerEmail" placeholder="Email" required>
        <input type="password" name="password" id="registerPassword" placeholder="Password" required minlength="8">
        <button type="submit" name="register">Register</button>
    </form>

    <h2>Login</h2>
    <form id="loginForm" action="index.php" method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo generateToken(); ?>">
        <input type="email" name="email" id="loginEmail" placeholder="Email" required>
        <input type="password" name="password" id="loginPassword" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
    </form>
</body>
</html>
