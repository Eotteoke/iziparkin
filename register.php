<?php
session_start();

$con = mysqli_connect("localhost", "root", "", "iziparkin");

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$error = array();

if (isset($_POST['register_user'])) {
    // Collect form data
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $phone_number = mysqli_real_escape_string($con, $_POST['phone_number']);
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    // Check if the username already exists
    $query = "SELECT * FROM customer_tb WHERE username='$username'";
    $result = mysqli_query($con, $query);
    if (mysqli_num_rows($result) > 0) {
        array_push($error, "Username already exists");
    } else {
        if (count($error) == 0) {
            // Insert data into the database
            $query = "INSERT INTO customer_tb (name, email, phone_number, username, password) VALUES ('$name', '$email', '$phone_number', '$username', '$password')";
            $result = mysqli_query($con, $query);
            if ($result) {
                $_SESSION['username'] = $username;
                header("Location: register_success.php");
                exit;
            } else {
                array_push($error, "Error occurred while registering");
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Register Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 400px;
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

        .error {
            color: red;
            margin-bottom: 10px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="tel"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        input[type="submit"] {
            background-color: #4D5DFA;
            color: #fff;
            padding: 10px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }

        input[type="submit"]:hover {
            background-color: #3244A8;
        }

        .login-link {
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Register</h1>
        <?php if (count($error) > 0) { ?>
            <div class="error">
                <?php foreach ($error as $err) { ?>
                    <p><?php echo $err; ?></p>
                <?php } ?>
            </div>
        <?php } ?>
        <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="phone_number">Phone Number:</label>
            <input type="tel" id="phone_number" name="phone_number" pattern="[0-9]{10}" required>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <input type="submit" name="register_user" value="Register">
        </form>
        <div class="login-link">
            <p>Already have an account? <a href="index.php">Login here</a></p>
        </div>
    </div>
</body>

</html>