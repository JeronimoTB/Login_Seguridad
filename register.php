<!-- <?php
include('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!validateToken($_POST['csrf_token'])) {
        die("CSRF token validation failed");
    }

    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
    }

    if (strlen($password) < 8) {
        die("Password must be at least 8 characters long");
    }

    if (!validatePasswordStrength($password)) {
        die("Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.");
    }

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $sql = "INSERT INTO users (email, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("SQL error: " . $conn->error);
    }

    $stmt->bind_param("ss", $email, $hashedPassword);
    if ($stmt->execute()) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?> -->
