<!DOCTYPE html>
<html>

<head>
    <title>Register Success</title>
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

        .login-link {
            color: #4D5DFA;
            text-decoration: none;
        }

        .login-link:hover {
            text-decoration: underline;
        }
    </style>
    <script>
        setTimeout(function() {
            window.location.href = "index.php";
        }, 2000);
    </script>
</head>

<body>
    <div class="container">
        <h1>Register Success</h1>
        <p>Your registration was successful! You can now proceed to login.</p>
        <a href="index.php" class="login-link">Login</a>
    </div>
</body>

</html>