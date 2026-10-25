<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "your_bus";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure booking session exists
if (!isset($_SESSION['booking'])) {
    die("Error: No booking data found.");
}

$user_id = $_SESSION['booking']['user_id'];
$bus_id = $_SESSION['booking']['bus_id'];
$tickets_booked = $_SESSION['booking']['tickets_booked'];
$total_price = $_SESSION['booking']['total_price'];
$date_travel = $_SESSION['booking']['date_travel']; // Correct column name

// Insert booking record
$insert_query = "INSERT INTO bookings (user_id, bus_id, tickets_booked, total_price, travel_date) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($insert_query);
if (!$stmt) {
    die("Error in query: " . $conn->error);
}
$stmt->bind_param("iiids", $user_id, $bus_id, $tickets_booked, $total_price, $date_travel);

if ($stmt->execute()) {
    $booking_id = $stmt->insert_id;

    // Update available seats
    $update_seats_query = "UPDATE available_seats SET available_seats = available_seats - ? WHERE bus_id = ? AND date_travel = ?";
    $stmt_update = $conn->prepare($update_seats_query);
    if (!$stmt_update) {
        die("Error in query: " . $conn->error);
    }
    $stmt_update->bind_param("iis", $tickets_booked, $bus_id, $date_travel);

    if (!$stmt_update->execute()) {
        die("Error updating seats: " . $conn->error);
    }

    $stmt_update->close();
    unset($_SESSION['booking']); // Clear booking session
} else {
    die("Error in booking. Please try again. " . $conn->error);
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
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
            width: 50%;
            margin: 50px auto;
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
        }
        h2 {
            color: #4CAF50;
        }
        p {
            font-size: 18px;
        }
        .btn {
            background-color: #cc3333;
            color: white;
            padding: 10px 20px;
            font-size: 18px;
            border: none;
            cursor: pointer;
            margin-top: 20px;
            text-decoration: none;
            display: inline-block;
        }
        .btn:hover {
            background-color: #a02929;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Booking Confirmed!</h2>
        <p>Thank you for booking your ticket.</p>
        <p><strong>Your Booking ID is: <?= htmlspecialchars($booking_id); ?></strong></p>
        <p>Please remember this Booking ID for future reference.</p>
        <a href="user_option.php" class="btn">Go Back</a>
    </div>
</body>
</html>
