<!DOCTYPE html>
<html>

<head>
    <title>Profile Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fff;
            margin: 0;
            padding: 0;
        }

        h1,
        h2 {
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border-radius: 10px;
            background-color: #fff;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            margin-bottom: 10px;
        }

        a {
            display: block;
            padding: 10px;
            border-radius: 5px;
            background-color: #333;
            color: #fff;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        a:hover {
            background-color: #333;
        }

        /* Added styles for the navigation bar */
        .navbar {
            background-color: #333;
            overflow: hidden;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .navbar a {
            float: left;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }

        .navbar a:hover {
            background-color: #2d3dc6;
        }
    </style>
</head>

<body>
    <?php
    // Include the file containing the getLoggedInUserName() function
    function getLoggedInUserName()
    {
        session_start();
        // Check if the user is logged in
        if (!isset($_SESSION['username'])) {
            header("Location: index.php");
            exit;
        }
        // Assuming the username is stored in a session variable named 'username'
        if (isset($_SESSION['username'])) {
            return $_SESSION['username'];
        } else {
            return null;
        }
    }


    // Retrieve the logged-in username
    $username = getLoggedInUserName();
    ?>

    <!-- Navigation bar -->
    <div class="navbar">
        <a href="home.php">Home</a>
        <a href="Profile.php" class="active">Profile</a>
        <a href="Mytickets.php">My Tickets</a>
        <a href="Review.php">Review</a>
        <a href="signout.php" style="float:right">Logout</a>
    </div>

    <div class="container">
        <h1>User Profile</h1>
        <h2>Welcome, <?php echo $username; ?></h2>

        <ul>
            <li><a href="Profile/personalinfo.php">Personal Info</a></li>
            <li><a href="Profile/my_car.php">My Car</a></li>
            <li><a href="Mytickets.php">My Ticket</a></li>
            <li><a href="review.php">User Reviews</a></li>
        </ul>
    </div>
</body>

</