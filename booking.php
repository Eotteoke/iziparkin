<!DOCTYPE html>
<html>

<head>
    <title>Booking - Park Parking Web Application</title>
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
        <a href="profile.php">Profile</a>
        <a href="Mytickets.php">My Tickets</a>
        <a href="Review.php">Review</a>
        <!-- Add more links as needed -->
    </div>

    <?php
    // Database connection and other functions
    $con = mysqli_connect("localhost", "root", "", "iziparkin");

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

    // Check if a parking lot ID is provided in the query parameters
    if (isset($_GET['id'])) {
        $parkingLotId = $_GET['id'];
        $parkingLot = getParkingLotById($parkingLotId);

        if ($parkingLot) {
            echo '<h1 class="main-title">Booking - ' . $parkingLot['park_name'] . '</h1>';
            echo '<div class="parking-lot-details">';
            echo '<h2>' . $parkingLot['park_name'] . '</h2>';
            echo '<p>Address: ' . $parkingLot['address'] . '</p>';
            echo '<p>Price: $' . $parkingLot['price_per_hour'] . ' per hour</p>';
            // Add more parking lot details as needed

            // Add your booking form here
            echo '<form action="process_booking.php" method="POST" class="booking-form">';
            echo '<input type="hidden" name="parking_lot_id" value="' . $parkingLotId . '">';

            // Add a field for parking hour duration
            echo '<label>Parking Hour Duration:</label>';
            echo '<input type="number" name="parking_hours" min="1" required>';

            // Add a checkbox for EV charging option
            $capQuery = "SELECT ev_cap, norm_cap FROM parking_lot_tb WHERE parking_lot_id = '$parkingLotId'";
            $capResult = mysqli_query($con, $capQuery);
            if (mysqli_num_rows($capResult) > 0) {
                $row = mysqli_fetch_assoc($capResult);
                $evCap = $row['ev_cap'];
                if ($evCap > 0) {
                    echo '<label for="ev_charging">EV Charging:</label>';
                    echo '<input type="hidden" name="ev_charging" value="0">'; // Hidden input with default value
                    echo '<input type="checkbox" name="ev_charging" id="ev_charging" value="1">';
                }


                // Calculate and display the total price
                $pricePerHour = $parkingLot['price_per_hour'];
                echo '<p>Total Price: $<span id="total-price">0</span></p>';

                // Add JavaScript code to calculate the total price based on the selected parking hour duration and EV charging option
                echo '<script>';
                echo 'var pricePerHour = ' . $pricePerHour . ';';
                echo 'var parkingHoursInput = document.querySelector(\'input[name="parking_hours"]\');';
                echo 'var evChargingCheckbox = document.getElementById("ev_charging");';
                echo 'var totalPrice = document.getElementById("total-price");';
                echo 'parkingHoursInput.addEventListener("input", function() {';
                echo '  var parkingHours = parseInt(parkingHoursInput.value);';
                echo '  var total = parkingHours * pricePerHour;';
                echo '  totalPrice.textContent = total.toFixed(2);';
                echo '});';
                echo 'evChargingCheckbox.addEventListener("change", function() {';
                echo '  var parkingHours = parseInt(parkingHoursInput.value);';
                echo '  var total = parkingHours * pricePerHour;';
                echo '  totalPrice.textContent = total.toFixed(2);';
                echo '});';
                echo '</script>';

                $normCap = $row['norm_cap'];
                if ($evCap > 0 || $normCap > 0) {
                    echo '<button type="submit">Book Now</button>';
                } else {
                    echo '<p>Sorry, this parking lot is full.</p>';
                }
                echo '</form>';
            }
            echo '</div>';
        } else {
            echo '<p>Parking lot not found.</p>';
        }
    } else {
        echo '<p>No parking lot ID provided.</p>';
    }
    ?>

</body>

</html>