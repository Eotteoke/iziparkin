<!DOCTYPE html>
<html>

<head>
    <title>Ticket Details</title>
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

        h2 {
            color: #4D5DFA;
            font-size: 18px;
            margin-bottom: 10px;
        }

        p {
            margin-bottom: 5px;
        }
    </style>
</head>

<body>
    <?php
    // Database connection
    $con = mysqli_connect("localhost", "root", "", "iziparkin");

    // Retrieve ticket details from the database
    $reservationId = $_GET['reservation_id'];

    $query = "SELECT * FROM reservation_tb WHERE reservation_id='$reservationId'";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) > 0) {
        $ticket = mysqli_fetch_assoc($result);
        $parkingLotId = $ticket['parking_lot_id'];

        $query = "SELECT * FROM parking_lot_tb WHERE parking_lot_id='$parkingLotId'";
        $result = mysqli_query($con, $query);

        if (mysqli_num_rows($result) > 0) {
            $parkingLot = mysqli_fetch_assoc($result);
    ?>
            <h1>Ticket Details</h1>
            <h2>Reservation ID: <?php echo $ticket['reservation_id']; ?></h2>
            <p>Parking Lot ID: <?php echo $ticket['parking_lot_id']; ?></p>
            <p>In Time: <?php echo $ticket['in_time']; ?></p>
            <p>Out Time: <?php echo $ticket['out_time']; ?></p>
            <p>Start Time: <?php echo $ticket['start_time']; ?></p>
            <p>End Time: <?php echo $ticket['end_time']; ?></p>
            <p>Reservation Status: <?php echo $ticket['reservation_status']; ?></p>

            <h2>Parking Lot Information</h2>
            <p>Parking Lot Name: <?php echo $parkingLot['park_name']; ?></p>
            <p>Description: <?php echo $parkingLot['description']; ?></p>
            <p>Address: <?php echo $parkingLot['address']; ?></p>
            <p>Price per Hour: <?php echo $parkingLot['price_per_hour']; ?></p>
            <p>Price per Day: <?php echo $parkingLot['price_per_day']; ?></p>
    <?php
        } else {
            echo "<h2>Parking lot not found.</h2>";
        }
    } else {
        echo "<h2>Ticket not found.</h2>";
    }
    ?>

</body>

</html>