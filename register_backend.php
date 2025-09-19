<?php
include "db.php";
session_start(); // REQUIRED before using $_SESSION

$username   = $_POST['Username'] ?? '';
$email      = $_POST['email'] ?? '';
$password   = $_POST['password'] ?? '';
$terms      = $_POST['terms_condition'] ?? '';

if ($username && $email && $user_type && $password && $terms) {
    // Hash the password before storing
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, email, password)
            VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $hashedPassword);

    if ($stmt->execute()) {
        // Get the ID of the newly registered user
        $newUserId = $stmt->insert_id;

        // Store in session
        $_SESSION['user_id'] = $newUserId;
        $_SESSION['username'] = $username;

        if (true) {
            header("Location: ./dashboard_sale.php");
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
