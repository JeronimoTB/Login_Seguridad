<?php
include_once('config.php');
include_once('functions.php');

// if (!isset($_SESSION['user_id'])) {
//     header("Location: index.php");
//     exit();
// }


if (!isset($_SESSION['user_id']) || (time() > $_SESSION['last_activity']) > 60) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}

$_SESSION['last_activity'] = time();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>
<body>
    <h1>Welcome to the home page!</h1>
    <a href="logout.php">Logout</a>

    <script>
        let timer;

        function startTimer() {
            timer = setTimeout(logout, 10000);  // 1 minute = 60000 ms
        }

        function resetTimer() {
            clearTimeout(timer);
            startTimer();
        }

        function logout() {
            navigator.sendBeacon('logout.php');
            window.location.href = 'index.php';
        }

        window.onload = startTimer;
        window.onmousemove = resetTimer;
        window.onmousedown = resetTimer;
        window.ontouchstart = resetTimer;
        window.onclick = resetTimer;
        window.onkeypress = resetTimer;

        window.addEventListener('beforeunload', function() {
            navigator.sendBeacon('logout.php');
        });
    </script>
</body>
</html>
