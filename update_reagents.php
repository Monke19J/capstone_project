<?php
include "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id              = $_POST['reagent_id'] ?? null;
    $name            = $_POST['reagent_name'] ?? '';
    $min_quantity    = $_POST['min_quantity'] ?? '';
    $category        = $_POST['category'] ?? '';
    $item_desc       = $_POST['item_description'] ?? '';
    $test_kit        = $_POST['test_kit'] ?? '';
    $packaging       = $_POST['packaging'] ?? '';
    $reagent_type    = $_POST['reagent_type'] ?? '';

    // Validate ID
    if (!$id) {
        die("Invalid reagent ID.");
    }

    // Handle file upload
    $imagePath = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir  = "uploads/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $filename   = time() . "_" . basename($_FILES['image']['name']);
        $targetFile = $uploadDir . $filename;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $imagePath = $targetFile;
        }
    }

    // Prepare SQL
    if ($imagePath) {
        $sql = "UPDATE reagents 
                SET reagent_name=?, min_quantity=?, category=?, item_description=?, test_kit=?, packaging=?, reagent_type=?, reagent_img=? 
                WHERE reagent_id=?";
    } else {
        $sql = "UPDATE reagents 
                SET reagent_name=?, min_quantity=?, category=?, item_description=?, test_kit=?, packaging=?, reagent_type=? 
                WHERE reagent_id=?";
    }

    $stmt = $conn->prepare($sql);

    if ($imagePath) {
        $stmt->bind_param(
            "sissssssi",
            $name,
            $min_quantity,
            $category,
            $item_desc,
            $test_kit,
            $packaging,
            $reagent_type,
            $imagePath,
            $id
        );
    } else {
        $stmt->bind_param(
            "sisssssi",
            $name,
            $min_quantity,
            $category,
            $item_desc,
            $test_kit,
            $packaging,
            $reagent_type,
            $id
        );
    }

    if ($stmt->execute()) {
        header("Location: ./chemistry.php?status=success-edit");
        exit();
    } else {
        $errorMsg = $stmt->error;
        $stmt->close();
        header("Location: ./chemistry.php?status=error&message=" . urlencode($errorMsg));
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: ./chemistry.php?status=error&message=" . urlencode("Invalid Edit"));
    exit();
}
?>
