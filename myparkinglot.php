<?php
session_start();

$con = mysqli_connect("localhost", "root", "", "iziparkin");

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Get the parking lot owner's ID
$username = $_SESSION['username'];
$query = "SELECT parking_lot_owner_id FROM parking_lot_owner_tb WHERE username = '$username' LIMIT 1";
$result = mysqli_query($con, $query);
$parking_lot_owner = mysqli_fetch_assoc($result);
$parking_lot_owner_id = $parking_lot_owner['parking_lot_owner_id'];

// Retrieve parking lots owned by the parking lot owner
$query = "SELECT * FROM parking_lot_tb WHERE parking_lot_owner_id = '$parking_lot_owner_id'";
$result = mysqli_query($con, $query);

// Handle the form submission to add a new parking lot
if (isset($_POST['add_parking_lot'])) {
    $ev_cap = mysqli_real_escape_string($con, $_POST['ev_cap']);
    $norm_cap = mysqli_real_escape_string($con, $_POST['norm_cap']);
    $price_per_hour = mysqli_real_escape_string($con, $_POST['price_per_hour']);
    $price_per_day = mysqli_real_escape_string($con, $_POST['price_per_day']);
    $park_name = mysqli_real_escape_string($con, $_POST['park_name']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $photo = mysqli_real_escape_string($con, $_POST['photo']);
    $address = mysqli_real_escape_string($con, $_POST['address']);

    // Insert the new parking lot into the database
    $insert_query = "INSERT INTO parking_lot_tb (parking_lot_owner_id, ev_cap, norm_cap, price_per_hour, price_per_day, park_name, description, photo, address)
                     VALUES ('$parking_lot_owner_id', '$ev_cap', '$norm_cap', '$price_per_hour', '$price_per_day', '$park_name', '$description', '$photo', '$address')";
    mysqli_query($con, $insert_query);

    // Redirect to the updated "myparkinglot.php" page
    header("Location: myparkinglot.php");
    exit;
}

// Handle the form submission to edit parking lot details
if (isset($_POST['edit_parking_lot'])) {
    $parking_lot_id = mysqli_real_escape_string($con, $_POST['parking_lot_id']);
    $ev_cap = mysqli_real_escape_string($con, $_POST['ev_cap_edit']);
    $norm_cap = mysqli_real_escape_string($con, $_POST['norm_cap_edit']);
    $price_per_hour = mysqli_real_escape_string($con, $_POST['price_per_hour_edit']);
    $price_per_day = mysqli_real_escape_string($con, $_POST['price_per_day_edit']);

    // Update the parking lot details in the database
    $update_query = "UPDATE parking_lot_tb
                     SET ev_cap = '$ev_cap', norm_cap = '$norm_cap', price_per_hour = '$price_per_hour', price_per_day = '$price_per_day'
                     WHERE parking_lot_id = '$parking_lot_id'";
    mysqli_query($con, $update_query);

    // Redirect to the updated "myparkinglot.php" page
    header("Location: myparkinglot.php");
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>My Parking Lots</title>
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

        .parking-lot {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .parking-lot h2 {
            margin-bottom: 10px;
        }

        .parking-lot p {
            margin: 5px 0;
        }

        .edit-form {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .edit-form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .edit-form input[type="text"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 3px;
            margin-bottom: 10px;
        }

        .edit-form input[type="submit"] {
            background-color: #4D5DFA;
            color: #fff;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        .edit-form input[type="submit"]:hover {
            background-color: #3244A8;
        }

        .add-parking-lot-form {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .add-parking-lot-form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .add-parking-lot-form input[type="text"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 3px;
            margin-bottom: 10px;
        }

        .add-parking-lot-form input[type="submit"] {
            background-color: #4D5DFA;
            color: #fff;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        .add-parking-lot-form input[type="submit"]:hover {
            background-color: #3244A8;
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
        <h1>My Parking Lots</h1>
        <div class="sign-out">
            <button onclick="window.location.href='signoutOwner.php'">Sign Out</button>
        </div>

        <?php
        // Display the existing parking lots
        while ($row = mysqli_fetch_assoc($result)) {
            $parking_lot_id = $row['parking_lot_id'];
            $ev_cap = $row['ev_cap'];
            $norm_cap = $row['norm_cap'];
            $price_per_hour = $row['price_per_hour'];
            $price_per_day = $row['price_per_day'];
            $park_name = $row['park_name'];
            $description = $row['description'];
            $photo = $row['photo'];
            $address = $row['address'];
        ?>
            <div class="parking-lot">
                <h2><?php echo $park_name; ?></h2>
                <p>Address: <?php echo $address; ?></p>
                <p>EV Capacity: <?php echo $ev_cap; ?></p>
                <p>Normal Capacity: <?php echo $norm_cap; ?></p>
                <p>Price per Hour: <?php echo $price_per_hour; ?></p>
                <p>Price per Day: <?php echo $price_per_day; ?></p>
                <p>Description: <?php echo $description; ?></p>
                <p>Photo: <?php echo $photo; ?></p>

                <div class="edit-form">
                    <h3>Edit Parking Lot Details</h3>
                    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                        <input type="hidden" name="parking_lot_id" value="<?php echo $parking_lot_id; ?>">
                        <label for="ev_cap_edit">EV Capacity:</label>
                        <input type="text" id="ev_cap_edit" name="ev_cap_edit" value="<?php echo $ev_cap; ?>" required>
                        <label for="norm_cap_edit">Normal Capacity:</label>
                        <input type="text" id="norm_cap_edit" name="norm_cap_edit" value="<?php echo $norm_cap; ?>" required>
                        <label for="price_per_hour_edit">Price per Hour:</label>
                        <input type="text" id="price_per_hour_edit" name="price_per_hour_edit" value="<?php echo $price_per_hour; ?>" required>
                        <label for="price_per_day_edit">Price per Day:</label>
                        <input type="text" id="price_per_day_edit" name="price_per_day_edit" value="<?php echo $price_per_day; ?>" required>
                        <input type="submit" name="edit_parking_lot" value="Save Changes">
                    </form>
                </div>
            </div>
        <?php
        }
        ?>

        <div class="add-parking-lot-form">
            <h2>Add Parking Lot</h2>
            <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                <label for="ev_cap">EV Capacity:</label>
                <input type="text" id="ev_cap" name="ev_cap" required>
                <label for="norm_cap">Normal Capacity:</label>
                <input type="text" id="norm_cap" name="norm_cap" required>
                <label for="price_per_hour">Price per Hour:</label>
                <input type="text" id="price_per_hour" name="price_per_hour" required>
                <label for="price_per_day">Price per Day:</label>
                <input type="text" id="price_per_day" name="price_per_day" required>
                <label for="park_name">Parking Lot Name:</label>
                <input type="text" id="park_name" name="park_name" required>
                <label for="description">Description:</label>
                <input type="text" id="description" name="description" required>
                <label for="photo">Photo:</label>
                <input type="text" id="photo" name="photo" required>
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" required>
                <input type="submit" name="add_parking_lot" value="Add Parking Lot">
            </form>
        </div>
    </div>
</body>

</html>