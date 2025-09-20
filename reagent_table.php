<?php

include "db.php";

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ./login.html?status=error&message=" . urlencode("Please log in first"));
}

if (!isset($_SESSION['current_reagent_id'])) {
    header("Location: chemistry.php?status=error&message=No+reagent+selected");
    exit();
}

$reagent_id = $_SESSION['current_reagent_id'];

$stmt = $conn->prepare("SELECT * FROM reagents WHERE reagent_id = ?");
$stmt->bind_param("i", $reagent_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_array();

$sql_reagent_stock = "SELECT * FROM reagent_stock WHERE reagent_id = $reagent_id";
$result_reagent_stock = $conn->query($sql_reagent_stock);

$sql_num_stock = "SELECT COUNT(*) AS num_stock FROM reagent_stock";
$result_num_stock = $conn->query($sql_num_stock);
$num_stocks = ($result_num_stock) ? $result_num_stock->fetch_assoc()['num_stock'] : 0;

$sql_qty = "SELECT SUM(quantity) AS total_quantity FROM reagent_stock";
$result_qty = $conn->query($sql_qty);
$total_stocks = ($result_qty) ? $result_qty->fetch_assoc()['total_quantity'] : 0;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>J&JMETS</title>

    <style>
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

        .bg-light-ellipse {
            position: fixed;
            width: 480px;
            height: 477px;
            border-radius: 50%;
            background: linear-gradient(#0090ff 34%, #00cfff 91%);
            filter: blur(150px);
            z-index: 0;
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
        }

        .ellipse2 {
            left: 1360px;
            top: 339px;
        }

        body {
            background-color: #ffffffff;
            padding-left: 330px;
            margin: 0;
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

        .submenu.show {
            display: flex;
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

        .submenu-inactive:hover {
            background-color: rgba(255, 255, 255, 0.2);
            cursor: pointer;
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

        ul.breadcrumb {
            list-style: none;
        }

        ul.breadcrumb li {
            display: inline;
            font-size: 18px;
            padding: 0;
            margin: 0;
        }

        ul.breadcrumb li+li:before {
            padding: 8px;
            color: black;
            content: "/\00a0";
        }

        ul.breadcrumb li a {
            color: #0275d8;
            text-decoration: none;
        }

        ul.breadcrumb li a:hover {
            color: #01447e;
            text-decoration: underline;
        }

        #header-title {
            /* grid-row: 1 / span 2; */
            display: inline-block;
            margin: 0;
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

        .searchbar {
            height: 36px;
            width: 250px;
            padding: 0 10px;
            border: none;
            border-radius: 8px;
            outline: none;
            background-color: #D9D9D9;
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

        /* Main Content */
        /* Main Content */
        /* Main Content */
        .main-content {
            position: relative;
            padding: 0px 20px;
            height: auto;
            /* background-color: white; */
            grid-template-rows: auto auto;
        }

        /* Search and Add Section */
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

        .searchbar-icon {
            height: 20px;
            width: 20px;
            position: absolute;
            left: 12px;
            top: 45%;
            transform: translateY(-50%);
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

        /* Stat Section */
        .info-section {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr;
        }

        .info-text {
            color: grey;
            font-family: "Inter", sans-serif;
            font-size: 15px;
            margin-left: 10px;
        }

        /* Table Section */
        .table-container {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            max-width: 1000px;
            /* margin: auto; */
            margin: 2% 10px;
        }

        .table-wrapper {
            max-height: 500px;
            overflow-y: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #0090FF;
            position: sticky;
            top: 0;
            z-index: 1;
        }

        th,
        td {
            padding: 12px 16px;
            text-align: left;
            font-size: 14px;
            border-bottom: 1px solid #eee;
        }

        th {
            font-weight: 600;
            color: #444;
        }

        tr:hover td {
            background: #dfe9ffff;
        }

        .status {
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
        }

        .status.ok {
            background: #d4f7dc;
            color: #187a3c;
        }

        .status.soon {
            background: #fff5cc;
            color: #b58900;
        }

        .status.expired {
            background: #ffd6d6;
            color: #a30000;
        }

        .actions {
            display: flex;
            gap: 10px;
        }

        .actions .btn {
            background: #4cc9f0;
            padding: 5px 10px;
            border-radius: 4px;
            color: white;
            font-size: 12px;
            border: none;
            cursor: pointer;
        }

        .actions .add {
            background-color: #29ae48ff;
        }

        .actions .minus {
            background-color: #e6bb0eff;
        }

        .actions .edit {
            background-color: #4cc9f0;
        }

        .actions .delete {
            background-color: #e63946;
        }

        .actions .btn:hover {
            opacity: 0.8;
        }

        /* drawer */
        .drawer {
            position: fixed;
            top: 0;
            right: 0;
            width: 400px;
            height: 100%;
            background: #fff;
            box-shadow: -2px 0 8px rgba(0, 0, 0, 0.2);
            transform: translateX(100%);
            transition: transform 0.3s ease-in-out;
            z-index: 1000;
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont,
                "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif;
        }

        .drawer.open {
            transform: translateX(0);
        }

        .drawer-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid #ddd;
        }

        .drawer form {
            padding: 8%;
        }

        .drawer form label {
            display: block;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .drawer form input,
        .drawer form select {
            width: 95%;
            padding: 4% 3%;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
        }

        .drawer-submit-btn {
            width: 100%;
            background-color: #0090FF;
            color: white;
            padding: 3% 0;
            border-radius: 10px;
        }

        .title {
            border-left: 4px solid #4CC9F0;
            padding-left: 10px;
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
                            <a href="./chemistry.php" class="reagent-name submenu-active">Chemistry</a>
                            <a href="#" class="reagent-name submenu-inactive">Hematology</a>
                            <a href="#" class="reagent-name submenu-inactive">Immunology</a>
                        </div>
                        <li class="nav-inactive" style="padding-right: 30px;">
                            <img src="./images/history.png" alt="History Icon" class="list-icon">
                            <a href="" class="nav-listname">History</a>
                        </li>
                        <li class="nav-inactive" style="padding-right: 30px;">
                            <img src="./images/client.png" alt="Client Icon" class="list-icon">
                            <a href="" class="nav-listname">Clients</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- End of Sidebar -->

    <!-- Start of Header -->
    <div class="header">
        <ul class="breadcrumb">
            <li><a href="./chemistry.php" id="header-title" style="text-decoration: none;">Chemistry</a></li>
            <li><a href="#"><?php echo $row['reagent_name'] ?></a></li>
        </ul>

        <div class="header-actions">
            <input type="text" class="searchbar" placeholder="Search…">

            <button class="icon-btn" aria-label="Messages">
                <img src="./images/message.png" alt="Message icon" class="list-icon">
            </button>

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


    <!-- Main content -->
    <main class="main-content">
        <img src="./images/logo-nobg.png" alt="background logo" class="logo-background">

        <!-- Search and Add Section -->
        <div class="add-search-section">
            <div class="search-container">
                <img src="./images/search_icon.png" alt="Search_Icon" class="searchbar-icon">
                <!-- Chemistry is a placeholder -->
                <input type="text" class="searchbar-reagent" placeholder="Search Stocks for [Chemistry]">
            </div>
            <div class="add-item-section">
                <button onclick="openDrawer('drawer')" class="add-item-btn">
                    + ADD ITEM
                </button>
            </div>
        </div>

        <!-- Stats info -->
        <div class="info-section">
            <div class="info-text">Number of Rows: <?php echo $num_stocks; ?></div>
            <div class="info-text">Total Stock: <?php echo $total_stocks; ?> units</div>
            <div class="info-text">Expiring Soon: <?php echo '0'; ?> </div>
            <div class="info-text">Below Minimum: <?php echo '0'; ?> </div>
        </div>

        <!-- Reagent Table -->
        <div class="table-container">
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Lot No.</th>
                            <th>Reagent Name</th>
                            <th>Distributor</th>
                            <th>Date Arrived</th>
                            <th>Expiry Date</th>
                            <th>Expiry Status</th>
                            <th>Quantity</th>
                            <th>Action Bar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($stock = $result_reagent_stock->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($stock['lot_no']); ?></td>
                                <td><?php echo htmlspecialchars($row['reagent_name'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($stock['distributor']); ?></td>
                                <td><?php echo htmlspecialchars($stock['date_arrived']); ?></td>
                                <td><?php echo htmlspecialchars($stock['expiry_date']); ?></td>
                                <td><span class="status ok">OK</span></td>
                                <td><?php echo htmlspecialchars($stock['quantity']); ?></td>
                                <td class="actions">
                                    <button class="btn add" onclick="openDrawer('addStockDrawer')">+</button>
                                    <button class="btn minus" onclick="openDrawer('removeStockDrawer')">-</button>
                                    <button class="btn edit">Edit</button>
                                    <button class="btn delete">Delete</button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>

                </table>
            </div>
        </div>

        <!-- Drawer -->
        <div id="drawer" class="drawer">
            <div class="drawer-header">
                <h2 class="title">Add Stock</h2>
                <button onclick="closeDrawer('drawer')">&times;</button>
            </div>
            <form action="add_stock.php" method="post">
                <input type="hidden" name="reagent_id" value="<?php echo $reagent_id; ?>">

                <label>Reagent Name</label>
                <input type="text" value="<?php echo htmlspecialchars($row['reagent_name']); ?>" readonly>

                <label>Lot No.</label>
                <input type="text" name="lot_no" required>

                <label>Distributor</label>
                <select name="distributor" required>
                    <option value="" disabled selected>Select Distributor</option>
                    <option value="Distributor A">Distributor A</option>
                    <option value="Distributor B">Distributor B</option>
                </select>

                <label>Date Received</label>
                <input type="date" name="date_arrived">

                <label>Expiry Date</label>
                <input type="date" name="expiry_date">

                <label>Quantity</label>
                <input type="number" name="quantity">

                <button type="submit" class="drawer-submit-btn">Save</button>
            </form>
        </div>

        <!-- Add Drawer -->
        <!-- Add Stock Drawer -->
        <div id="addStockDrawer" class="drawer">
            <div class="drawer-header">
                <h3 class="title">Add Stock</h3>
                <button onclick="closeDrawer('addStockDrawer')">✖</button>
            </div>
            <form action="update_stock.php" method="POST">
                <!-- Identify reagent -->
                <input type="hidden" name="reagent_id" value="<?php echo $reagent_id; ?>">
                <!-- <input type="hidden" name="stock_id" value="<?php echo $stock_form['stock_id']; ?>"> -->

                <input type="hidden" name="action_type" value="add">

                <label>Quantity to Add</label>
                <input type="number" name="quantity" min="1" required>

                <label>Client</label>
                <select name="client">
                    <option value="" disabled selected>Select Client</option>
                    <option value="Client A">Client A</option>
                    <option value="Client B">Client B</option>
                </select>

                <label>Date Arrived</label>
                <input type="date" name="date_action" required>

                <button type="submit" class="drawer-submit-btn">Add Stock</button>
            </form>
        </div>

        <!-- Remove Stock Drawer -->
        <div id="removeStockDrawer" class="drawer">
            <div class="drawer-header">
                <h3 class="title">Remove Stock</h3>
                <button onclick="closeDrawer('removeStockDrawer')">✖</button>
            </div>
            <form action="update_stock.php" method="POST">
                <input type="hidden" name="reagent_id" value="<?php echo $reagent_id; ?>">
                 <!-- <input type="hidden" name="stock_id" value="<?php echo $stock['stock_id']; ?>"> -->
                <input type="hidden" name="action_type" value="remove">

                <label>Quantity to Remove</label>
                <input type="number" name="quantity" min="1" required>

                <label>Client</label>
                <select name="client">
                    <option value="" disabled selected>Select Client</option>
                    <option value="Client A">Client A</option>
                    <option value="Client B">Client B</option>
                </select>

                <label>Date Delivered</label>
                <input type="date" name="date_action" required>

                <button type="submit" class="drawer-submit-btn">Remove Stock</button>
            </form>
        </div>

    </main>

    <script>
        // Reagent Submenu 
        document.getElementById("reagents").addEventListener("click", function(e) {
            e.preventDefault();
            const submenu = this.nextElementSibling;
            submenu.classList.toggle("hide");
        });

        // Drawer

        function openDrawer(id) {
            document.getElementById(id).classList.add('open');
        }

        function closeDrawer(id) {
            document.getElementById(id).classList.remove('open');
        }

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
                    alertMsg.textContent = message || "Stock added successfully.";
                    alertIcon.innerHTML = `
    <circle cx="12" cy="12" r="10" stroke="green" stroke-width="2" fill="none" />
    <path d="M8 12l2 2 4-4" stroke="green" stroke-width="2" fill="none" />`;
                } else if (status === "success-update") {
                    alertTitle.textContent = "Success";
                    alertMsg.textContent = message || "Stock Quantity is updated successfully.";
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
    </script>
</body>

</html>