<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

$con = mysqli_connect("localhost", "root", "", "iziparkin");

if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit;
}

$error = "";
$success = "";

if (isset($_GET['payment_id'])) {
    $paymentId = $_GET['payment_id'];

    if (isset($_POST['edit_payment'])) {
        $cardNumber = mysqli_real_escape_string($con, $_POST['card_number']);
        $expirationDate = mysqli_real_escape_string($con, $_POST['expiration_date']);
        $securityCode = mysqli_real_escape_string($con, $_POST['security_code']);
        $billingAddress = mysqli_real_escape_string($con, $_POST['billing_address']);

        $query = "UPDATE payment_method_tb SET card_number='$cardNumber', expiration_date='$expirationDate', security_code='$securityCode', billing_address='$billingAddress' WHERE payment_id=$paymentId";
        $result = mysqli_query($con, $query);

        if ($result) {
            $success = "Payment method updated successfully.";
        } else {
            $error = "Failed to update payment method.";
        }
    }

    $query = "SELECT * FROM payment_method_tb WHERE payment_id=$paymentId";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) == 1) {
        $paymentMethod = mysqli_fetch_assoc($result);
    } else {
        $error = "Payment method not found.";
    }
}

mysqli_close($con);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Payment Method</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
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

        .success {
            color: green;
            margin-bottom: 10px;
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

        input[type="text"] {
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
    </style>
</head>

<body>
    <div class="container">
        <h1>Edit Payment Method</h1>
        <?php if ($error !== "") { ?>
            <div class="error">
                <p><?php echo $error; ?></p>
            </div>
        <?php } ?>
        <?php if ($success !== "") { ?>
            <div class="success">
                <p><?php echo $success; ?></p>
            </div>
        <?php } ?>
        <?php if (isset($paymentMethod)) { ?>
            <form method="POST" action="<?php echo $_SERVER["PHP_SELF"] . "?payment_id=" . $paymentId; ?>">
                <label for="card_number">Card Number:</label>
                <input type="text" id="card_number" name="card_number" value="<?php echo $paymentMethod['card_number']; ?>" required>
                <label for="expiration_date">Expiration Date:</label>
                <input type="text" id="expiration_date" name="expiration_date" value="<?php echo $paymentMethod['expiration_date']; ?>" required>
                <label for="security_code">Security Code:</label>
                <input type="text" id="security_code" name="security_code" value="<?php echo $paymentMethod['security_code']; ?>" required>
                <label for="billing_address">Billing Address:</label>
                <input type="text" id="billing_address" name="billing_address" value="<?php echo $paymentMethod['billing_address']; ?>" required>
                <input type="submit" name="edit_payment" value="Save Changes">
            </form>
        <?php } ?>
    </div>
</body>

</html>