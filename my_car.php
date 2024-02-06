<!DOCTYPE html>
<html>

<head>
    <title>My Car Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ffffff;
            margin: 0;
            padding: 20px;
        }

        h1 {
            color: #4D5DFA;
            font-size: 24px;
            margin-bottom: 20px;
        }

        form {
            max-width: 400px;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            color: #4D5DFA;
            font-weight: bold;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #4D5DFA;
            border-radius: 4px;
        }

        button[type="submit"] {
            background-color: #4D5DFA;
            color: #ffffff;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #3346AF;
        }
    </style>
</head>

<body>
    <?php
    // Database connection
    $con = mysqli_connect("localhost", "root", "", "iziparkin");

    // Function to add a new car
    function addCar($brand, $licensePlate, $plateRegion, $customerId)
    {
        global $con;

        $brand = mysqli_real_escape_string($con, $brand);
        $licensePlate = mysqli_real_escape_string($con, $licensePlate);
        $plateRegion = mysqli_real_escape_string($con, $plateRegion);
        $customerId = mysqli_real_escape_string($con, $customerId);

        $query = "INSERT INTO car (car_brand) VALUES ('$brand')";
        mysqli_query($con, $query);

        // Get the ID of the last inserted car
        $carId = mysqli_insert_id($con);

        // Insert car license details
        $query = "INSERT INTO car_license (car_id, license_plate, plate_region) VALUES ('$carId', '$licensePlate', '$plateRegion')";
        mysqli_query($con, $query);

        // Update customer table with car_id
        $query = "UPDATE customer_tb SET car_id='$carId' WHERE customer_id='$customerId'";
        mysqli_query($con, $query);

        return $carId;
    }

    // Function to update an existing car
    function updateCar($carId, $brand, $licensePlate, $plateRegion)
    {
        global $con;

        $carId = mysqli_real_escape_string($con, $carId);
        $brand = mysqli_real_escape_string($con, $brand);
        $licensePlate = mysqli_real_escape_string($con, $licensePlate);
        $plateRegion = mysqli_real_escape_string($con, $plateRegion);

        $query = "UPDATE car SET car_brand='$brand' WHERE car_id='$carId'";
        mysqli_query($con, $query);

        // Update car license details
        $query = "UPDATE car_license SET license_plate='$licensePlate', plate_region='$plateRegion' WHERE car_id='$carId'";
        mysqli_query($con, $query);
    }

    // Function to retrieve a car by customer ID
    function getCarByCustomerId($customerId)
    {
        global $con;

        $customerId = mysqli_real_escape_string($con, $customerId);

        $query = "SELECT * FROM car 
                  LEFT JOIN car_license ON car.car_id = car_license.car_id 
                  LEFT JOIN customer_tb ON car_license.car_id = customer_tb.car_id 
                  WHERE customer_tb.customer_id='$customerId'";
        $result = mysqli_query($con, $query);
        $car = mysqli_fetch_assoc($result);

        return $car;
    }

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $customerId = $_POST['customer_id'];
        $carId = $_POST['car_id'];
        $brand = $_POST['car_brand'];
        $licensePlate = $_POST['license_plate'];
        $plateRegion = $_POST['plate_region'];

        if ($carId) {
            // Update existing car
            updateCar($carId, $brand, $licensePlate, $plateRegion);
            echo "<h2>Car updated successfully!</h2>";
        } else {
            // Add new car
            $carId = addCar($brand, $licensePlate, $plateRegion, $customerId);
            echo "<h2>Car added successfully with ID: $carId</h2>";
        }
    }

    // Display car form
    function displayCarForm($carId = null, $brand = '', $licensePlate = '', $plateRegion = '')
    {
    ?>
        <h1><?php echo $carId ? "Edit Car" : "Add New Car"; ?></h1>
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="hidden" name="customer_id" value="<?php echo $_SESSION['customer_id']; ?>">
            <input type="hidden" name="car_id" value="<?php echo $carId; ?>">
            <label for="car_brand">Car Brand:</label>
            <input type="text" name="car_brand" value="<?php echo $brand; ?>"><br><br>
            <label for="license_plate">License Plate:</label>
            <input type="text" name="license_plate" value="<?php echo $licensePlate; ?>"><br><br>
            <label for="plate_region">Plate Region:</label>
            <input type="text" name="plate_region" value="<?php echo $plateRegion; ?>"><br><br>
            <button type="submit">Save</button>
        </form>
    <?php
    }

    // Check if the user is logged in
    session_start();
    if (!isset($_SESSION['customer_id'])) {
        echo "<h2>Please log in to access this page.</h2>";
        exit;
    }

    // Get the customer ID from the session
    $customerId = $_SESSION['customer_id'];

    // Get the existing car of the user
    $car = getCarByCustomerId($customerId);

    if ($car) {
        displayCarForm($car['car_id'], $car['car_brand'], $car['license_plate'], $car['plate_region']);
    } else {
        displayCarForm();
    }

    mysqli_close($con);
    ?>
</body>

</html>