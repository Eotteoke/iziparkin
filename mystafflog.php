<?php
session_start();

$con = mysqli_connect("localhost", "root", "", "iziparkin");

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$errors = array();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Staff Log Confirm page</title>
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

        .button-container {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }

        .button-container input[type="submit"] {
            width: 100%;
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
        <div class="sign-out">
            <button onclick="window.location.href='signoutStaff.php'">Sign Out</button>
        </div>
        <h1>Staff Log Confirm page</h1>
        <?php if (count($errors) > 0) { ?>
            <div class="error">
                <?php foreach ($errors as $error) { ?>
                    <p><?php echo $error; ?></p>
                <?php } ?>
            </div>
        <?php } ?>
        <div class="button-container">
            <form method="GET" action="mystafflog_in.php">
                <input type="submit" value="Confirm Log In">
            </form>
            <form method="GET" action="mystafflog_out.php">
                <input type="submit" value="Confirm Log Out">
            </form>
        </div>
    </div>
</body>

</html>