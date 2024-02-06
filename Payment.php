<head>
    <title>Process Booking - Park Parking Web Application</title>
    <style>
        /* CSS styles for the UI design */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
        }

        .navbar {
            background-color: #333;
            overflow: hidden;
        }

        .navbar a {
            float: left;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }

        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }

        .main-title {
            margin: 20px;
            font-size: 24px;
        }

        .parking-lot-details {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 20px;
            margin: 20px;
        }

        .parking-lot-details h2 {
            font-size: 20px;
            margin-bottom: 10px;
        }

        .parking-lot-details p {
            font-size: 16px;
            margin-bottom: 5px;
        }

        .booking-form {
            margin-top: 20px;
        }

        .booking-form label {
            font-size: 16px;
            margin-bottom: 5px;
            display: block;
        }

        .booking-form input[type="text"],
        .booking-form input[type="number"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            margin-bottom: 10px;
        }

        .booking-form button[type="submit"] {
            background-color: #4D5DFA;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .booking-form button[type="submit"]:hover {
            background-color: #2d3dc6;
        }

        /* Add more custom styles as needed */
    </style>
</head>

<body>
    <!-- Navigation bar -->
    <div class="navbar">
        <a href="home.php">Home</a>
        <a href="settings.php">Settings</a>
        <a href="Mytickets.php">My Tickets</a>
        <a href="Review.php">Review</a>
        <!-- Add more links as needed -->
    </div>

    <?php
    session_start();

    $parkingLotId = isset($_SESSION['parking_lot_id']);
    $parkingHours = isset($_SESSION['parking_hours']);
    $evCharging = isset($_SESSION['ev_charging']) ? 1 : 0;
    $customerId = isset($_SESSION['customer_id']);

    function createReservation($customerId, $paymentTypeId, $parkingLotId, $parkingHours, $evCharging)
    {
        global $con;

        $inTime = date("Y-m-d H:i:s");
        $outTime = date("Y-m-d H:i:s");
        $startTime = date("Y-m-d H:i:s");
        $endTime = date("Y-m-d H:i:s");
        $reservationStatus = "Reserved";

        $sql = "INSERT INTO reservation_tb (customer_id, parking_lot_id, in_time, out_time, start_time, end_time, reservation_status) VALUES ('$customerId', '$parkingLotId', '$inTime', '$outTime', '$startTime', '$endTime', '$reservationStatus')";

        mysqli_query($con, $sql);

        $reservationId = mysqli_insert_id($con);

        $paymentAmount = calculateTotalPrice($parkingHours, $evCharging);
        $paymentTime = date("Y-m-d H:i:s");

        $sql = "INSERT INTO payment_method_tb (customer_id,payment_type_id,card_number,expiration_date,security_code,billing_address ) VALUES ('$customerId','$paymentTypeId', null, null, null, null)";
        mysqli_query($con, $sql);

        $sql = "INSERT INTO payment_tb (payment_type_id,reservation_id,payment_time) VALUES ('$paymentTypeId', '$reservationId', '$paymentTime')";
        mysqli_query($con, $sql);

        $reservationStatus = "Paid";
        $sql = "UPDATE reservation_tb SET reservation_status = '$reservationStatus' WHERE reservation_id = '$reservationId'";
        mysqli_query($con, $sql);

        return $reservationId;
    }

    function calculateTotalPrice($parkingHours, $evCharging)
    {
        $pricePerHour = 10;
        $price = $parkingHours * $pricePerHour;

        if ($evCharging) {
            $evChargingFee = 5;
            $price += $evChargingFee;
        }

        return $price;
    }

    function getParkingLotById($parkingLotId)
    {
        global $con;

        // Your implementation to fetch parking lot details by ID
        // Replace this with your actual SQL query and database connection code
        $sql = "SELECT * FROM parking_lot_tb WHERE parking_lot_id = '$parkingLotId'";

        $result = mysqli_query($con, $sql);
        $parkingLot = mysqli_fetch_assoc($result);

        return $parkingLot;
    }

    $con = mysqli_connect("localhost", "root", "", "iziparkin");

    $parkingLot = getParkingLotById($parkingLotId);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $paymentTypeId = $_POST['payment_method'];

        if ($paymentTypeId == '1' || $paymentTypeId == '2') {
            $cardNumber = $_POST['card_number'];
            $expirationDate = $_POST['expiration_date'];
            $securityCode = $_POST['security_code'];
            $billingAddress = $_POST['billing_address'];

            $sql = "INSERT INTO payment_method_tb (customer_id, payment_type_id, card_number, expiration_date, security_code, billing_address) VALUES ('$customerId', '$paymentTypeId', '$cardNumber', '$expirationDate', '$securityCode', '$billingAddress')";
            mysqli_query($con, $sql);
        }

        $reservationId = createReservation($customerId, $paymentTypeId, $parkingLotId, $parkingHours, $evCharging);
        echo '<h1>Reservation Successful</h1>';
        echo '<p>Your reservation ID is: ' . $reservationId . '</p>';
    }
    ?>

    <div class="parking-lot-details">
        <h2>Parking Lot Details</h2>
        <p>Parking Lot ID: <?php echo $parkingLotId; ?></p>
        <!-- Display other parking lot details as needed -->
    </div>

    <div class="booking-form">
        <form method="POST" action="payment.php">
            <label for="payment-method">Payment Method:</label>
            <select id="payment-method" name="payment_method">
                <option value="1">Credit Card</option>
                <option value="2">Debit Card</option>
                <option value="3">Cash</option>
            </select>

            <div id="card-details">
                <label for="card-number">Card Number:</label>
                <input type="text" id="card-number" name="card_number">

                <label for="expiration-date">Expiration Date:</label>
                <input type="text" id="expiration-date" name="expiration_date">

                <label for="security-code">Security Code:</label>
                <input type="text" id="security-code" name="security_code">

                <label for="billing-address">Billing Address:</label>
                <input type="text" id="billing-address" name="billing_address">
            </div>

            <button type="submit">Pay and Reserve</button>
        </form>
    </div>
</body>
<?php
$con = mysqli_connect("localhost", "root", "", "iziparkin");
