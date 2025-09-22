<?php
include "db.php";

// print_r($_POST);

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ./login.html?status=error&message=" . urlencode("Please log in first"));
}

$currentUser = $_SESSION['user_id'] ?? null;

$reagent_type = $_POST['reagent_type'] ?? null;
$reagent_name = $_POST['reagent_name'] ?? null;
$min_quantity = $_POST['min-quantity'] ?? null;
$category = $_POST['category'] ?? null;
$item_description = $_POST['item_description'] ?? null;
$test_kit = $_POST['test_kit'] ?? null;
$packaging = $_POST['packaging'] ?? null;

$imagePath = "./images/no_available_img.jpeg";

if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['image']['tmp_name'];
    $fileName    = $_FILES['image']['name'];
    $fileExt     = pathinfo($fileName, PATHINFO_EXTENSION);

    $newFileName = uniqid("img_", true) . "." . strtolower($fileExt);

    $uploadDir  = "./product_img/";
    $destPath   = $uploadDir . $newFileName;

    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if (move_uploaded_file($fileTmpPath, $destPath)) {
        $imagePath = $destPath;
    }
}

if ($reagent_type && $reagent_name && $min_quantity && $category) {
    $sql = "INSERT INTO reagents (reagent_type, reagent_name, min_quantity, category, reagent_img, item_description, test_kit, packaging)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssisssss",
        $reagent_type,
        $reagent_name,
        $min_quantity,
        $category,
        $imagePath,
        $item_description,
        $test_kit,
        $packaging
    );
    if ($stmt->execute()) {
        $reagent_id = $stmt->insert_id; // get reagent ID for alerts
        $stmt->close();

        $alert_condition = $_POST['alert_condition'] ?? null;
        $alert_min_qty   = $_POST['alert_min_quantity'] ?? null;

        if ($alert_condition && $alert_min_qty) {
            // Insert into alerts table
            $stmt = $conn->prepare("INSERT INTO alerts (reagent_id, alert_condition, min_quantity) VALUES (?, ?, ?)");
            $stmt->bind_param("isi", $reagent_id, $alert_condition, $alert_min_qty);
            $stmt->execute();
            $alert_id = $stmt->insert_id;
            $stmt->close();
        }

        if ($reagent_type === "chemistry") {
            header("Location: ./chemistry.php?status=success");
        } elseif ($reagent_type === "hematology") {
            header("Location: ./hematology.php?status=success");
        } elseif ($reagent_type === "immunology") {
            header("Location: ./immunology.php?status=success");
        } else {
            header("Location: ./dashboard.php?status=success");
        }
        exit();
    } else {
        $errorMsg = $stmt->error;
        $stmt->close();
        header("Location: ./chemistry.php?status=error&message=" . urlencode($errorMsg));
        exit();
    }
} else {
    header("Location: ./chemistry.php?status=error&message=" . urlencode("Missing required fields"));
    exit();
}

$conn->close();
