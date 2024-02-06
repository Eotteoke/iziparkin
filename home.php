<!DOCTYPE html>
<html>

<head>
    <title>Park Parking Web Application</title>
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

        .search-form {
            margin: 20px;
            text-align: center;
        }

        .search-form .search-input {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 300px;
        }

        .search-form .search-button {
            background-color: #4D5DFA;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-form .search-button:hover {
            background-color: #2d3dc6;
        }

        .parking-lot {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 20px;
            margin: 20px;
        }

        .parking-lot h3 {
            font-size: 20px;
            margin-bottom: 10px;
        }

        .parking-lot p {
            font-size: 16px;
            margin-bottom: 5px;
        }

        /* Add more custom styles as needed */
    </style>
</head>

<body>
    <!-- Navigation bar -->
    <div class="navbar">
        <a href="home.php" class="active">Home</a>
        <a href="Profile.php">Profile</a>
        <a href="Mytickets.php">My Tickets</a>
        <a href="Review.php">Review</a>
        <a href="signout.php" style="float:right">Logout</a>
    </div>

    <?php
    // Database connection and other functions
    $con = mysqli_connect("localhost", "root", "", "iziparkin");

    function getRecommendedParkingLots()
    {
        global $con;

        // Your implementation to fetch recommended parking lots
        // Replace this with your actual SQL query and database connection code
        $sql = "SELECT * FROM parking_lot_tb
                JOIN rating_tb ON parking_lot_tb.parking_lot_id = rating_tb.parking_lot_id
                ORDER BY rating_tb.overall_rating DESC
                LIMIT 5";

        $result = mysqli_query($con, $sql);
        $recommendedParkingLots = mysqli_fetch_all($result, MYSQLI_ASSOC);

        return $recommendedParkingLots;
    }

    function getNearbyParkingLots()
    {
        global $con;

        // Your implementation to fetch nearby parking lots
        // Replace this with your actual SQL query and database connection code
        $sql = "SELECT * FROM parking_lot_tb
                ORDER BY RAND()
                LIMIT 5";

        $result = mysqli_query($con, $sql);
        $nearbyParkingLots = mysqli_fetch_all($result, MYSQLI_ASSOC);

        return $nearbyParkingLots;
    }

    function searchParkingLotsByAddress($address)
    {
        global $con;

        // Your implementation to search parking lots by address
        // Replace this with your actual SQL query and database connection code
        $sql = "SELECT * FROM parking_lot_tb
                WHERE address LIKE '%$address%'";

        $result = mysqli_query($con, $sql);
        $parkingLots = mysqli_fetch_all($result, MYSQLI_ASSOC);

        return $parkingLots;
    }

    function getLoggedInUserName()
    {
        session_start();
        // Check if the user is logged in
        if (!isset($_SESSION['username'])) {
            header("Location: login.php");
            exit;
        }
        // Assuming the user name is stored in a session variable named 'username'
        if (isset($_SESSION['username'])) {
            return $_SESSION['username'];
        } else {
            return null;
        }
    }

    // Retrieve the logged-in user's name and display a message
    $loggedInUser = getLoggedInUserName();
    if ($loggedInUser) {
        echo '<h1 class="main-title">Hey, ' . $loggedInUser . '. Where are we parking today?</h1>';
    } else {
        echo '<h1 class="main-title">Welcome! Where are we parking today?</h1>';
    }
    ?>

    <!-- Add the search form -->
    <div class="search-form">
        <form action="" method="GET">
            <input type="text" name="address" class="search-input" placeholder="Enter address">
            <button type="submit" class="search-button">Search</button>
        </form>
    </div>

    <?php
    // Handle search form submission
    if (isset($_GET['address'])) {
        $address = $_GET['address'];
        $parkingLots = searchParkingLotsByAddress($address);

        if (!empty($parkingLots)) {
            echo '<h2>Search Results</h2>';
            foreach ($parkingLots as $parkingLot) {
                echo '<div class="parking-lot">';
                echo '<h3><a href="booking.php?id=' . $parkingLot['parking_lot_id'] . '">' . $parkingLot['park_name'] . '</a></h3>';
                echo '<p>' . $parkingLot['address'] . ' (Price: $' . $parkingLot['price_per_hour'] . ' per hour)</p>';
                echo '</div>';
            }
        } else {
            echo '<p>No parking lots found for the given address.</p>';
        }
    }

    // Fetch and display recommended parking lots
    $recommendedParkingLots = getRecommendedParkingLots();
    if (!empty($recommendedParkingLots)) {
        echo '<h2>Recommended Parking Lots</h2>';
        foreach ($recommendedParkingLots as $parkingLot) {
            echo '<div class="parking-lot">';
            echo '<h3><a href="booking.php?id=' . $parkingLot['parking_lot_id'] . '">' . $parkingLot['park_name'] . '</a></h3>';
            echo '<p>' . $parkingLot['address'] . ' (Rating: ' . $parkingLot['overall_rating'] . ', Price: $' . $parkingLot['price_per_hour'] . ' per hour)</p>';
            echo '</div>';
        }
    }

    // Fetch and display nearby parking lots
    $nearbyParkingLots = getNearbyParkingLots();
    if (!empty($nearbyParkingLots)) {
        echo '<h2>Nearby Parking Lots</h2>';
        foreach ($nearbyParkingLots as $parkingLot) {
            echo '<div class="parking-lot">';
            echo '<h3><a href="booking.php?id=' . $parkingLot['parking_lot_id'] . '">' . $parkingLot['park_name'] . '</a></h3>';
            echo '<p>' . $parkingLot['address'] . ' (Price: $' . $parkingLot['price_per_hour'] . ' per hour)</p>';
            echo '</div>';
        }
    }
    ?>

</body>

</html>