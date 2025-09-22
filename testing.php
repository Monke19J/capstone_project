<?php
include "db.php";

$sql_expiration = "
    SELECT rs.stock_id,
           rs.lot_no,
           rs.expiry_date,
           rs.quantity,
           r.reagent_name,
           r.reagent_type
    FROM reagent_stock rs
    JOIN reagents r ON rs.reagent_id = r.reagent_id
    WHERE rs.stock_status = 'active'
      AND rs.expiry_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH)
    ORDER BY rs.expiry_date ASC
";
$result_expiration = $conn->query($sql_expiration);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        /* Section Header */
        .expiry-header {
            font-family: "Inter", sans-serif;
            font-size: 16px;
            font-weight: bold;
            margin: 12px 0;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Status circles */
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

        /* Table styling */
        .expiry-table {
            width: 100%;
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

        /* Expiry tags */
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

        /* Hidden state */
        .expiry-table.hidden {
            display: none;
        }
    </style>
</head>

<body>
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

<script>
function toggleTable(header) {
    const table = header.nextElementSibling;
    table.classList.toggle("hidden");
}
</script>


</body>

</html>