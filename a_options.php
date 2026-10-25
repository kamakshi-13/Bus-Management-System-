<?php
session_start();

// Ensure admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: a_login.php");
    exit();
}

// Logout functionality
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: a_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Options</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: black;
            color: white;
            font-family: Arial, sans-serif;
            text-align: center;
        }
        .container {
            width: 40%;
            margin: 100px auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }
        h2 {
            margin-bottom: 20px;
        }
        .btn {
            display: block;
            width: 90%;
            margin: 10px auto;
            padding: 15px;
            font-size: 18px;
            font-weight: bold;
            color: white;
            background-color: #cc3333;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .btn:hover {
            background-color: #aa2222;
        }
        .logout-btn {
            background-color: gray;
        }
        .logout-btn:hover {
            background-color: darkgray;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Admin Dashboard</h2>
        <a href="report.php" class="btn">Report</a>
        <a href="login.php" class="btn">Cancel Booking</a>
        <a href="login.php" class="btn">Book a Ticket</a>
        <a href="a_book.php" class="btn">See Booking by Date</a>

        <form method="POST">
            <button type="submit" name="logout" class="btn logout-btn">Logout</button>
        </form>
    </div>

</body>
</html>
