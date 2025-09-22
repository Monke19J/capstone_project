<!-- Medyo magulo as i did all the different modules in a
  single file but i will separate them in a different files 
  for code clarity as soon as i implement all the required features -->




<?php
include "db.php";

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ./login.html?status=error&message=" . urlencode("Please log in first"));
}

if (isset($_SESSION['current_reagent_id'])) {
    unset($_SESSION['current_reagent_id']);
}

$sql_reagent_type = "SELECT COUNT(*) AS total_reagents 
                     FROM reagents 
                     WHERE reagent_status = 'active' AND reagent_type = 'chemistry'";
$result_reagent_type = $conn->query($sql_reagent_type);
$total_reagents = ($result_reagent_type) ? $result_reagent_type->fetch_assoc()['total_reagents'] : 0;

$sql_qty = "
    SELECT COUNT(*) AS total_quantity
    FROM reagent_stock rs
    JOIN reagents r ON rs.reagent_id = r.reagent_id
    WHERE rs.stock_status = 'active'
      AND r.reagent_type = 'chemistry'
";
$result_qty = $conn->query($sql_qty);
$total_quantity = ($result_qty) ? $result_qty->fetch_assoc()['total_quantity'] : 0;

$total_value = 0;

$sql_card_value = "
    SELECT r.*, COUNT(rs.stock_id) AS stock_count
    FROM reagents r
    LEFT JOIN reagent_stock rs 
        ON r.reagent_id = rs.reagent_id 
        AND rs.stock_status = 'active'
    WHERE r.reagent_type = 'chemistry' 
      AND r.reagent_status = 'active'
    GROUP BY r.reagent_id
