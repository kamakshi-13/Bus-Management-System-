<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "your_bus";


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
$booking_details = null;
$error_message = "";


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['booking_id'])) {
    $booking_id = $_POST['booking_id'];

    
    $query = "SELECT u.name, u.phone, b.booking_id, b.tickets_booked, b.total_price, b.booking_date, b.travel_date, bu.source, bu.destination
              FROM bookings b
              JOIN users u ON b.user_id = u.user_id
              JOIN buses bu ON b.bus_id = bu.bus_id
              WHERE b.booking_id = ? AND b.user_id = ?";
    
    $stmt = $conn->prepare($query);
    
    if (!$stmt) {
        die("SQL Error: " . $conn->error); // Debugging step
    }

    $stmt->bind_param("ii", $booking_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    
    if ($result->num_rows > 0) {
        $booking_details = $result->fetch_assoc();
    } else {
        $error_message = "No reservation found for this Booking ID or it does not belong to you.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Reservation</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: black;
            color: white;
            font-family: Arial, sans-serif;
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
            width: 50%;
            margin: 50px auto;
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
        label {
            font-size: 18px;
            display: block;
            margin-top: 10px;
        }
        input {
            width: 80%;
            padding: 10px;
            margin: 10px 0;
            font-size: 16px;
        }
        .error {
            color: red;
            font-size: 16px;
        }
        .btn {
            background-color: #cc3333;
            color: white;
            padding: 10px 20px;
            font-size: 18px;
            border: none;
            cursor: pointer;
            margin-top: 20px;
        }
        .btn:hover {
            background-color: #a02929;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <a href="user_option.php">Dashboard</a>
        <a href="book.php">Book Ticket</a>
        <a href="cancel.php">Cancel Ticket</a>
        <a href="show.php">View Reservation</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="container">
        <h2>View Reservation</h2>
        <?php if ($error_message): ?>
            <p class="error"><?= $error_message ?></p>
        <?php endif; ?>

        <form action="" method="POST">
            <label>Enter Booking ID:</label>
            <input type="number" name="booking_id" required>
            <button type="submit" class="btn">Fetch Reservation</button>
        </form>

        <?php if ($booking_details): ?>
            <h3>Reservation Details</h3>
            <p><strong>Name:</strong> <?= $booking_details['name'] ?></p>
            <p><strong>Phone:</strong> <?= $booking_details['phone'] ?></p>
            <p><strong>Bus Route:</strong> <?= $booking_details['source'] ?> to <?= $booking_details['destination'] ?></p>
            <p><strong>Number of Tickets:</strong> <?= $booking_details['tickets_booked'] ?></p>
            <p><strong>Travel Date:</strong> <?= $booking_details['travel_date'] ?></p>
            <p><strong>Date of Booking:</strong> <?= $booking_details['booking_date'] ?></p>
            <p><strong>Total Price:</strong> ₹<?= $booking_details['total_price'] ?></p>

            <a href="user_option.php" class="btn">Go to Dashboard</a>
        <?php endif; ?>
    </div>
</body>
</html>
