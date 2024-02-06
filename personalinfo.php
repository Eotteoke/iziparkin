<!DOCTYPE html>
<html>

<head>
    <title>User Profile</title>
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

    // Retrieve user's profile from the database
    session_start();
    $userId = $_SESSION['customer_id'];

    $query = "SELECT * FROM customer_tb WHERE customer_id='$userId'";
    $result = mysqli_query($con, $query);
    $user = mysqli_fetch_assoc($result);

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];

        // Update user's profile in the database
        $query = "UPDATE customer_tb SET name='$name', email='$email', phone_number='$phone' WHERE customer_id='$userId'";
        mysqli_query($con, $query);

        // Refresh the user data
        $user['name'] = $name;
        $user['email'] = $email;
        $user['phone_number'] = $phone;

        echo "<h2>Profile updated successfully!</h2>";
    }
    ?>

    <h1>User Profile</h1>

    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="name">Name:</label>
        <input type="text" name="name" value="<?php echo $user['name']; ?>"><br><br>

        <label for="email">Email:</label>
        <input type="text" name="email" value="<?php echo $user['email']; ?>"><br><br>

        <label for="phone">Phone Number:</label>
        <input type="text" name="phone" value="<?php echo $user['phone_number']; ?>"><br><br>

        <button type="submit">Save</button>
    </form>
</body>

</html>