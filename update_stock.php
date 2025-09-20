<!-- Does not save the stock id and will fix it tommorow -->

<?php
include "db.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ./login.html?status=error&message=" . urlencode("Please log in first"));
}

// print_r($_POST);
// die();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $reagent_id = $_POST['reagent_id'] ?? null;
    $stock_id = $_POST['stock_id'] ?? null;
    $action_type = $_POST['action_type'] ?? null;
    $quantity = intval($_POST['quantity'] ?? 0);
    $client = $_POST['client'] ?? null;
    $date_action = $_POST['date_action'] ?? date('Y-m-d');

    if (!$reagent_id || !$action_type || $quantity <= 0) {
        header("Location: reagent_table.php?status=error&msg=Invalid+data");
        exit();
    }

    //  Update reagent_stock (we’ll assume total stock is summed in reagent_stock table)
    if ($action_type === "add") {
        $stmt = $conn->prepare("UPDATE reagent_stock SET quantity = quantity + ? WHERE stock_id = ?");
    } elseif ($action_type === "remove") {
        $stmt = $conn->prepare("UPDATE reagent_stock SET quantity = GREATEST(quantity - ?, 0) WHERE stock_id = ?");
    }
    $stmt->bind_param("ii", $quantity, $reagent_id);
    $stmt->execute();
    $stmt->close();

    // Insert into stock_history
    $stmt2 = $conn->prepare("INSERT INTO stock_history (stock_id, reagent_id, action_type, quantity, client_id, date_action) 
                         VALUES (?, ?, ?, ?, ?, ?)");
    $stmt2->bind_param("iisiss", $stock_id, $reagent_id, $action_type, $quantity, $client, $date_action);
    $stmt2->execute();
    $stmt2->close();

    header("Location: reagent_table.php?reagent_id=" . $reagent_id . "&status=success-update&msg=Stock+updated");
    exit();
} else {
    header("Location: reagent_table.php?status=error&msg=Invalid+request");
    exit();
}

$conn->close();
