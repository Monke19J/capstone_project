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
            $stock_id = $stmt->insert_id;
            $stmt->close();

            $action_type = "add";
            $client      = null; // no client for restock
            $date_action = $date_received ?? date('Y-m-d');

            $stmt2 = $conn->prepare("INSERT INTO stock_history 
                    (stock_id, reagent_id, action_type, quantity, client_id, date_action) 
                    VALUES (?, ?, ?, ?, ?, ?)");
            $stmt2->bind_param("iisiss", $stock_id, $reagent_id, $action_type, $quantity, $distributor, $date_action);
            $stmt2->execute();
            $stmt2->close();
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
