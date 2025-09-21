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
    $reagent_id = $_GET['reagent_id'] ?? null;

    if (!$id || !is_numeric($id)) {
        header("Location: ./reagent_table.php?reagent_id=" . urlencode($reagent_id) . "&status=error&message=" . urlencode("Invalid Attempt"));
        exit();
    }

    // Soft delete reagent
    $sql = "UPDATE reagent_stock SET stock_status = 'inactive' WHERE stock_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: ./reagent_table.php?reagent_id=" . urlencode($reagent_id) . "&status=success-delete&message=" . urlencode("Stocks deleted successfully"));
        exit();
    } else {
        $errorMsg = $stmt->error;
        $stmt->close();
        $conn->close();
        header("Location: ./reagent_table.php?reagent_id=" . urlencode($reagent_id) . "&status=error&message=" . urlencode("Delete failed: " . $errorMsg));
        exit();
    }
} else {
    // fallback if request is not GET
    header("Location: ./reagent_table.php?status=error&message=" . urlencode("Invalid Request"));
    exit();
}
?>
