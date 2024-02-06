<!DOCTYPE html>
<html>

<head>
    <title>Booking Success</title>
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
        echo '<h1>Booking Successful</h1>';
        echo '<p>Have fun with your parking lots.</p>';
        ?>
        <a href="home.php" class="return-link">Return to Menu</a>
    </div>
</body>

</html>