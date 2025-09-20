<?php
include "db.php";

session_start();

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if ($username && $password) {
    $stmt = $conn->prepare("SELECT user_id, username, user_type, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['user_type'] = $row['user_type'];

            if ($row['user_type'] == "super_admin") {
                header("Location: ./dashboard_sale.php");
            } elseif ($row['user_type'] == "viewer") {
                header("Location: ./dashboard_sale_view.php");
            } elseif ($row['user_type'] == "engineer") {
                header("Location: ./dashboard_engineering.php");
            } else {
                header("Location: ./login.html?status=error&message=" . urlencode("Unknown account type"));
            }
            exit();
        } else {
            // Wrong password
            header("Location: ./login.html?status=error&message=" . urlencode("Invalid username or password"));
            exit();
        }
    } else {
        // No user found
        header("Location: ./login.html?status=error&message=" . urlencode("Invalid username or password"));
        exit();
    }

    $stmt->close();
} else {
    header("Location: ./login.html?status=error&message=" . urlencode("Missing required fields"));
    exit();
}

$conn->close();
