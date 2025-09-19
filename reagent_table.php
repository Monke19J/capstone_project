<?php
// session_start();
// if (!isset($_SESSION['user_id'])) {
//     // user is not logged in → kick them back to login
//     header("Location: ./login.html?status=error&message=" . urlencode("Please log in first"));
// }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>J&JMETS</title>

    <!-- <link rel="stylesheet" href="./css/bootstrap.css"> -->
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

        .btn {
            border: none;
            background: #4cc9f0;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            cursor: pointer;
        }

        .btn.edit {
            background: #4cc9f0;
        }

        .btn.delete {
            background: #e63946;
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
            <li><a href="#">Reagent Name Stock</a></li>
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

        <!--  -->
        <div class="add-search-section">
            <div class="search-container">
                <img src="./images/search_icon.png" alt="Search_Icon" class="searchbar-icon">
                <!-- Chemistry is a placeholder -->
                <input type="text" class="searchbar-reagent" placeholder="Search Stocks for [Chemistry]">
            </div>
            <div class="add-item-section">
                <button id="add-modal" class="add-item-btn">
                    + ADD ITEM
                </button>
            </div>
        </div>

        <!-- Stats info -->
        <div class="info-section">
            <div class="info-text">Number of Rows: <?php echo '0'; ?></div>
            <div class="info-text">Total Stock: <?php echo '0'; ?> units</div>
            <div class="info-text">Expiring Soon: <?php echo '0'; ?> </div>
        </div>

        <!-- Reagent Table -->
        <div class="table-container">
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Lot No.</th>
                            <th>Reagent Name</th>
                            <th>Client</th>
                            <th>Date Delivered</th>
                            <th>Expiry Date</th>
                            <th>Expiry Status</th>
                            <th>Quantity</th>
                            <th>Action Bar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>001</td>
                            <td>Reagent Name</td>
                            <td>Client A</td>
                            <td>Oct 1, 2025</td>
                            <td>Dec 15, 2025</td>
                             <td><span class="status ok">OK</span></td>
                            <td>50 Units</td>  
                            <td class="actions">
                                <button class="btn edit">Edit</button>
                                <button class="btn delete">Delete</button>
                            </td>
                        </tr>
                        <tr>
                            <td>001</td>
                            <td>Reagent Name</td>
                            <td>Client A</td>
                            <td>Oct 1, 2025</td>
                            <td>Dec 15, 2025</td>
                             <td><span class="status ok">OK</span></td>
                            <td>50 Units</td>  
                            <td class="actions">
                                <button class="btn edit">Edit</button>
                                <button class="btn delete">Delete</button>
                            </td>
                        </tr>
                        <tr>
                            <td>001</td>
                            <td>Reagent Name</td>
                            <td>Client A</td>
                            <td>Oct 1, 2025</td>
                            <td>Dec 15, 2025</td>
                             <td><span class="status soon">Expired Soon</span></td>
                            <td>50 Units</td>  
                            <td class="actions">
                                <button class="btn edit">Edit</button>
                                <button class="btn delete">Delete</button>
                            </td>
                        </tr>
                        <tr>
                            <td>001</td>
                            <td>Reagent Name</td>
                            <td>Client A</td>
                            <td>Oct 1, 2025</td>
                            <td>Dec 15, 2025</td>
                             <td><span class="status ok">OK</span></td>
                            <td>50 Units</td>  
                            <td class="actions">
                                <button class="btn edit">Edit</button>
                                <button class="btn delete">Delete</button>
                            </td>
                        </tr>
                        <tr>
                            <td>001</td>
                            <td>Reagent Name</td>
                            <td>Client A</td>
                            <td>Oct 1, 2025</td>
                            <td>Dec 15, 2025</td>
                             <td><span class="status ok">OK</span></td>
                            <td>50 Units</td>  
                            <td class="actions">
                                <button class="btn edit">Edit</button>
                                <button class="btn delete">Delete</button>
                            </td>
                        </tr>
                        <tr>
                            <td>001</td>
                            <td>Reagent Name</td>
                            <td>Client A</td>
                            <td>Oct 1, 2025</td>
                            <td>Dec 15, 2025</td>
                             <td><span class="status ok">OK</span></td>
                            <td>50 Units</td>  
                            <td class="actions">
                                <button class="btn edit">Edit</button>
                                <button class="btn delete">Delete</button>
                            </td>
                        </tr>
                        <tr>
                            <td>001</td>
                            <td>Reagent Name</td>
                            <td>Client A</td>
                            <td>Oct 1, 2025</td>
                            <td>Dec 15, 2025</td>
                             <td><span class="status expired">Expired</span></td>
                            <td>50 Units</td>  
                            <td class="actions">
                                <button class="btn edit">Edit</button>
                                <button class="btn delete">Delete</button>
                            </td>
                        </tr>
                        <tr>
                            <td>001</td>
                            <td>Reagent Name</td>
                            <td>Client A</td>
                            <td>Oct 1, 2025</td>
                            <td>Dec 15, 2025</td>
                             <td><span class="status soon">Expired Soon</span></td>
                            <td>50 Units</td>  
                            <td class="actions">
                                <button class="btn edit">Edit</button>
                                <button class="btn delete">Delete</button>
                            </td>
                        </tr>
                        <tr>
                            <td>001</td>
                            <td>Reagent Name</td>
                            <td>Client A</td>
                            <td>Oct 1, 2025</td>
                            <td>Dec 15, 2025</td>
                             <td><span class="status expired">Expired</span></td>
                            <td>50 Units</td>  
                            <td class="actions">
                                <button class="btn edit">Edit</button>
                                <button class="btn delete">Delete</button>
                            </td>
                        </tr>
                        <tr>
                            <td>001</td>
                            <td>Reagent Name</td>
                            <td>Client A</td>
                            <td>Oct 1, 2025</td>
                            <td>Dec 15, 2025</td>
                             <td><span class="status ok">OK</span></td>
                            <td>50 Units</td>  
                            <td class="actions">
                                <button class="btn edit">Edit</button>
                                <button class="btn delete">Delete</button>
                            </td>
                        </tr>
                        <tr>
                            <td>001</td>
                            <td>Reagent Name</td>
                            <td>Client A</td>
                            <td>Oct 1, 2025</td>
                            <td>Dec 15, 2025</td>
                             <td><span class="status ok">OK</span></td>
                            <td>50 Units</td>  
                            <td class="actions">
                                <button class="btn edit">Edit</button>
                                <button class="btn delete">Delete</button>
                            </td>
                        </tr>
                        <tr>
                            <td>001</td>
                            <td>Reagent Name</td>
                            <td>Client A</td>
                            <td>Oct 1, 2025</td>
                            <td>Dec 15, 2025</td>
                             <td><span class="status ok">OK</span></td>
                            <td>50 Units</td>  
                            <td class="actions">
                                <button class="btn edit">Edit</button>
                                <button class="btn delete">Delete</button>
                            </td>
                        </tr>
                        <tr>
                            <td>001</td>
                            <td>Reagent Name</td>
                            <td>Client A</td>
                            <td>Oct 1, 2025</td>
                            <td>Dec 15, 2025</td>
                             <td><span class="status ok">OK</span></td>
                            <td>50 Units</td>  
                            <td class="actions">
                                <button class="btn edit">Edit</button>
                                <button class="btn delete">Delete</button>
                            </td>
                        </tr>
                        <tr>
                            <td>001</td>
                            <td>Reagent Name</td>
                            <td>Client A</td>
                            <td>Oct 1, 2025</td>
                            <td>Dec 15, 2025</td>
                             <td><span class="status ok">OK</span></td>
                            <td>50 Units</td>  
                            <td class="actions">
                                <button class="btn edit">Edit</button>
                                <button class="btn delete">Delete</button>
                            </td>
                        </tr>
                        <tr>
                            <td>001</td>
                            <td>Reagent Name</td>
                            <td>Client A</td>
                            <td>Oct 1, 2025</td>
                            <td>Dec 15, 2025</td>
                             <td><span class="status ok">OK</span></td>
                            <td>50 Units</td>  
                            <td class="actions">
                                <button class="btn edit">Edit</button>
                                <button class="btn delete">Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- <script src="./js/bootstrap.js"></script> -->
    <script>
        document.getElementById("reagents").addEventListener("click", function(e) {
            e.preventDefault();
            const submenu = this.nextElementSibling;
            submenu.classList.toggle("hide");
        });
    </script>
</body>

</html>