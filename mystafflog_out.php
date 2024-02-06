<?php
session_start();

$con = mysqli_connect("localhost", "root", "", "iziparkin");

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

// Check if the user is logged in
if (!isset($_SESSION['staff_email'])) {
    header("Location: Stafflogin.php");
    exit;
}

// Initialize variables for displaying success or error message
$successMessage = "";
$errorMessage = "";

// Handle the form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $reservationId = $_POST['reservation_id'];
    $parkingLotId = $_POST['parking_lot_id'];
    $evCheckbox = isset($_POST['ev_checkbox']);

    // Perform input validation
    if (empty($reservationId) || empty($parkingLotId)) {
        $errorMessage = "Invalid input";
    } else {
        // Check the connection
        if ($con->connect_error) {
            die("Connection failed: " . $con->connect_error);
        }

        // Query to validate the reservation ID
        $validateSql = "SELECT * FROM reservation_tb WHERE reservation_id = '$reservationId' AND parking_lot_id = '$parkingLotId'";

        // Execute the query to validate the reservation ID
        $validateResult = $con->query($validateSql);

        if ($validateResult->num_rows > 0) {
            // Reservation ID is valid, check the reservation status
            $row = $validateResult->fetch_assoc();
            $reservationStatus = $row['reservation_status'];

            if ($reservationStatus === 'Active') {
                // Reservation status is 'Active', proceed with updating the reservation status to 'Complete'
                // Query to update the reservation status to 'Complete'
                $updateSql = "UPDATE reservation_tb SET reservation_status = 'Complete' WHERE reservation_id = '$reservationId' AND parking_lot_id = '$parkingLotId'";

                // Execute the query to update the reservation status
                if ($con->query($updateSql) === true) {
                    $successMessage = "Reservation confirmed successfully";
                } else {
                    $errorMessage = "Error confirming reservation: " . $con->error;
                }
                if ($evCheckbox) {
                    $updateSql = "UPDATE parking_lot_tb SET ev_cap = ev_cap + 1 WHERE parking_lot_id = '$parkingLotId'";
                    $con->query($updateSql);
                } 
                else {
                    $updateSql = "UPDATE parking_lot_tb SET norm_cap = norm_cap  +1 WHERE parking_lot_id = '$parkingLotId'";
                    $con->query($updateSql);
                }
            } else {
                $errorMessage = "Reservation is not in 'Active' status";
            }
        } else {
            $errorMessage = "Invalid reservation ID";
        }

        // Close the database connection
        $con->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Staff Log</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 100px auto;
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .container h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #4D5DFA;
        }

        .selector-form {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .selector-form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .selector-form input[type="text"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 3px;
            margin-bottom: 10px;
        }

        .confirm-button {
            display: none;
        }

        .error-message {
            color: red;
            margin-top: 10px;
        }

        .success-message {
            color: green;
            margin-top: 10px;
        }
        .sign-out {
            text-align: right;
        }

        .sign-out button {
            background-color: #4D5DFA;
            color: #fff;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        .sign-out button:hover {
            background-color: #3244A8;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="sign-out">
            <button onclick="window.location.href='signoutStaff.php'">Sign Out</button>
        </div>
        <h1>Staff Log</h1>
        <div class="selector-form">
            <h2>Customer out confirm</h2>
            <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                <label for="reservation_id">Reservation ID:</label>
                <input type="text" id="reservation_id" name="reservation_id" required>
                <label for="parking_lot_id">Parking Lot ID:</label>
                <input type="text" id="parking_lot_id" name="parking_lot_id" required>
                <label for="ev_checkbox">EV:
                <input type="checkbox" id="ev_checkbox" name="ev_checkbox"></label>
                <button type="submit">Check Reservation</button>
            </form>
            <p class="error-message"><?php echo $errorMessage; ?></p>
            <p class="success-message"><?php echo $successMessage; ?></p>
            <button class="confirm-button" onclick="confirmReservation()">Confirm</button>
        </div>
    </div>
    <script>
        function confirmReservation() {
            // Hide any previous error or success messages
            document.querySelector('.error-message').textContent = '';
            document.querySelector('.success-message').textContent = '';

            var reservationId = document.getElementById('reservation_id').value;
            var parkingLotId = document.getElementById('parking_lot_id').value;

            // Perform client-side input validation
            if (reservationId.trim() === '' || parkingLotId.trim() === '') {
                alert('Invalid input');
                return;
            }

            // Send AJAX request to update reservation status
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'confirm_reservation.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            document.querySelector('.success-message').textContent = response.message;
                        } else {
                            document.querySelector('.error-message').textContent = response.message;
                        }
                    } else {
                        document.querySelector('.error-message').textContent = 'Error confirming reservation';
                    }
                }
            };
            xhr.send('reservation_id=' + encodeURIComponent(reservationId) + '&parking_lot_id=' + encodeURIComponent(parkingLotId));
        }
    </script>
</body>
</html>
