<?php

include "db.php";
session_start();
if (!isset($_SESSION['user_id'])) {
    // user is not logged in → kick them back to login
    header("Location: ./login.html?status=error&message=" . urlencode("Please log in first"));
}

$sql_history = "
SELECT h.history_id,
       h.stock_id,
       h.reagent_id,
       h.action_type,
       h.quantity,
       h.client_id,
       h.date_action,
       r.reagent_name,
       r.reagent_type,
       r.reagent_img,
       r.category,
       r.test_kit,
       rs.lot_no,
       rs.expiry_date
FROM stock_history h
JOIN reagents r 
    ON h.reagent_id = r.reagent_id
JOIN reagent_stock rs
    ON h.stock_id = rs.stock_id
ORDER BY DATE(h.date_action) DESC, h.date_action DESC;

";

$result_history = $conn->query($sql_history);

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
        .search-filter-section {
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

        .inventory-filter-section {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            /* background-color: red; */
        }

        .filter-btn {
            width: auto;
            height: 30px;
            border-radius: 5px;
            background-color: #ffffffff;
            border: 1px solid black;
            padding: 0 10px;
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

        /* History Date Text */
        .history-date-text {
            margin-left: 1%;
            font-family: "Inter", sans-serif;
            font-size: 20px;
            font-weight: bold;
            color: #003366;
            /* Dark navy for contrast */
        }

        /* History Card */
        .history-items {
            height: 68vh;
            width: auto;
            margin: 2% 10px;
        }

        .item-card {
            width: 90%;
            height: auto;
            background: #fdfefe;
            border: 1px solid #cde4fa;
            border-radius: 20px;
            box-shadow: 0 6px 14px rgba(0, 120, 200, 0.15);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            display: grid;
            grid-template-rows: 42px 30px 30px 42px;
            grid-template-columns: 15% 1fr 1fr 1fr;
            gap: 15px 35px;
            padding: 0 15px;
            padding-bottom: 20px;
            margin: 15px 0;
        }

        .item-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 18px rgba(0, 120, 200, 0.25);
        }

        .card-category {
            grid-row: 1/2;
            grid-column: 1/2;
            width: 100%;
            height: 100%;
            color: white;
            border-bottom-right-radius: 20px;
            border-bottom-left-radius: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Chemistry = Blue */
        .card-category.chemistry {
            background: #0090FF;
        }

        /* Hematology = Red */
        .card-category.hematology {
            background: #e63946;
        }

        /* Immunology = Purple */
        .card-category.immunology {
            background: #6a4c93;
        }


        .card-text {
            font-size: 16px;
            font-weight: 600;
            font-family: "Inter", sans-serif;
            color: #002147;
        }

        .card-lot-num {
            grid-row: 1/2;
            grid-column: 2/3;
            display: flex;
            align-items: flex-end;
            font-weight: 500;
            color: #004080;
        }

        .card-image {
            grid-row: 2/4;
            grid-column: 1/2;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card-reagent-img {
            width: auto;
            height: 100%;
            border: 1px solid #cce0f5;
            border-radius: 8px;
            background: #f8fbff;
        }

        .card-name {
            grid-row: 2/3;
            grid-column: 2/3;
            display: flex;
            align-items: center;
            font-weight: bold;
            font-size: 18px;
            color: #002147;
        }

        .card-time {
            grid-row: 3/4;
            grid-column: 2/3;
            font-size: 14px;
            color: #5c6f87;
        }

        .card-action-type {
            grid-row: 1/2;
            grid-column: 4/5;
            display: flex;
            justify-content: flex-end;
        }

        .action-type {
            min-width: 90px;
            padding: 5px 10px;
            background: #e0f0ff;
            color: #004080;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 20px;
            margin-right: 25px;
        }

        .action-type.add {
            background: #d1f7d6;
            color: #1a7f37;
        }

        .action-type.remove {
            background: #ffe0e0;
            color: #b71c1c;
        }

        .action-type-text {
            font-size: 14px;
            font-weight: bold;
            font-family: "Inter", sans-serif;
        }

        .card-details {
            grid-column: 1 / -1;
            background: #eef7ff;
            border-radius: 15px;
            padding: 12px 15px;
            margin: 10px 0;
            font-size: 14px;
            color: #333;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
        }

        .details-table th,
        .details-table td {
            padding: 6px 8px;
            text-align: center;
            font-size: 13px;
        }

        .details-table th {
            font-weight: bold;
            color: #222;
        }


        .card-collapse {
            grid-row: -2/-1;
            grid-column: 1/-1;
        }

        .card-collapse-btn {
            width: 95%;
            height: 36px;
            background: #0090FF;
            color: white;
            font-size: 15px;
            font-weight: 600;
            border-radius: 100px;
            border-style: none;
            margin: 0 25px;
            transition: background 0.2s ease;
        }

        .card-collapse-btn:hover {
            background: #0074cc;
        }

        .card-stock {
            grid-row: 3/4;
            grid-column: -2/-1;
            padding-right: 25px;
            text-align: right;
            font-weight: bold;
            font-size: 16px;
            color: #002147;
        }

        .no-history {
            width: 100%;
            text-align: center;
            padding: 40px 20px;
            margin-top: 30px;
        }

        .no-history-img {
            width: 120px;
            opacity: 0.7;
            margin-bottom: 15px;
        }

        .no-history p {
            font-family: "Inter", sans-serif;
            font-size: 18px;
            font-weight: 500;
            color: #666;
            /* softer text */
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
                        <li class="nav-active" style="padding-right: 30px;">
                            <img src="./images/history.png" alt="Inventory Icon" class="list-icon">
                            <a href="#" class="nav-listname">Inventory</a>
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
            <h1 id="header-title">Inventory</h1>

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

            <div class="search-filter-section">
                <div class="search-container">
                    <img src="./images/search_icon.png" alt="Search_Icon" class="searchbar-icon">
                    <!-- Chemistry is a placeholder -->
                    <input type="text" class="searchbar-reagent" placeholder="Search History">
                </div>
                <div class="inventory-filter-section">
                    <button class="filter-btn">
                        <img src="./images/calendar_icon.png" alt="Calendar_icon" class="filter-btn-icon">
                        Date - Date
                    </button>

                    <button class="filter-btn">
                        <img src="./images/filter_icon.png" alt="Filter_icon" class="filter-btn-icon">
                        Filter
                    </button>
                </div>
            </div>

            <?php
            $currentDate = null;

            if ($result_history && $result_history->num_rows > 0):
                while ($history = $result_history->fetch_assoc()):
                    $entryDate = date("M d, Y", strtotime($history['date_action']));

                    // Date
                    if ($currentDate !== $entryDate) {
                        $currentDate = $entryDate;
                        echo "<p class='history-date-text'>{$entryDate}</p>";
                        echo "<div class='history-items'><div class='item-row'>";
                    }

                    $reagent_type = strtolower($history['reagent_type']);
                    $action_type = strtolower($history['action_type']);
                    $dateFormatted = date("M d, Y h:i A", strtotime($history['date_action']));
            ?>

                    <!-- Inventory Card -->
                    <div class="item-card">
                        <!-- Category -->
                        <div class="card-category <?php echo $reagent_type; ?>">
                            <span class="card-text"><?php echo htmlspecialchars($history['reagent_type']); ?></span>
                        </div>

                        <!-- Lot number -->
                        <div class="card-lot-num">
                            <span class="card-text">Lot: <?php echo htmlspecialchars($history['lot_no']); ?></span>
                        </div>

                        <!-- Image -->
                        <div class="card-image">
                            <img src="<?php echo $history['reagent_img'] ?? './images/no_available_img.jpeg'; ?>"
                                alt="Reagent image" class="card-reagent-img">
                        </div>

                        <!-- Reagent Name -->
                        <div class="card-name">
                            <span class="card-text"><?php echo htmlspecialchars($history['reagent_name']); ?></span>
                        </div>

                        <!-- Time -->
                        <div class="card-time">
                            <span class="card-text"><?php echo $dateFormatted; ?></span>
                        </div>

                        <!-- Action Type -->
                        <div class="card-action-type">
                            <div class="action-type <?php echo $action_type; ?>">
                                <span class="action-type-text">
                                    <?php echo ($action_type === 'add') ? 'Restocked' : 'Delivered'; ?>
                                </span>
                            </div>
                        </div>

                        <!-- Number of Stock -->
                        <div class="card-stock">
                            <span class="card-text"><?php echo htmlspecialchars($history['quantity']); ?> units</span>
                        </div>

                        <!-- Details -->
                        <div class="card-details" style="display:none;">
                            <table class="details-table">
                                <thead>
                                    <tr>
                                        <th>Lot No.</th>
                                        <th>Reagent Name</th>
                                        <th>Department</th>
                                        <th>Client</th>
                                        <th>Test/Kits</th>
                                        <th>Category</th>
                                        <th>Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?php echo htmlspecialchars($history['lot_no']); ?></td>
                                        <td><?php echo htmlspecialchars($history['reagent_name']); ?></td>
                                        <td><?php echo htmlspecialchars(ucfirst(strtolower($history['reagent_type'])) ?? 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars($history['client_id'] ?? 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars($history['test_kit'] ?? 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars(ucfirst(strtolower($history['category'])) ?? 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars($history['quantity']); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Collapse Btn -->
                        <div class="card-collapse">
                            <button class="card-collapse-btn">
                                <span class="action-type-text" style="color:white;">See Full Details</span>
                            </button>
                        </div>
                    </div>

                <?php endwhile;
                echo "</div></div>";
            else: ?>
                <div class="no-history">
                    <img src="./images/no_data.png" alt="No history" class="no-history-img">
                    <p>No history records found.</p>
                </div>

            <?php endif; ?>
        </main>
    </div>

    <!-- <script src="./js/bootstrap.js"></script> -->
    <script>
        document.getElementById("reagents").addEventListener("click", function(e) {
            e.preventDefault(); // prevent the link from reloading
            const submenu = this.nextElementSibling;
            submenu.classList.toggle("show");
        });

        document.querySelectorAll(".card-collapse-btn").forEach(btn => {
            btn.addEventListener("click", () => {
                const details = btn.closest(".item-card").querySelector(".card-details");
                if (details.style.display === "none" || details.style.display === "") {
                    details.style.display = "block";
                    btn.querySelector("span").textContent = "Hide Details";
                } else {
                    details.style.display = "none";
                    btn.querySelector("span").textContent = "See Full Details";
                }
            });
        });
    </script>
</body>

</html>