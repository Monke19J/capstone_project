<?php
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

        html, body {
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
        .main-content {
            position: relative;
            padding: 0px 20px;
            height: auto;
            /* background-color: white; */
            grid-template-rows: auto auto;
        }

        .report {
            grid-row: 1;
            padding-top: 8px;
            align-self: start;
            display: flex;
            justify-content: flex-end;
        }

        .report-btn {
            margin-right: 25px;
            width: 146px;
            height: 48px;
            border-radius: 20px;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: linear-gradient(to right, #2bb4f4 0%, #ff5cf7 100%);
        }

        .report-icon {
            width: 27px;
            height: 27px;
        }

        .report-name {
            font-size: 20px;
            font-weight: bold;
            color: black;
        }

        .monthly-summary {
            grid-row: 2;
            margin-top: 8px;
            margin-left: 25px;
            width: 495px;
            height: 295px;
            /* background-color: green; */

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
                        <li class="nav-active" id="dashboard">
                            <img src="./images/dashboard.png" alt="Dashboard Icon" class="list-icon">
                            <a href="#" class="nav-listname">Dashboard</a>
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
                        <li class="nav-inactive" style="padding-right: 30px;">
                            <img src="./images/client.png" alt="Client Icon" class="list-icon">
                            <a href="./calendar.php" class="nav-listname">Calendar</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- End of Sidebar -->

    <!-- Start of Header -->
    <div class="header">
        <h1 id="header-title">Dashboard</h1>

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
        <div class="report">
            <button class="report-btn">
                <img class="report-icon" src="./images/report.png" alt="Profile">
                <p class="report-name">Report</p>
            </button>
        </div>
        <div class="monthly-summary">

        </div>
    </main>

    </div>

    <!-- <script src="./js/bootstrap.js"></script> -->
    <script>
        document.getElementById("reagents").addEventListener("click", function(e) {
            e.preventDefault(); // prevent the link from reloading
            const submenu = this.nextElementSibling;
            submenu.classList.toggle("show");
        });
    </script>
</body>

</html>