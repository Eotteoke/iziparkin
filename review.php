<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $con = mysqli_connect("127.0.0.1", "root", "", "iziparkin");
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    // Retrieve the form data (rating)
    $customer_id = $_POST['customer_id'];
    $parking_lot_id = $_POST['parking_lot_id'];
    $rating_date = $_POST['rating_date'];
    $cleanliness_rating = $_POST['cleanliness_rating'];
    $security_rating = $_POST['security_rating'];
    $convenience_rating = $_POST['convenience_rating'];
    $price_rating = $_POST['price_rating'];
    $overall_rating = ($cleanliness_rating + $security_rating + $convenience_rating + $price_rating) / 4;

    // Perform database insertion for rating_tb
    $sql = "INSERT INTO rating_tb (customer_id, parking_lot_id, rating_date, cleanliness_rating, security_rating, convenience_rating, price_rating, overall_rating) VALUES ('$customer_id', '$parking_lot_id', '$rating_date', '$cleanliness_rating', '$security_rating', '$convenience_rating', '$price_rating', '$overall_rating')";

    if (mysqli_query($con, $sql)) {
        // Retrieve the auto-generated rating_id
        $rating_id = mysqli_insert_id($con);

        // Retrieve the form data for review
        $review_text = $_POST['review_text'];
        $review_photo = $_POST['review_photo'];
        $review_video = $_POST['review_video'];

        // Perform database insertion for review_tb using the retrieved rating_id
        $sql = "INSERT INTO review_tb (rating_id, review_text, review_photo, review_video) VALUES ('$rating_id', '$review_text', '$review_photo', '$review_video')";

        if (mysqli_query($con, $sql)) {
        } else {
            echo "Error: " . mysqli_error($con);
        }
    } else {
        echo "Error: " . mysqli_error($con);
    }

    // Close the database connection
    mysqli_close($con);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Process Rating and Review</title>
    <style>
        /* CSS styles for the UI design */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
        }

        .main-title {
            margin: 20px;
            font-size: 24px;
        }

        .booking-form {
            margin-top: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 20px;
            margin: 20px;
        }

        .booking-form label {
            font-size: 16px;
            margin-bottom: 5px;
            display: block;
        }

        .booking-form input[type="text"],
        .booking-form input[type="number"],
        .booking-form input[type="date"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            margin-bottom: 10px;
        }

        .booking-form select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            margin-bottom: 10px;
        }

        .booking-form button[type="submit"] {
            background-color: #4D5DFA;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .booking-form button[type="submit"]:hover {
            background-color: #2d3dc6;
        }

        /* Add more custom styles as needed */
    </style>
</head>

<body>
    <h1 class="main-title">Give us a review</h1>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="booking-form">

        <label for="customer_id">Customer ID:</label>
        <input type="text" id="customer_id" name="customer_id" required>
        <br><br>

        <label for="parking_lot_id">Parking Lot ID:</label>
        <input type="text" id="parking_lot_id" name="parking_lot_id" required>
        <br><br>

        <label for="rating_date">Rating Date:</label>
        <input type="date" id="rating_date" name="rating_date" required>
        <br><br>

        <label>Cleanliness rating:</label>
        <select name="cleanliness_rating" required>
            <option value="">Select cleanliness rating</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
        </select>
        <br><br>

        <label>Security rating:</label>
        <select name="security_rating" required>
            <option value="">Select security rating</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
        </select>
        <br><br>

        <label>Convenience rating:</label>
        <select name="convenience_rating" required>
            <option value="">Select convenience rating</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
        </select>
        <br><br>

        <label>Price rating:</label>
        <select name="price_rating" required>
            <option value="">Select price rating</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
        </select>
        <br><br>

        <label for="review_text">Review Text:</label>
        <textarea id="review_text" name="review_text"></textarea>
        <br><br>

        <label for="review_photo">Review Photo:</label>
        <input type="file" id="review_photo" name="review_photo">
        <br><br>

        <label for="review_video">Review Video:</label>
        <input type="file" id="review_video" name="review_video">
        <br><br>

        <button type="submit">Submit</button>
    </form>
</body>

</html>