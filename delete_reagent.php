<?php
include "db.php";
session_start();

// Ensure only logged-in super_admin can delete
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'super_admin') {
    header("Location: ./login.html?status=error&message=" . urlencode("Unauthorized"));
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $id = $_GET['id'] ?? null;

    if (!$id || !is_numeric($id)) {
        header("Location: ./reagent_table.php?status=error&message=" . urlencode("Invalid Attempt"));
        exit();
    }

    // Soft delete reagent
    $sql = "UPDATE reagents SET reagent_status = 'inactive' WHERE reagent_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $stmt->close();

        // Soft delete related stocks
        $sql2 = "UPDATE reagent_stock SET stock_status = 'inactive' WHERE reagent_id = ?";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("i", $id);
        $stmt2->execute();
        $stmt2->close();

        $conn->close();
        header("Location: ./chemistry.php?status=success-delete&message=" . urlencode("Reagent and related stocks deleted successfully"));
        exit();
    } else {
        $errorMsg = $stmt->error;
        $stmt->close();
        $conn->close();
        header("Location: ./chemistry.php?status=error&message=" . urlencode("Delete failed: " . $errorMsg));
        exit();
    }
} else {
    header("Location: ./chemistry.php?status=error&message=" . urlencode("Invalid Request"));
    exit();
}
