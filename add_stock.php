<?php
include "db.php";

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ./login.html?status=error&message=" . urlencode("Please log in first"));
}

// Validate required fields
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $reagent_id   = $_POST['reagent_id'] ?? null;
    $lot_no       = $_POST['lot_no'] ?? null;
    $distributor  = $_POST['distributor'] ?? null;
    $date_received = $_POST['date_arrived'] ?? null;
    $expiry_date  = $_POST['expiry_date'] ?? null;
    $quantity     = $_POST['quantity'] ?? null;

    if ($reagent_id && $lot_no && $distributor && $quantity) {
        $stmt = $conn->prepare("INSERT INTO reagent_stock 
            (reagent_id, lot_no, distributor, date_arrived, expiry_date, quantity) 
            VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssi", $reagent_id, $lot_no, $distributor, $date_received, $expiry_date, $quantity);

        if ($stmt->execute()) {
            header("Location: reagent_table.php?reagent_id=" . urlencode($reagent_id) . "&status=success&msg=Stock added");
            exit();
        } else {
            header("Location: reagent_table.php?reagent_id=" . urlencode($reagent_id) . "&status=error&msg=Failed to add stock");
            exit();
        }
    } else {
        header("Location: reagent_table.php?status=error&msg=Missing required fields");
        exit();
    }
}
?>
