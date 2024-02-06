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

$error = array();
$success = "";

// Add payment method
if (isset($_POST['add_payment'])) {
    // Code for adding a new payment method
    // ...

} elseif (isset($_POST['edit_payment'])) {
    // Code for editing a payment method
    // ...

}

// Fetch payment methods for the current customer
$customer_id = $_SESSION['customer_id'];
$query = "SELECT * FROM payment_method_tb WHERE customer_id='$customer_id'";
$result = mysqli_query($con, $query);
$payment_methods = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Payment Methods</title>
    <!-- CSS styles -->
    <style>
        /* CSS styles for the page */
        /* ... */
    </style>
</head>

<body>
    <div class="container">
        <h1>Payment Methods</h1>
        <!-- Display error messages -->
        <?php if (count($error) > 0) { ?>
            <div class="error">
                <?php foreach ($error as $err) { ?>
                    <p><?php echo $err; ?></p>
                <?php } ?>
            </div>
        <?php } ?>
        <!-- Display success message -->
        <?php if ($success !== "") { ?>
            <div class="success">
                <p><?php echo $success; ?></p>
            </div>
        <?php } ?>
        <!-- Add payment method form -->
        <h2>Add Payment Method</h2>
        <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
            <!-- Payment method form fields -->
            <!-- ... -->
            <input type="submit" name="add_payment" value="Add Payment Method">
        </form>
        <!-- Payment methods list -->
        <h2>Payment Methods</h2>
        <div class="payment-methods">
            <?php foreach ($payment_methods as $payment_method) { ?>
                <div class="payment-method">
                    <!-- Display payment method details -->
                    <?php if (isset($payment_method['name'])) { ?>
                        <h3><?php echo $payment_method['name']; ?></h3>
                    <?php } else { ?>
                        <h3>Payment Method Name Unavailable</h3>
                    <?php } ?>
                    <p><strong>Card Number:</strong> <?php echo $payment_method['card_number']; ?></p>
                    <p><strong>Expiration Date:</strong> <?php echo $payment_method['expiration_date']; ?></p>
                    <p><strong>Billing Address:</strong> <?php echo $payment_method['billing_address']; ?></p>
                    <a href="edit_payment.php?payment_id=<?php echo $payment_method['payment_id']; ?>" class="edit-link">Edit</a>
                </div>
            <?php } ?>
        </div>
    </div>
</body>