<?php
session_start();

$con = mysqli_connect("localhost", "root", "", "iziparkin");

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$errors = array();

// Login existing parking lot owner
if (isset($_POST['login_user'])) {
    $staff_email = mysqli_real_escape_string($con, $_POST['staff_email']);
    $staff_password = mysqli_real_escape_string($con, $_POST['staff_password']);

    if (empty($staff_email)) {
        array_push($errors, "Username is required");
    }
    if (empty($staff_password)) {
        array_push($errors, "Password is required");
    }
    if (count($errors) == 0) {
        $query = "SELECT * FROM staff_tb WHERE staff_email = '$staff_email' AND staff_password = '$staff_password'";
        $result = mysqli_query($con, $query);
        if (mysqli_num_rows($result) == 1) {
            $_SESSION['staff_email'] = $staff_email;
            header("Location: loginStaff_success.php");
            exit;
        } else {
            array_push($errors, "Wrong Email or password");
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Login and Registration Page</title>
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
        input[type="password"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 3px;
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

        .register-link {
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Staff Login</h1>
        <?php if (count($errors) > 0) { ?>
            <div class="error">
                <?php foreach ($errors as $error) { ?>
                    <p><?php echo $error; ?></p>
                <?php } ?>
            </div>
        <?php } ?>
        <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
            <label for="staff_email">Email:</label>
            <input type="text" id="staff_email" name="staff_email" required>
            <label for="password">Password:</label>
            <input type="password" id="staff_password" name="staff_password" required>
            <input type="submit" name="login_user" value="Login">
        </form>
    </div>
</body>

</html>