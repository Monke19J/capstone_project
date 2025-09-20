<?php
include "db.php";
session_start();

$username   = $_POST['Username'] ?? '';
$email      = $_POST['email'] ?? '';
$password   = $_POST['password'] ?? '';
$terms      = $_POST['terms_condition'] ?? '';
$user_type = "engineer";

if ($username && $email && $password && $terms) {
    // Hash the password before storing
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, email, password, user_type)
            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $username, $email, $hashedPassword, $user_type);

    if ($stmt->execute()) {
        // Get the ID of the newly registered user
        $newUserId = $stmt->insert_id;

        // Store in session
        $_SESSION['user_id'] = $newUserId;
        $_SESSION['username'] = $username;

        if (true) {
            header("./dashboard_engineering.php");
        } else {
            header("Location: ./register.html?status=error&message=" . urlencode("Unknown account type"));
        }
        exit();
    } else {
        header("Location: ./register.html?status=error&message=" . urlencode($stmt->error));
        exit();
    }

    $stmt->close();
} else {
    header("Location: ./register.html?status=error&message=" . urlencode("Missing required fields"));
    exit();
}

$conn->close();
?>
