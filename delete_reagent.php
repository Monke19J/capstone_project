<?php
include "db.php";
session_start();

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


    $sql_type = "SELECT reagent_type FROM reagents WHERE reagent_id = ?";
    $stmt_type = $conn->prepare($sql_type);
    $stmt_type->bind_param("i", $id);
    $stmt_type->execute();
    $result_type = $stmt_type->get_result();
    $row_type = $result_type->fetch_assoc();
    $reagent_type = $row_type['reagent_type'] ?? null;
    $stmt_type->close();

    if (!$reagent_type) {
        $conn->close();
        header("Location: ./".$reagent_type.".php?status=error&message=" . urlencode("Reagent not found"));
        exit();
    }

    $sql = "UPDATE reagents SET reagent_status = 'inactive' WHERE reagent_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $stmt->close();

        $sql2 = "UPDATE reagent_stock SET stock_status = 'inactive' WHERE reagent_id = ?";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("i", $id);
        $stmt2->execute();
        $stmt2->close();

        $conn->close();

        if ($reagent_type === "Chemistry") {
            header("Location: ./chemistry.php?status=success-delete&message=" . urlencode("Reagent deleted successfully"));
        } elseif ($reagent_type === "Immunology") {
            header("Location: ./immunology.php?status=success-delete&message=" . urlencode("Reagent deleted successfully"));
        } elseif ($reagent_type === "Hematology") {
            header("Location: ./Hematology.php?status=success-delete&message=" . urlencode("Reagent deleted successfully"));
        } else {
            header("Location: ./reagent_table.php?status=success-delete&message=" . urlencode("Reagent deleted successfully"));
        }
        exit();

    } else {
        $errorMsg = $stmt->error;
        $stmt->close();
        $conn->close();
        header("Location: ./reagent_table.php?status=error&message=" . urlencode("Delete failed: " . $errorMsg));
        exit();
    }
} else {
    header("Location: ./reagent_table.php?status=error&message=" . urlencode("Invalid Request"));
    exit();
}
