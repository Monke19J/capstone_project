<!-- This will be a repositor of deleted/ unused features -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Repository</title>
    <style>
        /* Set alert to different users */
        .set-alert-section {
            margin: 20px 0 25px 0;
        }

        .alert-checkbox-group {
            display: flex;
            gap: 15px;
            color: #003366;
            margin-top: 8px;
        }

        .alert-checkbox-group label {
            display: flex;
            align-items: center;
            gap: 5px;
            cursor: pointer;
            font-family: "Inter", sans-serif;
        }

        .manual-alert {
            border: 1px solid black;
            background-color: white;
            border-radius: 10px;
            padding: 0 15px;
        }

        .manual-alert-column {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 0;
        }

        .manual-alert-btn {
            background: none;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
        }

        .collapse-icon {
            height: 12px;
            width: 12px;
        }
    </style>
</head>

<body>
    <!-- Set alert to deffirent users -->
    <!-- Need a btn for it to activate -->
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

            <hr>

            <div class="set-alert-section">
                <p class="set-alert-text">This alert will be sent to:</p>
                <div class="alert-checkbox-group">
                    <label><input type="checkbox" name="alert_receiver[]" value="self"> Self</label>
                    <label><input type="checkbox" name="alert_receiver[]" value="all_sales"> All Sales</label>
                    <label><input type="checkbox" name="alert_receiver[]" value="all_engineering"> All Engineers</label>
                    <label><input type="checkbox" name="alert_receiver[]" value="everyone"> Everyone</label>
                </div>
            </div>

            <!-- Manual alert collapse with checkboxes -->
            <div class="manual-alert">
                <div class="manual-alert-column">
                    <p class="set-alert-text" style="color:black;">Select People Manually: </p>
                    <button type="button" id="toggleManualAlert" class="manual-alert-btn">
                        <img id="minusIcon" class="collapse-icon" src="./images/minus.png" style="display:inline;">
                        <img id="plusIcon" class="collapse-icon" src="./images/plus.png" style="display:none;">
                    </button>
                </div>
                <div id="manualAlertContent" class="manual-alert-content" style="display:none;">
                    <input type="text" id="userSearch" placeholder="Search user.">
                    <div class="user-list">
                        <?php
                        include "db.php";
                        $result = $conn->query("SELECT username FROM users");
                        while ($row = $result->fetch_assoc()) {
                            echo '<label class="user-item">';
                            echo '<input type="checkbox" name="alert_users[]" value="' . $row['username'] . '"> ' . $row['username'];
                            echo '</label>';
                        }
                        ?>
                    </div>
                </div>
            </div>

            <div class="submit-section">
                <button type="button" id="confirmSetAlert" class="add-btn" style="margin-top: 50px;">Confirm</button>
            </div>
        </div>
    </div>
</body>
<script>
     // Manual Alert Collapse Chatgpt
        const toggleManualAlert = document.getElementById("toggleManualAlert");
        const minusIcon = document.getElementById("minusIcon");
        const plusIcon = document.getElementById("plusIcon");
        const manualAlertContent = document.getElementById("manualAlertContent");

        toggleManualAlert.addEventListener("click", () => {
            if (manualAlertContent.style.display === "none") {
                manualAlertContent.style.display = "block";
                minusIcon.style.display = "inline";
                plusIcon.style.display = "none";
            } else {
                manualAlertContent.style.display = "none";
                minusIcon.style.display = "none";
                plusIcon.style.display = "inline";
            }
        });

        // Filter by type
        document.querySelectorAll(".user-type-btn").forEach(btn => {
            btn.addEventListener("click", () => {
                const type = btn.dataset.type;
                document.querySelectorAll(".user-item").forEach(item => {
                    item.style.display = (item.dataset.type === type) ? "flex" : "none";
                });
            });
        });

        // Search filter
        const userSearch = document.getElementById("userSearch");
        userSearch.addEventListener("input", () => {
            const term = userSearch.value.toLowerCase();
            document.querySelectorAll(".user-item").forEach(item => {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(term) ? "flex" : "none";
            });
        });

        const confirmBtn = document.getElementById("confirmSetAlert");

        confirmBtn.addEventListener("click", () => {
            // just close the modal — inputs remain filled
            setAlertModal.style.display = "none";
        });
</script>
</html>