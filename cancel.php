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
$success_message = "";


if (isset($_POST['fetch_booking'])) {
    $booking_id = $_POST['booking_id'];

    $query = "SELECT b.booking_id, b.tickets_booked, b.total_price, b.travel_date, bu.source, bu.destination, bu.bus_id
              FROM bookings b
              JOIN buses bu ON b.bus_id = bu.bus_id
              WHERE b.booking_id = ? AND b.user_id = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $booking_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $booking_details = $result->fetch_assoc();
    } else {
        $error_message = "No booking found or this booking does not belong to you.";
    }
    $stmt->close();
}


if (isset($_POST['cancel_booking'])) {
    $booking_id = $_POST['booking_id'];
    $bus_id = $_POST['bus_id'];
    $travel_date = $_POST['travel_date'];
    $tickets_booked = $_POST['tickets_booked'];

    
    $delete_query = "DELETE FROM bookings WHERE booking_id = ? AND user_id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("ii", $booking_id, $user_id);
    
    if ($stmt->execute()) {
        
        $update_seats_query = "UPDATE available_seats SET available_seats = available_seats + ? WHERE bus_id = ? AND date_travel = ?";
        $stmt2 = $conn->prepare($update_seats_query);
        $stmt2->bind_param("iis", $tickets_booked, $bus_id, $travel_date);
        $stmt2->execute();
        $stmt2->close();

        $success_message = "Booking successfully cancelled.";
    } else {
        $error_message = "Failed to cancel booking. Try again.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cancel Booking</title>
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
        .success {
            color: limegreen;
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
        <h2>Cancel Booking</h2>
        <?php if ($error_message): ?>
            <p class="error"><?= $error_message ?></p>
        <?php endif; ?>

        <?php if ($success_message): ?>
            <p class="success"><?= $success_message ?></p>
            <a href="user_option.php" class="btn">Go to Dashboard</a>
        <?php else: ?>
            <form action="" method="POST">
                <label>Enter Booking ID:</label>
                <input type="number" name="booking_id" required>
                <button type="submit" name="fetch_booking" class="btn">Fetch Booking</button>
            </form>

            <?php if ($booking_details): ?>
                <h3>Booking Details</h3>
                <p><strong>Bus Route:</strong> <?= $booking_details['source'] ?> to <?= $booking_details['destination'] ?></p>
                <p><strong>Number of Tickets:</strong> <?= $booking_details['tickets_booked'] ?></p>
                <p><strong>Travel Date:</strong> <?= $booking_details['travel_date'] ?></p>
                <p><strong>Total Price:</strong> ₹<?= $booking_details['total_price'] ?></p>

                <form action="" method="POST">
                    <input type="hidden" name="booking_id" value="<?= $booking_details['booking_id'] ?>">
                    <input type="hidden" name="bus_id" value="<?= $booking_details['bus_id'] ?>">
                    <input type="hidden" name="travel_date" value="<?= $booking_details['travel_date'] ?>">
                    <input type="hidden" name="tickets_booked" value="<?= $booking_details['tickets_booked'] ?>">
                    <button type="submit" name="cancel_booking" class="btn">Cancel Booking</button>
                </form>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>
