<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Options - Bus Ticket Management</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: black;
            color: white;
            font-family: Arial, sans-serif;
            text-align: center;
        }
        .navbar {
            background-color: #cc3333;
            padding: 15px;
            text-align: center;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-size: 18px;
            font-weight: bold;
        }
        .navbar a:hover {
            text-decoration: underline;
        }
        .container {
            margin-top: 50px;
        }
        .option-box {
            background: #222;
            padding: 20px;
            margin: 20px auto;
            width: 300px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 20px;
            font-weight: bold;
            transition: 0.3s;
        }
        .option-box:hover {
            background: #cc3333;
        }
        a {
            text-decoration: none;
            color: white;
        }
    </style>
</head>
<body>

    <div class="navbar">
        
        
        <a href="login.php">Logout</a>
    </div>

    <div class="container">
        <h1>Welcome to Your Dashboard</h1>
        <div class="option-box"><a href="book.php">Book a Ticket</a></div>
        <div class="option-box"><a href="cancel.php">Cancel a Ticket</a></div>
        <div class="option-box"><a href="show.php">View Ticket</a></div>
    </div>

</body>
</html>
