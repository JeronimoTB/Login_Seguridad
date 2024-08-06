<?php
class User {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function register($email, $password) {
        if (!validateToken($_POST['csrf_token'])) {
            return "CSRF token validation failed";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Invalid email format";
        }

        if (strlen($password) < 8) {
            return "Password must be at least 8 characters long";
        }

        if (!validatePasswordStrength($password)) {
            return "Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.";
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $sql = "INSERT INTO users (email, password) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return "SQL error: " . $this->conn->error;
        }

        $stmt->bind_param("ss", $email, $hashedPassword);
        if ($stmt->execute()) {
            $stmt->close();
            return "Registration successful!";
        } else {
            $stmt->close();
            return "Error: " . $stmt->error;
        }
    }

    public function login($email, $password) {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return "SQL error: " . $this->conn->error;
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $stmt->close();
            if (password_verify($password, $user['password'])) {
                return $user;
            } else {
                return "Invalid credentials!";
            }
        } else {
            $stmt->close();
            return "Invalid credentials!";
        }
    }
}
?>
