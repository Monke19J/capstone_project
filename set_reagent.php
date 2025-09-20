<?php
session_start();

if (isset($_GET['id'])) {
    $_SESSION['current_reagent_id'] = $_GET['id'];
    header("Location: reagent_table.php");
    exit();
} else {
    header("Location: chemistry.php?status=error&message=Missing+reagent+id");
    exit();
}
