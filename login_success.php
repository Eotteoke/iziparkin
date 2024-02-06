<!DOCTYPE html>
<html>

<head>
    <title>Login Success</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            text-align: center;
        }

        .container {
            max-width: 500px;
            margin: 0 auto;
            padding: 40px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        p {
            font-size: 16px;
            margin-bottom: 20px;
        }

        .return-link {
            color: #4D5DFA;
            text-decoration: none;
        }

        .return-link:hover {
            text-decoration: underline;
        }
    </style>
    <script>
        setTimeout(function() {
            window.location.href = "home.php";
        }, 2000);
    </script>
</head>

<body>
    <div class="container">
        <?php
        session_start();
        // Check if the user is logged in
        if (isset($_SESSION['username'])) {
            $username = $_SESSION['username'];

            // Database connection
            $con = mysqli_connect("localhost", "root", "", "iziparkin");

            // Fetch customer ID from the database
            $query = "SELECT customer_id FROM customer_tb WHERE username='$username'";
            $result = mysqli_query($con, $query);
            $row = mysqli_fetch_assoc($result);
            $customerId = $row['customer_id'];

            mysqli_close($con);

            echo '<h1>Login Success</h1>';
            echo '<p>Welcome, ' . $username . '.</p>';

            // Set the customer ID in the session
            $_SESSION['customer_id'] = $customerId;
        } else {
            echo '<h1>Login Success</h1>';
            echo '<p>Welcome, User.</p>';
        }
        ?>
        <a href="home.php" class="return-link">Return to Menu</a>
    </div>
</body>

</html>