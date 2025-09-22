<?php

include "db.php";
session_start();
if (!isset($_SESSION['user_id'])) {
    // user is not logged in → kick them back to login
    header("Location: ./login.html?status=error&message=" . urlencode("Please log in first"));
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>J&JMETS</title>

    <style>
        /* Color aesthetic in the bg*/
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
            display: none;
            overflow: hidden;
            align-items: flex-start;
            flex-direction: column;
            margin: 0;
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

        /* Search and Filter Section */
        .filter-add-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            /* background-color: blue; */
            margin: 1% 2%;
        }

        .sort-alert {
            grid-column: 1/2;
        }

        .add-alert {
            grid-column: 2/-1;
            display: flex;
            justify-self: flex-end;
        }

        .filter-btn {
            width: auto;
            height: 40px;
            border-radius: 5px;
            background-color: #ffffffff;
            border: 1px solid black;
            padding: 0 20px;
            margin-top: 1%;
            margin-bottom: 2%;
            margin-right: 3%;
            color: black;
            font-size: 12px;
            font-weight: 800;
            text-align: center;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 3px;
        }

        .filter-btn-icon {
            height: 20px;
            width: 20px;
        }

        /* Expire Header */
        .expiry-header {
            font-family: "Inter", sans-serif;
            font-size: 16px;
            font-weight: bold;
            margin: 12px 2%;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Circle Color */
        .status-circle {
            width: 14px;
            height: 14px;
            border-radius: 50%;
            display: inline-block;
        }

        .status-circle.critical {
            background: #c62828;
        }

        .status-circle.major {
            background: #ef6c00;
        }

        .status-circle.minor {
            background: #f9a825;
        }

        .status-circle.others {
            background: #1565c0;
        }

        .expiry-table {
            width: 95%;
            margin: 0 2%;
            border-collapse: collapse;
            margin-bottom: 15px;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        .expiry-table th {
            background: #e8f4ff;
            text-align: left;
            padding: 10px 14px;
            font-weight: bold;
            font-size: 14px;
            color: #333;
        }

        .expiry-table td {
            padding: 12px 14px;
            font-size: 14px;
            color: #222;
        }

        .expiry-table tr:nth-child(even) {
            background: #f9fcff;
        }

        .expiry-tag {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
            color: #333;
        }

        .expiry-tag.critical {
            background: #ffebee;
            color: #c62828;
        }

        .expiry-tag.major {
            background: #fff8e1;
            color: #ef6c00;
        }

        .expiry-tag.minor {
            background: #fffde7;
            color: #f9a825;
        }

        .expiry-tag.others {
            background: #e3f2fd;
            color: #1565c0;
        }

        .expiry-table.hidden {
            display: none;
        }
    </style>
</head>

<body>
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

                        <li class="nav-inactive submenu-toggle" id="reagents" style="padding-right: 5px;">
                            <img src="./images/flask.png" alt="Flask Icon" class="list-icon">
                            <a href="#" class="nav-listname">Reagents</a>
                        </li>
                        <div class="submenu">
                            <a href="./chemistry.php" class="reagent-name submenu-inactive">Chemistry</a>
                            <a href="./hematology.php" class="reagent-name submenu-inactive">Hematology</a>
                            <a href="./immunology.php" class="reagent-name submenu-inactive">Immunology</a>
                        </div>
                        <li class="nav-inactive" style="padding-right: 30px;">
                            <img src="./images/history.png" alt="Inventory Icon" class="list-icon">
                            <a href="./inventory.php" class="nav-listname">Inventory</a>
                        </li>
                        <li class="nav-active" style="padding-right: 30px;">
                            <img src="./images/client.png" alt="Client Icon" class="list-icon">
                            <a href="#" class="nav-listname">Calendar</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- End of Sidebar -->

        <!-- Start of Header -->
        <div class="header">
            <h1 id="header-title">Calendar</h1>

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


        <!-- Main content -->
        <main class="main-content">
            <img src="./images/logo-nobg.png" alt="background logo" class="logo-background">

            <!-- Filter and Add Section -->
            <div class="filter-add-section">
                <div class="sort-alert">
                    <button class="filter-btn">
                        <img src="./images/calendar_icon.png" alt="Calendar_icon" class="filter-btn-icon">
                        Date - Date
                    </button>
                </div>

                <div class="add-alert">
                    <button class="filter-btn">
                        +Add
                    </button>
                </div>
            </div>

            <div class="expiry-section">
                <div class="expiry-header critical" onclick="toggleTable(this)">
                    <span class="status-circle critical"></span>
                    Expiring within a week (Critical)
                </div>
                <table class="expiry-table">
                    <thead>
                        <tr>
                            <th>Lot No.</th>
                            <th>Reagent Name</th>
                            <th>Quantity</th>
                            <th>Expiry</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Chemistry 1</td>
                            <td>100</td>
                            <td><span class="expiry-tag critical">Sep 25, 2025</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="expiry-section">
                <div class="expiry-header major" onclick="toggleTable(this)">
                    <span class="status-circle major"></span>
                    Expiring within a month (Major)
                </div>
                <table class="expiry-table">
                    <thead>
                        <tr>
                            <th>Lot No.</th>
                            <th>Reagent Name</th>
                            <th>Quantity</th>
                            <th>Expiry</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>2</td>
                            <td>Hematology 2</td>
                            <td>80</td>
                            <td><span class="expiry-tag major">Oct 12, 2025</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>



        </main>
    </div>

    <script>
        document.getElementById("reagents").addEventListener("click", function(e) {
            e.preventDefault(); // prevent the link from reloading
            const submenu = this.nextElementSibling;
            submenu.classList.toggle("show");
        });

        function toggleTable(header) {
            const table = header.nextElementSibling;
            table.classList.toggle("hidden");
        }
    </script>
</body>

</html>