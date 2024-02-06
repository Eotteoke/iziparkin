<!DOCTYPE html>
<html>

<head>
    <title>My Tickets</title>
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

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #dddddd;
        }

        tr:hover {
            background-color: #f5f5f5;
        }
    </style>
</head>

<body>
    <?php
    // Database connection
    $con = mysqli_connect("localhost", "root", "", "iziparkin");

    // Retrieve user's tickets from the database
    session_start();
    $userId = $_SESSION['customer_id'];

    $query = "SELECT * FROM reservation_tb WHERE customer_id='$userId'";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) > 0) {
    ?>
        <h1>My Tickets</h1>
        <table>
            <tr>
                <th>Reservation ID</th>
                <th>Parking Lot ID</th>
                <th>In Time</th>
                <th>Out Time</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Reservation Status</th>
            </tr>
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td><a href='ticket_details.php?reservation_id=" . $row['reservation_id'] . "'>" . $row['reservation_id'] . "</a></td>";
                echo "<td>" . $row['parking_lot_id'] . "</td>";
                echo "<td>" . $row['in_time'] . "</td>";
                echo "<td>" . $row['out_time'] . "</td>";
                echo "<td>" . $row['start_time'] . "</td>";
                echo "<td>" . $row['end_time'] . "</td>";
                echo "<td>" . $row['reservation_status'] . "</td>";
                echo "</tr>";
            }
            ?>
        </table>
    <?php
    } else {
        echo "<h2>No tickets found.</h2>";
    }
    ?>

</body>

</html>