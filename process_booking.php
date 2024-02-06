<?php
// Database connection and other functions
$con = mysqli_connect("localhost", "root", "", "iziparkin");
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the form data
    $parkingLotId = $_POST['parking_lot_id'];
    $parkingHours = $_POST['parking_hours'];
    $evCharging = $_POST['ev_charging'];
    $paymentOption = $_POST['payment_option'];
    $customerId = $_SESSION['customer_id'];

    // Validate and sanitize the form data as needed

    // Process the payment information based on the selected payment option
    if ($paymentOption == 1 || $paymentOption == 2) {
        // Credit or Debit card payment
        $cardNumber = $_POST['card_number'];
        $expirationDate = $_POST['expiration_date'];
        $securityCode = $_POST['security_code'];
        $billingAddress = $_POST['billing_address'];

        // Validate and sanitize the payment information as needed

        // Save the payment information to the database
        $sql = "INSERT INTO payment_method_tb (customer_id, payment_type_id, card_number, expiration_date, security_code, billing_address)
                VALUES ('$customerId', '$paymentOption', '$cardNumber', '$expirationDate', '$securityCode', '$billingAddress')";

        // Execute the SQL query and check if the payment information is saved successfully
        if (mysqli_query($con, $sql)) {
            // Payment information saved successfully

            // Create a reservation
            $sql = "INSERT INTO reservation_tb (customer_id, parking_lot_id, in_time, out_time, start_time, end_time, reservation_status)
                    VALUES ('$customerId', '$parkingLotId', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'Booked')";

            // Execute the SQL query and check if the reservation is created successfully
            if (mysqli_query($con, $sql)) {
                // Reservation created successfully

                // Decrease ev_cap or norm_cap based on the selected option
                if ($evCharging == 1) {
                    // Update ev_cap
                    $sql = "UPDATE parking_lot_tb SET ev_cap = ev_cap - 1 WHERE parking_lot_id = '$parkingLotId' AND ev_cap > 0";
                } else {
                    // Update norm_cap
                    $sql = "UPDATE parking_lot_tb SET norm_cap = norm_cap - 1 WHERE parking_lot_id = '$parkingLotId' AND norm_cap > 0";
                }

                // Execute the SQL query and check if the capacity update is successful
                if (mysqli_query($con, $sql)) {
                    // Capacity updated successfully

                    // Redirect the user to a confirmation page or perform any other desired actions
                    header("Location: booking_success.php");
                    exit();
                } else {
                    // Error updating the capacity
                    echo "Error: " . mysqli_error($con);
                }
            } else {
                // Error creating the reservation
                echo "Error: " . mysqli_error($con);
            }
        } else {
            // Error saving the payment information
            echo "Error: " . mysqli_error($con);
        }
    } elseif ($paymentOption == 3) {
        // Cash payment

        // Create a reservation without saving payment information
        $sql = "INSERT INTO reservation_tb (customer_id, parking_lot_id, in_time, out_time, start_time, end_time, reservation_status)
                VALUES ('$customerId', '$parkingLotId', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'Booked')";

        // Execute the SQL query and check if the reservation is created successfully
        if (mysqli_query($con, $sql)) {
            // Reservation created successfully

            // Decrease ev_cap or norm_cap based on the selected option
            if ($evCharging == 1) {
                // Update ev_cap
                $sql = "UPDATE parking_lot_tb SET ev_cap = ev_cap - 1 WHERE parking_lot_id = '$parkingLotId' AND ev_cap > 0";
            } else {
                // Update norm_cap
                $sql = "UPDATE parking_lot_tb SET norm_cap = norm_cap - 1 WHERE parking_lot_id = '$parkingLotId' AND norm_cap > 0";
            }

            // Execute the SQL query and check if the capacity update is successful
            if (mysqli_query($con, $sql)) {
                // Capacity updated successfully

                // Redirect the user to a confirmation page or perform any other desired actions
                header("Location: booking_success.php");
                exit();
            } else {
                // Error updating the capacity
                echo "Error: " . mysqli_error($con);
            }
        } else {
            // Error creating the reservation
            echo "Error: " . mysqli_error($con);
        }
    } else {
        // Invalid payment option selected
        echo "Invalid payment option selected.";
    }
} else {
    // Invalid request
    echo "Invalid request.";
}
?>
?>

<!DOCTYPE html>
<html>

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

        .main-title {
            margin: 20px;
            font-size: 24px;
        }

        .booking-form {
            margin-top: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 20px;
            margin: 20px;
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
    <h1 class="main-title">Process Booking</h1>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="booking-form">
        <input type="hidden" name="parking_lot_id" value="<?php echo $parkingLotId; ?>">
        <input type="hidden" name="parking_hours" value="<?php echo $parkingHours; ?>">
        <input type="hidden" name="ev_charging" value="<?php echo $evCharging; ?>">

        <label>Payment Option:</label>
        <select name="payment_option" required>
            <option value="">Select Payment Option</option>
            <option value="1">Credit Card</option>
            <option value="2">Debit Card</option>
            <option value="3">Cash</option>
        </select>

        <div id="card-details">
            <label>Card Number:</label>
            <input type="text" name="card_number">

            <label>Expiration Date:</label>
            <input type="text" name="expiration_date">

            <label>Security Code:</label>
            <input type="text" name="security_code">

            <label>Billing Address:</label>
            <input type="text" name="billing_address">
        </div>

        <button type="submit">Book Now</button>
    </form>

    <script>
        // Show/hide card details based on the selected payment option
        var paymentOptionSelect = document.querySelector('select[name="payment_option"]');
        var cardDetailsDiv = document.getElementById('card-details');

        paymentOptionSelect.addEventListener('change', function() {
            if (paymentOptionSelect.value == 1 || paymentOptionSelect.value == 2) {
                cardDetailsDiv.style.display = 'block';
            } else {
                cardDetailsDiv.style.display = 'none';
            }
        });
    </script>
</body>

</html>