";
$result_card_value = $conn->query($sql_card_value);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>J&JMETS</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        .bg-light-ellipse {
            position: fixed;
            width: 480px;
            height: 477px;
            border-radius: 50%;
            background: linear-gradient(#0090ff 34%, #00cfff 91%);
            filter: blur(150px);
            opacity: 0.7;
        }

        .logo-background {
            position: fixed;
            height: 65vh;
            width: auto;
            z-index: -1;
            opacity: 0.1;
            top: 45vh;
            left: 50%;
            transform: translate(-15%, -40%);
        }

        .ellipse1 {
            left: -200px;
            top: 339px;
            z-index: 0;
        }

        .ellipse2 {
            left: 1360px;
            top: 339px;
            z-index: -1;
        }

        body {
            background-color: #ffffffff;
            padding-left: 330px;
            margin: 0;
            overflow-x: hidden;
        }

        html,
        body {
            overflow-x: hidden;
        }

        /* Sidebar Css */
        .sidebar {
            position: fixed;
            top: 20px;
            bottom: 20px;
            left: 30px;
            width: 300px;
            background-color: rgba(128, 128, 128, 0.2);
            backdrop-filter: blur(100px);
            border-radius: 20px;
            padding-top: 1rem;
            overflow-y: auto;
        }

        /* .sidebar a {
            color: black;
            text-decoration: none;
            display: block;
            padding: 10px 15px;
            border-radius: 8px;
            margin: 2px 8px;
        }
        .sidebar a:hover {
            background-color: #495057;
        } */

        .sidebar .logo-section {
            margin: 10px auto;
            width: 270px;
            height: 84px;
            background-color: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(100px);
            border-radius: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }

        .sidebar .logo-section #company-logo {
            width: 55px;
            height: 52px;
            border-radius: 50%;

        }

        #company-name {
            color: black;
            font-weight: bold;
            font-size: 20px;
            margin: 0;

        }

        ul.nav-list {
            list-style: none;
            padding: 0;
            margin-top: 35px;
        }

        li.nav-active {
            width: 90%;
            height: 62px;
            margin: 7px auto;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            background-color: white;
            border-radius: 20px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        li.nav-inactive {
            width: 88%;
            height: 62px;
            margin: 7px auto;
            border-radius: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            background-color: transparent;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        li.nav-inactive:hover {
            background-color: rgba(255, 255, 255, 0.2);
            cursor: pointer;
        }

        .list-icon {
            width: 39px;
            height: 36px;
            object-fit: contain;
            display: block;
        }

        .nav-listname {
            color: black;
            font-weight: 800;
            font-size: 20px;
            text-decoration: none;
            line-height: 1;
        }

        .submenu {
            display: flex;
            overflow: hidden;
            align-items: flex-start;
            flex-direction: column;
            margin: 0;
        }

        .submenu.hide {
            display: none;
        }

        .reagent-name {
            display: inline-flex;
            justify-content: flex-start;
            color: black;
            font-weight: 800;
            font-size: 20px;
            text-decoration: none;
            position: relative;
            margin-left: 40%;

        }

        .reagent-name::before {
            content: "•";
            position: absolute;
            left: 10%;
            color: black;
            font-size: 20px;
        }

        .submenu-inactive {
            padding: 3% 10%;
            border-radius: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: transparent;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .submenu-active {
            padding: 3% 10%;
            border-radius: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #0090FF;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .submenu-inactive:hover {
            background-color: rgba(255, 255, 255, 0.2);
            cursor: pointer;
        }

        /* End of Sidebar Css */


        /* Header Css */
        .header {
            margin-top: 20px;
            width: 100%;
            height: 60x;
            display: grid;
            grid-template-columns: 1fr auto;
            align-items: center;
            margin-bottom: 1%;
        }

        #header-title {
            /* grid-row: 1 / span 2; */
            display: inline-block;
            margin: 0;
            padding-left: 40px;
            font-weight: 800;
            font-size: 40px;
            color: #0090FF;
            -webkit-text-stroke: 1px black;

        }

        .header-actions {
            grid-column: 2;
            /* grid-row: 1; */
            align-self: start;
            height: 60px;
            display: flex;
            align-items: center;
            gap: 12px;
            padding-top: 5px;
        }

        .icon-btn {
            background-color: #180337;
            display: flex;
            align-items: center;
            gap: 8px;
            border: none;
            background: transparent;
            font-size: 20px;
            line-height: 1;
            cursor: pointer;
            padding: 6px 10px;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .icon-btn:hover {
            box-shadow: 0 0 8px rgba(255, 92, 247, 0.7);
            border: 1px solid #ff5cf7;
            transform: scale(1.1);
        }

        .avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: block;
        }

        #username {
            font-weight: bold;
            color: black;
            font-size: 15px;
        }

        #dropdown-icon {
            width: 14px;
            height: 12px;
            filter: brightness(0) saturate(100%) invert(84%) sepia(1%) saturate(0%) hue-rotate(209deg) brightness(92%) contrast(91%);
            padding-bottom: 3px;
        }


        /* Main content */
        main {
            /* background-color: aqua; */
            height: 85vh;
            width: auto;
            margin: 0 3%;
        }

        .add-item-section {
            display: flex;
            justify-content: flex-end;
            /* background-color: red; */
        }

        .add-item-btn {
            width: auto;
            height: 30px;
            border-radius: 10px;
            background-color: #2BB4F4;
            border-style: none;
            padding: 0 10px;
            margin-top: 1%;
            margin-bottom: 2%;
            margin-right: 3%;
            color: black;
            font-size: 12px;
            font-weight: 800;
            text-align: center;
            cursor: pointer;
        }

        .add-search-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            /* background-color: blue; */
        }

        .search-container {
            position: relative;
            margin: 2px 10px;
            display: flex;
            justify-content: flex-start;
            /* background-color: green; */
        }

        .searchbar-reagent {
            height: 36px;
            width: 80%;
            padding: 0 10px 0 40px;
            border: none;
            border-radius: 8px;
            outline: none;
            box-sizing: border-box;
            background-color: #D9D9D9;
        }

        .searchbar-icon {
            height: 20px;
            width: 20px;
            position: absolute;
            left: 12px;
            top: 45%;
            transform: translateY(-50%);
        }

        .catalog {
            display: flex;
            align-items: center;
            margin: 1% 5px;
            /* background-color: yellow; */
        }

        .catalog-btn {
            height: 36px;
            width: auto;
            padding: 0 6px;
            background: transparent;
        }

        .catalog-icon {
            height: 20px;
            width: 20px;
        }

        .info-section {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr;
        }

        .info-text {
            color: black;
            font-family: "Inter", sans-serif;
            font-size: 18px;
            font-weight: bold;
            margin-left: 10px;
        }

        .chemistry-items {
            height: 60vh;
            width: auto;
            margin: 3% 10px;
        }

        .item-row {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr;
            gap: 5%;
        }

        .item-card {
            width: 100%;
            height: auto;
            background: #ffffff;
            border: 1px solid #d0e6f9;
            border-radius: 20px;
            box-shadow: 0 4px 10px rgba(76, 201, 240, 0.2);
            display: flex;
            align-items: center;
            flex-direction: column;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .item-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(76, 201, 240, 0.35);
        }


        .item-img {
            width: 85%;
            height: 170px;
            margin: 15px 0;
            border-radius: 12px;
            border: 2px solid #4CC9F0;
            object-fit: contain;
            background: #f4faff;
        }

        .item-name {
            font-family: "Inter", sans-serif;
            color: #003366;
            font-weight: 600;
            font-size: 15px;
            text-align: center;
            margin: 5px 0;
        }

        /* for Category */
        .text-gradient {
            background: linear-gradient(to right, #4CC9F0, #4361EE);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }


        .item-info-format {
            display: inline-flex;
            align-items: center;
        }

        .item-info-img {
            height: 25px;
            width: 25px;
        }

        .item-info-text {
            font-family: "Inter", sans-serif;
            color: #004080;
            font-size: 14px;
            margin: 0 5px;
        }


        .customize-card {
            margin: 15px 0;
            display: inline-flex;
        }

        .edit-card {
            display: flex;
            justify-content: flex-start;
        }

        .customize-btn {
            height: 35px;
            width: auto;
            padding: 0 3px;
            background: transparent;
            border-style: none;
            cursor: pointer;
        }

        .customize-icon {
            height: 30px;
            width: 30px;
        }

        .view-stock-card {
            display: flex;
            align-items: center;
            justify-self: flex-end;
            margin-left: 5%;
        }

        .view-stock-btn {
            height: 30px;
            border-radius: 10px;
            background: #4CC9F0;
            border: none;
            padding: 0 7px;
            color: #ffffff;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.2s ease;
        }

        .view-stock-btn:hover {
            background: #3a9ecb;
        }

        /* Overlay */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 64, 128, 0.2);
            /* light blue tint overlay */
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        /* Modal box */
        .modal-content {
            background: #ffffff;
            width: 55%;
            padding: 30px 50px;
            border-radius: 16px;
            border: 1px solid #d0e6f9;
            /* light blue border */
            box-shadow: 0 8px 25px rgba(76, 201, 240, 0.25);
            position: relative;
            z-index: 2000;
        }

        .modal-title {
            font-family: "Inter", sans-serif;
            font-size: 28px;
            font-weight: 700;
            color: #004080;
            border-left: 4px solid #4CC9F0;
            padding-left: 10px;
            margin-bottom: 20px;
        }

        .close-btn {
            position: absolute;
            top: 15px;
            right: 20px;
            cursor: pointer;
            height: 28px;
            width: 28px;
        }

        .add-btn {
            padding: 10px 20px;
            border: none;
            background: #2bb4f4;
            color: white;
            border-radius: 6px;
            cursor: pointer;
        }

        .item-name-input,
        .modal-input,
        .dropdown-category-btn {
            background: #f9fcff;
            border: 1px solid #d0e6f9;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 14px;
            color: #003366;
            width: 100%;
        }

        .item-name-input:focus,
        .modal-input:focus,
        .dropdown-category-btn:focus {
            border-color: #4CC9F0;
            outline: none;
            box-shadow: 0 0 5px rgba(76, 201, 240, 0.4);
        }

        .two-column {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 7%;
            margin: 15px 0;
        }

        .text-area-css {
            border: 1px solid #d0e6f9;
            width: 92%;
            display: block;
            font-family: "Inter", sans-serif;
        }

        .show {
            display: block;
        }

        .min-quantity-content {
            display: flex;
            justify-content: space-between;
            gap: 5%;
        }

        .dropdown-category-btn {
            padding: 10px 14px;
            border: 1px solid #d0e6f9;
            border-radius: 8px;
            background: #f4faff;
            cursor: pointer;
            width: 100%;
            color: #003366;
            /* text-align: left; */
            font-size: 14px;
            white-space: nowrap;
            margin-bottom: 15px;
            cursor: pointer;
        }

        .modal-input {
            /* flex: 1; */
            border: 1px solid #d0e6f9;
            border-radius: 6px;
            font-size: 14px;
            -moz-appearance: textfield;
            margin-bottom: 15px;
        }

        .set-alarm-btn {
            width: auto;
            height: 40px;
            border: 1px solid #d0e6f9;
            background-color: white;
            border-radius: 5px;
            border-style: none;
            display: flex;
            justify-items: flex-end;
            cursor: pointer;
        }

        .set-alarm-img {
            width: auto;
            height: 40px;
        }

        .set-alert-close-btn {
            position: absolute;
            top: 10px;
            right: 20px;
            cursor: pointer;
            height: 35px;
            width: 35px;
        }

        .set-alert-header {
            font-size: 25px;
        }

        .set-alert-text {
            font-family: "Inter", sans-serif;
            color: #003366;
            font-size: 16px;
            margin-bottom: 5px;
            font-weight: bolder;
            display: flex;
            align-items: center;
        }

        .set-alert-dropdown {
            width: 100%;
        }

        .set-alert-min-input {
            font-size: 14px;
            height: auto;
            padding: 0 10px;
            margin-bottom: 15px;
        }



        .submit-section {
            display: flex;
            justify-content: flex-end;
        }


        .add-btn:hover,
        .full-details-btn:hover,
        .set-alarm-btn:hover {
            background: #3a9ecb;
        }

        /* Alert Css */
        .site-alert {
            display: none;
            position: fixed;
            top: 20px;
            right: 20px;
            background: #1e1e1e;
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            z-index: 9999;
        }

        .site-alert.show {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .site-alert.success {
            border-left: 6px solid green;
        }

        .site-alert.error {
            border-left: 6px solid red;
        }

        .site-alert-close {
            background: transparent;
            border: none;
            color: white;
            font-size: 20px;
            cursor: pointer;
        }


        /* Dropbox */
        .upload-area {
            border: 2px dashed #6c63ff;
            border-radius: 10px;
            padding: 30px;
            background: #f9fcff;
            text-align: center;
            cursor: pointer;
            position: relative;
            /* important so nothing overlaps */
            z-index: 10;
            /* make sure it’s clickable */
        }

        .upload-area.dragover {
            background: #e6e6ff;
            border-color: #4522F3;
        }


        .manual-alert-content {
            margin-top: 10px;
            padding: 10px;
            background: #fff;
            /* white background */
            border-radius: 8px;
            color: #000;
            /* switch text to black for contrast */
        }

        #userSearch {
            width: 100%;
            padding: 6px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .user-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 8px;
        }

        .user-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        /* Edit Modal */
        .edit-modal-content {
            background: #ffffff;
            width: 70%;
            padding: 20px 50px;
            border-radius: 20px;
            position: relative;
            border: 1px solid #d0e6f9;
            box-shadow: 0 8px 25px rgba(76, 201, 240, 0.25);
        }

        .edit-modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.5rem 1rem;
            margin: 2%;
        }

        .edit-modal-body {
            /* background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25); */
            color: #003366;
            padding: 20px;
            border-radius: 10px;
            font-family: "Segoe UI", sans-serif;
        }

        .edit-row {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .edit-modal-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 20px;
            align-items: start;
        }

        .edit-modal-card {
            flex: 1;
            background: none;
            border: 1px solid #d0e6f9;
            border-radius: 8px;
            text-align: center;
            /* display: flex;
            align-items: center; */
        }

        .edit-modal-card .edit-card-label {
            font-size: 0.85rem;
            color: #003366;
        }

        .edit-modal-card .edit-card-value {
            font-size: 1.2rem;
            font-weight: bold;
        }

        .section-title {
            margin: 10px 0;
            font-size: 20px;
            color: #003366;
            text-align: left;
            font-family: "Inter", sans-serif;
        }

        .custom-field-input {
            display: flex;
            justify-content: flex-start;
        }

        .image-card {
            padding: 20px 50px;
        }

        .image-card .image-placeholder {
            border: 2px dashed #777;
            border-radius: 8px;
            width: 100%;
            height: 250px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            text-align: center;
            background: rgba(255, 255, 255, 0.05);
        }

        .image-placeholder.dragover {
            border-color: #007bff;
            background: rgba(0, 123, 255, 0.1);
        }

        .image-placeholder img {
            max-width: 90%;
            max-height: 200px;
            margin-bottom: 8px;
            display: block;
        }


        .image-card img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            margin-bottom: 8px;
        }

        .image-card p {
            font-size: 0.85rem;
            color: #555;
        }

        .custom-fields-card {
            padding: 15px 20px;
            border: 1px solid #d0e6f9;
            border-radius: 8px;
            background: #f8fbff;
        }

        .custom-field-input {
            display: flex;
            flex-direction: column;
            margin-bottom: 12px;
        }

        .custom-field-input .label {
            color: #003366;
            font-size: 0.9rem;
            margin-bottom: 5px;
            text-align: left;
        }

        .custom-field-input input {
            background: none;
            border: none;
            border-bottom: 1px solid #bbb;
            color: #003366;
            padding: 5px;
            width: 90%;
            outline: none;
        }

        .custom-field-input input:focus {
            border-bottom-color: #4cafef;
        }

        /* Edit header pen */
        .editable-header {
            display: inline-flex;
            ;
            align-items: center;
            border-bottom: 2px solid #007bff;
            padding-bottom: 5px;
            gap: 10px;
            width: 100%;
        }

        .editable-header span {
            flex: 1;
            font-size: 1.3rem;
            color: #003366;
        }

        .editable-header input {
            flex: 1;
            font-size: 1.3rem;
            border: none;
            border-bottom: 2px solid #007bff;
            outline: none;
            color: #003366;
        }

        .pen-icon {
            cursor: pointer;
            width: 20px;
            height: 20px;
        }


        .stretch {
            align-items: stretch;
        }

        .edit-input {
            background: none;
            border: none;
            border-bottom: 1px solid #777;
            color: #003366;
            outline: none;
        }

        /* #edit-modal.view-mode .title-input {
            border-bottom: none;
            pointer-events: none;
            cursor: default;
        } */

        .title-input {
            border: none;
            outline: none;
            width: 100%;
            text-align: left;
        }

        .title-input:focus {
            border-bottom: 1px solid #4cafef;
        }

        /* Delete Modal */
        .delete-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2000;
        }

        .delete-modal-content {
            background: #fff;
            border-radius: 10px;
            width: 400px;
            max-width: 90%;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            animation: fadeIn 0.2s ease-in-out;
        }

        .delete-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid #ddd;
        }

        .delete-modal-header h3 {
            margin: 0;
            font-size: 18px;
        }

        .delete-modal-body {
            padding: 1rem;
            font-size: 15px;
            color: #333;
            font-family: "Inter", sans-serif;
        }

        .delete-modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            padding: 1rem;
            border-top: 1px solid #ddd;
        }

        .cancel-btn,
        .confirm-btn {
            padding: 8px 16px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-weight: bold;
        }

        .cancel-btn {
            background: #eee;
            color: #333;
        }

        .confirm-btn {
            background: #e63946;
            color: #fff;
        }

        .delete-close-btn {
            height: 28px;
            width: 28px;
            border: none;
            background: none;
            font-size: 20px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div id="siteAlert" class="site-alert">
        <div class="site-alert-content">
            <svg id="alertIcon" class="site-alert-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24"></svg>
            <div class="site-alert-text">
                <strong id="alertTitle"></strong>
                <p id="alertMsg"></p>
            </div>
            <button id="alertClose" class="site-alert-close">&times;</button>
        </div>
    </div>

    <div class="container-fluid">
        <div class="bg-light-ellipse ellipse1"></div>
        <div class="bg-light-ellipse ellipse2"></div>
        <div class="row">
            <!-- Start of Sidebar -->
            <div class="sidebar">
                <div class="logo-section">
                    <img src="./images/logo.png" alt="Company logo" id="company-logo">
                    <h2 id="company-name">J&JK METS</h2>
                </div>

                <div class="nav-list-container">
                    <ul class="nav-list">
                        <li class="nav-inactive" id="dashboard">
                            <img src="./images/dashboard.png" alt="Dashboard Icon" class="list-icon">
                            <a href="./dashboard_sale.php" class="nav-listname">Dashboard</a>
                        </li>

                        <li class="nav-active submenu-toggle" id="reagents" style="padding-right: 5px;">
                            <img src="./images/flask.png" alt="Flask Icon" class="list-icon">
                            <a href="#" class="nav-listname">Reagents</a>
                        </li>
                        <div class="submenu">
                            <a href="#" class="reagent-name submenu-active">Chemistry</a>
                            <a href="./hematology.php" class="reagent-name submenu-inactive">Hematology</a>
                            <a href="./immunology.php" class="reagent-name submenu-inactive">Immunology</a>
                        </div>
                        <li class="nav-inactive" style="padding-right: 30px;">
                            <img src="./images/history.png" alt="Inventory Icon" class="list-icon">
                            <a href="./inventory.php" class="nav-listname">Inventory</a>
                        </li>
                        <li class="nav-inactive" style="padding-right: 30px;">
                            <img src="./images/client.png" alt="Client Icon" class="list-icon">
                            <a href="calendar.php" class="nav-listname">Calendar</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- End of Sidebar -->

        <!-- Start of Header -->
        <div class="header">
            <h1 id="header-title">Chemistry</h1>

            <div class="header-actions">
                <button class="icon-btn" aria-label="Notifications">
                    <img src="./images/notification.png" alt="Notification Bell" class="list-icon">
                </button>

                <button class="icon-btn" aria-label="Profile">
                    <img class="avatar" src="./images/no_profile.jpg" alt="Profile">
                    <p id="username">User123</p>
                    <img id="dropdown-icon" src="./images/dropdown.png" alt="Dropdown icon">
                </button>
            </div>

        </div>
        <!-- End of Header -->

        <main>
            <img src="./images/logo-nobg.png" alt="background logo" class="logo-background">

            <!-- Searchbar and Add Button -->
            <div class="add-search-section">
                <div class="search-container">
                    <img src="./images/search_icon.png" alt="Search_Icon" class="searchbar-icon">
                    <input type="text" class="searchbar-reagent" placeholder="Search Chemistry Reagents">
                </div>
                <div class="add-item-section">
                    <button id="add-modal" class="add-item-btn">
                        + ADD ITEM
                    </button>
                </div>
            </div>

            <!-- Modal Add Reagent Form -->
            <div id="modal" class="modal-overlay">
                <div class="modal-content">
                    <div class="modal-header">
                        <img src="./images/close.png" alt="close-icon" id="form-close-btn" class="close-btn">
                        <h2 class="modal-title">ADD ITEM</h2>
                    </div>
                    <form id="mainForm" action="./add_reagents.php" method="POST" enctype="multipart/form-data">
                        <!-- Reagent Name -->
                        <input type="text" name="reagent_name" id="reagent_name" class="item-name-input" placeholder="Name*" required>

                        <div class="two-column">
                            <!-- Category -->
                            <select id="category" name="category" class="dropdown-category-btn" required>
                                <option value="" disabled selected>Select Category*</option>
                                <option value="reagents">Reagents</option>
                                <option value="calibrators">Calibrators</option>
                                <option value="controls">Controls</option>
                                <option value="electrolyte">Electrolyte</option>
                                <option value="consumables">Consumables</option>
                            </select>
                            <input type="hidden" name="reagent_type" id="reagent_type" value="chemistry">


                            <!-- Minimum quantity -->
                            <div class="min-quantity-content">
                                <input type="number" name="min-quantity" id="min_quantity_main" class="modal-input"
                                    placeholder="Min Level">
                                <button type="button" id="set-alert" class="set-alarm-btn">
                                    <img class="set-alarm-img" src="./images/set-alarm.png" alt="add-alarm icon">
                                </button>
                            </div>
                        </div>

                        <!-- Set alert modal -->
                        <div id="set-alert-modal" class="modal-overlay">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <img src="./images/close.png" alt="close-icon" id="set-alert-close-btn" class="close-btn">
                                    <h2 class="modal-title set-alert-header">Set Minimum Quantity Alert</h2>
                                </div>

                                <div class="two-column">
                                    <p class="set-alert-text">Alert Me When Quantity is:</p>
                                    <p class="set-alert-text">Min Level</p>
                                </div>

                                <div class="two-column">
                                    <select id="alert_condition" name="alert_condition" class="dropdown-category-btn set-alert-dropdown">
                                        <option value="equal">Equal to Minimum Level</option>
                                        <option value="below">Below Minimum Level</option>
                                        <option value="at_or_below">At or Below Minimum Level</option>
                                        <option value="above">Above Minimum Level</option>
                                    </select>

                                    <input type="number" name="alert_min_quantity" id="min_quantity_modal"
                                        class="modal-input set-alert-min-input" placeholder="Min Level">
                                </div>

                                <div class="submit-section">
                                    <!-- Just closes modal, doesn't submit -->
                                    <button type="button" id="confirmSetAlert" class="add-btn" style="margin-top: 50px;">Confirm</button>
                                </div>
                            </div>
                        </div>

                        <hr style="opacity: 0.4;">
                        <!-- Dropdown for image -->
                        <div id="upload-area" class="upload-area">
                            <p>Drag & Drop image here or click to upload</p>
                            <input type="file" id="fileInput" name="image" accept="image/*" hidden>
                            <img id="preview" alt="Preview" style="width:25px; height:25px;  margin-top:10px; display:none;">
                        </div>
                        <hr style="opacity: 0.4;">

                        <div class="two-column">
                            <!-- Item desc -->
                            <input type="text" class="modal-input" name="item_description" placeholder="Item Description">
                            <!-- Test/Kit No. -->
                            <input type="text" class="modal-input" name="test_kit" placeholder="Test/Kit No.">
                        </div>

                        <!-- Packaging Details -->
                        <textarea class="upload-area text-area-css" name="packaging" placeholder="Packaging details (e.g. R1 : 10 x 40 mL R2 : 10 x 40 mL)" rows="2"></textarea>

                        <div class="submit-section">
                            <button type="submit" class="add-btn submit-section">Submit</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sort Btn -->
            <div class="sort-section">
                <div class="catalog">
                    <button class="catalog-btn"><img class="catalog-icon" src="./images/catalog.png"
                            alt="catalog-icon"></button>
                </div>
            </div>

            <!-- Stats info -->
            <div class="info-section">
                <div class="info-text">Items: <?php echo $total_reagents; ?></div>
                <div class="info-text">Total Quantity: <?php echo $total_quantity; ?> stocks</div>
            </div>

            <!-- Reagent Cards -->
            <div class="chemistry-items">
                <div class="item-row">
                    <?php while ($row = $result_card_value->fetch_assoc()): ?>
                        <div class="item-card">
                            <img src="<?php echo $row['reagent_img'] ?? './images/no_available_img.jpeg'; ?>" alt="" class="item-img">
                            <p class="item-name"><?php echo htmlspecialchars($row['reagent_name']); ?></p>
                            <p class="item-name text-gradient"><?php echo htmlspecialchars($row['category']); ?></p>
                            <div class="item-stock item-info-format">
                                <img src="./images/item-stock.png" alt="" class="item-info-img">
                                <p class="item-info-text"><?php echo $row['stock_count']; ?> stock</p>
                            </div>
                            <div class="customize-card">
                                <div class="edit-card">
                                    <button class="open-edit-modal customize-btn"
                                        data-id="<?php echo $row['reagent_id']; ?>"
                                        data-name="<?php echo htmlspecialchars($row['reagent_name']); ?>"
                                        data-min="<?php echo $row['min_quantity']; ?>"
                                        data-category="<?php echo $row['category']; ?>"
                                        data-img="./uploads/<?php echo $row['reagent_img'] ?? 'no_available_img.jpeg'; ?>"
                                        data-desc="<?php echo htmlspecialchars($row['item_description']); ?>"
                                        data-kit="<?php echo htmlspecialchars($row['test_kit']); ?>"
                                        data-pack="<?php echo htmlspecialchars($row['packaging']); ?>">
                                        <img class="customize-icon" src="./images/edit.png" alt="edit-icon">
                                    </button>
                                </div>
                                <div class="edit-card">
                                    <button class="customize-btn">
                                        <img class="customize-icon" src="./images/trash.png" alt="edit-icon" onclick="openDeleteModal(<?php echo $row['reagent_id'] ?>)">
                                    </button>
                                </div>
                                <div class="view-stock-card">
                                    <button class="view-stock-btn" onclick="setReagentID('<?php echo $row['reagent_id']; ?>')">
                                        View Stock
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>

            <!-- Edit Mdoal -->
            <div id="edit-modal" class="modal-overlay" style="display:none;">
                <div class="edit-modal-content">
                    <div class="delete-section">
                        <img src="./images/close.png" alt="close-icon" id="edit-close-btn" class="close-btn">
                    </div>

                    <form id="editForm" method="POST" action="update_reagents.php" enctype="multipart/form-data">
                        <input type="hidden" name="reagent_id" id="reagentIdInput">

                        <!-- Header -->
                        <div class="editable-header edit-modal-header" style="width: 33%;">
                            <span id="reagentNameDisplay">Reagent Name</span>
                            <input type="text" name="reagent_name" id="reagentNameInput" class="title-input" style="display:none;">
                            <img src="./images/edit_pen.png" alt="edit" id="penReagentName" class="pen-icon">
                        </div>

                        <div class="edit-modal-body">
                            <!-- Row 1 -->
                            <div class="edit-row">
                                <!-- Quantity (readonly) -->
                                <div class="edit-modal-card">
                                    <p class="edit-card-label">Quantity</p>
                                    <span id="quantityDisplay">0</span>
                                    <input type="hidden" name="quantity" id="quantityInput">
                                </div>

                                <!-- Min Level (editable with pen) -->
                                <div class="edit-modal-card">
                                    <p class="edit-card-label">Min Level</p>
                                    <div class="editable-header" style="width: 30%;">
                                        <span id="minLevelDisplay">0</span>
                                        <input type="number" name="min_quantity" id="minLevelInput" class="edit-input" style="display:none; width: 30%;">
                                        <img src="./images/edit_pen.png" alt="edit" id="penMinLevel" class="pen-icon">
                                    </div>
                                </div>

                                <!-- Category (select) -->
                                <div class="edit-modal-card">
                                    <p class="edit-card-label">Category</p>
                                    <select id="categorySelect" name="category" class="dropdown-category-btn" required>
                                        <option value="" disabled>Select Category*</option>
                                        <option value="reagents">Reagents</option>
                                        <option value="calibrators">Calibrators</option>
                                        <option value="controls">Controls</option>
                                        <option value="electrolyte">Electrolyte</option>
                                        <option value="consumables">Consumables</option>
                                    </select>
                                    <input type="hidden" name="reagent_type" id="reagent_type" value="chemistry">
                                </div>
                            </div>

                            <!-- Row 2 -->
                            <div class="edit-modal-row two-column stretch">
                                <!-- Reagent Image -->
                                <div class="edit-modal-card image-card">
                                    <h3 class="section-title">Reagent Image</h3>
                                    <div id="upload-area-edit" class="image-placeholder">
                                        <img id="preview-edit" src="./images/no_available_img.jpeg" alt="Reagent image">
                                        <p>Click or drag an image here</p>
                                    </div>
                                    <input type="file" name="image" id="fileInput-edit" style="display:none;" accept="image/*">
                                </div>



                                <!-- Custom Fields -->
                                <div class="edit-modal-card custom-fields-card">
                                    <h3 class="section-title">Custom Fields</h3>
                                    <div class="custom-field-input">
                                        <span class="label">Item Description</span>
                                        <input type="text" name="item_description" id="itemDescriptionInput">
                                    </div>
                                    <div class="custom-field-input">
                                        <span class="label">Test/Kit No.</span>
                                        <input type="text" name="test_kit" id="testKitInput">
                                    </div>
                                    <div class="custom-field-input">
                                        <span class="label">Packaging Details</span>
                                        <input type="text" name="packaging" id="packagingInput">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="modal-footer">
                            <button type="submit" class="save-btn">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Delete Modal -->
            <div id="delete-modal" class="delete-modal-overlay" style="display: none;">
                <div class="delete-modal-content">
                    <div class="delete-modal-header">
                        <h3>Confirm Deletion</h3>
                        <img src="./images/close.png" alt="close-icon" onclick="closeDeleteModal()" class="delete-close-btn">
                    </div>

                    <div class="delete-modal-body">
                        <p>Are you sure you want to delete this reagent? This action cannot be undone.</p>
                    </div>

                    <div class="delete-modal-footer">
                        <button class="cancel-btn" onclick="closeDeleteModal()">Cancel</button>
                        <button class="confirm-btn" onclick="deleteModalBackend()">Delete</button>
                    </div>
                </div>
            </div>
        </main>
    </div>


    <script>
        // Collapse department reagent 
        document.getElementById("reagents").addEventListener("click", function(e) {
            e.preventDefault();
            const submenu = this.nextElementSibling;
            submenu.classList.toggle("hide");
        });

        function setReagentID(reagentID) {
            location.href = "./set_reagent.php?id=" + reagentID;
        }

        // Add Modal
        const modal = document.getElementById("modal");
        const addModal = document.getElementById("add-modal");
        const formCloseBtn = document.getElementById("form-close-btn");

        // Open Add modal
        addModal.addEventListener("click", () => {
            modal.style.display = "flex";
        });

        // Close Add modal
        formCloseBtn.addEventListener("click", () => {
            modal.style.display = "none";
        });

        // Close add modal if clicked outside
        window.addEventListener("click", (e) => {
            if (e.target === modal) {
                modal.style.display = "none";
            }
        });

        // Edit Modal
        const editModal = document.getElementById("edit-modal");
        const editModalBtn = document.querySelector(".open-edit-modal");
        const editCloseBtn = document.getElementById("edit-close-btn");

        // Open Edit modal
        document.querySelectorAll(".open-edit-modal").forEach(button => {
            button.addEventListener("click", (e) => {
                e.preventDefault();

                const reagentId = button.getAttribute("data-id");

                editModal.style.display = "flex";

                console.log("Editing reagent ID:", reagentId);
            });
        });

        // Close edit modal
        editCloseBtn.addEventListener("click", () => {
            editModal.style.display = "none";
        });

        // Close edit modal if clicked outside 
        window.addEventListener("click", (e) => {
            if (e.target === modal) {
                editModal.style.display = "none";
            }
        });

        // Alert Modal
        const setAlertBtn = document.getElementById("set-alert");
        const setAlertModal = document.getElementById("set-alert-modal");
        const setAlertCloseBtn = document.getElementById("set-alert-close-btn");

        // Open alert modal
        setAlertBtn.addEventListener("click", () => {
            setAlertModal.style.display = "flex";
        });

        // Close alert modal
        setAlertCloseBtn.addEventListener("click", () => {
            setAlertModal.style.display = "none";
        });

        // Close alert modal if clicked outside
        window.addEventListener("click", (e) => {
            if (e.target === modal) {
                setAlertModal.style.display = "none";
            }
        });

        // Syncing two input of min-quantity
        const minMain = document.getElementById("min_quantity_main");
        const minModal = document.getElementById("min_quantity_modal");

        minMain.addEventListener("input", () => {
            minModal.value = minMain.value;
        });

        minModal.addEventListener("input", () => {
            minMain.value = minModal.value;
        });

        // Set alert confirm btn onclick
        const confirmBtn = document.getElementById("confirmSetAlert");

        confirmBtn.addEventListener("click", () => {
            setAlertModal.style.display = "none";
        });


        // Edit Modal
        document.querySelectorAll('.open-edit-modal').forEach(btn => {
            btn.addEventListener('click', () => {
                // Show modal
                const modal = document.getElementById('edit-modal');
                modal.style.display = 'flex';

                // Fill hidden ID
                document.getElementById('reagentIdInput').value = btn.dataset.id;

                // Reagent name
                document.getElementById('reagentNameDisplay').textContent = btn.dataset.name;
                document.getElementById('reagentNameInput').value = btn.dataset.name;

                // Quantity
                // document.getElementById('quantityDisplay').textContent = btn.dataset.quantity;
                // document.getElementById('quantityInput').value = btn.dataset.quantity;

                // Min level
                document.getElementById('minLevelDisplay').textContent = btn.dataset.min || "0";
                document.getElementById('minLevelInput').value = btn.dataset.min || "";

                // Category
                document.getElementById('categorySelect').value = btn.dataset.category;

                // Image
                const preview = document.getElementById('preview-edit');
                preview.src = btn.dataset.img;
                preview.style.display = 'block';



                // Custom fields
                document.getElementById('itemDescriptionInput').value = btn.dataset.desc || "";
                document.getElementById('testKitInput').value = btn.dataset.kit || "";
                document.getElementById('packagingInput').value = btn.dataset.pack || "";
            });
        });

        // Close modal
        document.getElementById('edit-close-btn').addEventListener('click', () => {
            document.getElementById('edit-modal').style.display = 'none';
        });

        // Edit Modal input pen
        function makeEditable(displayId, inputId, penId) {
            const display = document.getElementById(displayId);
            const input = document.getElementById(inputId);
            const pen = document.getElementById(penId);

            pen.addEventListener('click', () => {
                display.style.display = 'none';
                input.style.display = 'inline-block';
                input.focus();
                pen.style.display = 'none';
            });

            input.addEventListener('blur', () => {
                display.textContent = input.value || display.textContent;
                display.style.display = 'inline';
                input.style.display = 'none';
                pen.style.display = 'inline';
            });
        }

        // Reagent name
        makeEditable('reagentNameDisplay', 'reagentNameInput', 'penReagentName');
        // Min level
        makeEditable('minLevelDisplay', 'minLevelInput', 'penMinLevel');


        // Notification Alerts Chatgpt
        document.addEventListener("DOMContentLoaded", () => {
            const alertBox = document.getElementById("siteAlert");
            const alertIcon = document.getElementById("alertIcon");
            const alertTitle = document.getElementById("alertTitle");
            const alertMsg = document.getElementById("alertMsg");
            const alertClose = document.getElementById("alertClose");

            const params = new URLSearchParams(window.location.search);
            const status = params.get("status");
            const message = params.get("message");

            console.log("Status param:", status);
            console.log("Message param:", message);

            if (status) {
                alertBox.classList.add("show", status);

                if (status === "success") {
                    alertTitle.textContent = "Success";
                    alertMsg.textContent = message || "Reagent added successfully.";
                    alertIcon.innerHTML = `
    <circle cx="12" cy="12" r="10" stroke="green" stroke-width="2" fill="none" />
    <path d="M8 12l2 2 4-4" stroke="green" stroke-width="2" fill="none" />`;
                } else if (status === "success-edit") {
                    alertTitle.textContent = "Success";
                    alertMsg.textContent = message || "Reagent updated successfully.";
                    alertIcon.innerHTML = `
    <circle cx="12" cy="12" r="10" stroke="green" stroke-width="2" fill="none" />
    <path d="M8 12l2 2 4-4" stroke="green" stroke-width="2" fill="none" />`;
                } else if (status === "success-delete") {
                    alertTitle.textContent = "Success";
                    alertMsg.textContent = message || "Reagent deleted successfully.";
                    alertIcon.innerHTML = `
    <circle cx="12" cy="12" r="10" stroke="green" stroke-width="2" fill="none" />
    <path d="M8 12l2 2 4-4" stroke="green" stroke-width="2" fill="none" />`;
                } else {
                    alertTitle.textContent = "Error";
                    alertMsg.textContent = message || "Something went wrong.";
                    alertIcon.innerHTML = `
    <circle cx="12" cy="12" r="10" stroke="red" stroke-width="2" fill="none" />
    <line x1="9" y1="9" x2="15" y2="15" stroke="red" stroke-width="2" />
    <line x1="15" y1="9" x2="9" y2="15" stroke="red" stroke-width="2" />`;
                }
            }

            alertClose?.addEventListener("click", () => {
                alertBox.classList.remove("show");
            });

            if (status) {
                setTimeout(() => {
                    alertBox.classList.remove("show");
                }, 5000);
            }
        });


        // Add Reagent Dropbox Chatgpt
        document.addEventListener('DOMContentLoaded', () => {
            const uploadArea = document.getElementById('upload-area');
            const fileInput = document.getElementById('fileInput');
            const preview = document.getElementById('preview');
            let objectUrl;

            // stop browser from opening file when dropped
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(evt =>
                uploadArea.addEventListener(evt, e => e.preventDefault())
            );

            // click triggers file input
            uploadArea.addEventListener('click', () => fileInput.click());

            uploadArea.addEventListener('dragover', () => uploadArea.classList.add('dragover'));
            uploadArea.addEventListener('dragleave', () => uploadArea.classList.remove('dragover'));

            uploadArea.addEventListener('drop', (e) => {
                uploadArea.classList.remove('dragover');
                const file = e.dataTransfer.files?.[0];
                if (file) setFile(file);
            });

            fileInput.addEventListener('change', () => {
                const file = fileInput.files?.[0];
                if (file) setFile(file);
            });

            function setFile(file) {
                const dt = new DataTransfer();
                dt.items.add(file);
                fileInput.files = dt.files;

                if (objectUrl) URL.revokeObjectURL(objectUrl);
                objectUrl = URL.createObjectURL(file);

                preview.src = objectUrl;
                preview.style.display = 'block';

                uploadArea.querySelector("p").textContent = file.name;
            }
        });

        // Edit Modal Dropbox Chatgpt
        document.addEventListener('DOMContentLoaded', () => {
            const uploadArea = document.getElementById('upload-area-edit');
            const fileInput = document.getElementById('fileInput-edit');
            const preview = document.getElementById('preview-edit');
            let objectUrl;

            if (!uploadArea || !fileInput || !preview) return; // safety

            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(evt => {
                uploadArea.addEventListener(evt, e => e.preventDefault());
            });

            uploadArea.addEventListener('click', () => fileInput.click());
            uploadArea.addEventListener('dragover', () => uploadArea.classList.add('dragover'));
            uploadArea.addEventListener('dragleave', () => uploadArea.classList.remove('dragover'));

            uploadArea.addEventListener('drop', (e) => {
                uploadArea.classList.remove('dragover');
                const file = e.dataTransfer.files?.[0];
                if (file) setFile(file);
            });

            fileInput.addEventListener('change', () => {
                const file = fileInput.files?.[0];
                if (file) setFile(file);
            });

            function setFile(file) {
                const dt = new DataTransfer();
                dt.items.add(file);
                fileInput.files = dt.files;

                if (objectUrl) URL.revokeObjectURL(objectUrl);
                objectUrl = URL.createObjectURL(file);

                preview.src = objectUrl;
                preview.style.display = 'block';
                uploadArea.querySelector("p").textContent = file.name;
            }
        });

        // Delete Modal
        function openDeleteModal(deleteReagentID) {
            reagentToDelete = deleteReagentID;
            document.getElementById("delete-modal").style.display = "flex";
        }

        function closeDeleteModal() {
            document.getElementById("delete-modal").style.display = "none";
        }

        function deleteModalBackend(deleteReagentID) {
            if (reagentToDelete) {
                // redirect to backend delete
                location.href = "./delete_reagent.php?id=" + reagentToDelete;
            } else {
                alert("No reagent selected for deletion!");
            }
        }
    </script>
</body>

</html>