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

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Please <a href='login.php'>login</a> to book a ticket.");
}

$user_id = $_SESSION['user_id'];
$available_seats = null; // Default: null to check later

// Fetch available seats if bus_id and travel_date are set
if (isset($_POST['bus_id']) && isset($_POST['travel_date'])) {
    $bus_id = $_POST['bus_id'];
    $travel_date = $_POST['travel_date'];

    $seat_query = "SELECT available_seats FROM available_seats WHERE bus_id = ? AND date_travel = ?";
    $stmt = $conn->prepare($seat_query);
    $stmt->bind_param("is", $bus_id, $travel_date);
    $stmt->execute();
    $seat_result = $stmt->get_result();

    if ($seat_result->num_rows > 0) {
        $seat_data = $seat_result->fetch_assoc();
        $available_seats = $seat_data['available_seats'];
    } else {
        $available_seats = 0; // No seats available
    }
    $stmt->close();
}

// Process Booking
if (isset($_POST['proceed_to_pay'])) {
    $bus_id = $_POST['bus_id'];
    $tickets_booked = $_POST['tickets_booked'];
    $travel_date = $_POST['travel_date'];

    if ($available_seats !== null && $tickets_booked > $available_seats) {
        $warning = "⚠️ Not enough seats available!";
    } else {
        // Fetch price
        $price_query = "SELECT price_per_ticket FROM buses WHERE bus_id = ?";
        $stmt = $conn->prepare($price_query);
        $stmt->bind_param("i", $bus_id);
        $stmt->execute();
        $price_result = $stmt->get_result();
        $price_data = $price_result->fetch_assoc();
        $stmt->close();

        $total_price = $tickets_booked * $price_data['price_per_ticket'];

        // Store booking details in session for confirmation
        $_SESSION['booking'] = [
            'user_id' => $user_id,
            'bus_id' => $bus_id,
            'tickets_booked' => $tickets_booked,
            'total_price' => $total_price,
            'date_travel' => $travel_date
        ];

        // Redirect to payment page
        header("Location: payment.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Ticket</title>
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
        select, input {
            width: 80%;
            padding: 10px;
            margin: 10px 0;
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
        .warning {
            color: yellow;
            font-size: 16px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <a href="index1.php">Home</a>
        <a href="contactus.php">Contact Us</a>
        <a href="needhelp.php">Need Help</a>
        <a href="login.php">Login/Register</a>
    </div>

    <div class="container">
        <h2>Book Your Ticket</h2>
        <form action="" method="POST">
            <label>Select Bus:</label>
            <select name="bus_id" required onchange="this.form.submit()">
                <option value="">-- Select Bus --</option>
                <?php
                $bus_query = "SELECT * FROM buses";
                $bus_result = $conn->query($bus_query);
                while ($bus = $bus_result->fetch_assoc()) { ?>
                    <option value="<?= $bus['bus_id'] ?>" <?= isset($bus_id) && $bus_id == $bus['bus_id'] ? 'selected' : '' ?>>
                        <?= $bus['source'] ?> to <?= $bus['destination'] ?>
                    </option>
                <?php } ?>
            </select>

            <label>Select Travel Date:</label>
            <select name="travel_date" required onchange="this.form.submit()">
                <option value="">-- Select Date --</option>
                <?php
                for ($i = 0; $i < 3; $i++) { // ✅ FIX: Start from 0 instead of 1
                    $date_option = date('Y-m-d', strtotime("+$i days"));
                    echo "<option value='$date_option' " . (isset($travel_date) && $travel_date == $date_option ? 'selected' : '') . ">$date_option</option>";
                }
                ?>
            </select>

            <label>Available Seats:
                <strong><?= isset($available_seats) ? $available_seats : 'Select a bus & date' ?></strong>
            </label>

            <?php if (isset($warning)): ?>
                <p class="warning"><?= $warning ?></p>
            <?php endif; ?>

            <label>Number of Seats to Book:</label>
            <input type="number" name="tickets_booked" min="1" max="<?= $available_seats ?? 50 ?>" required <?= (isset($available_seats) && $available_seats == 0) ? 'disabled' : '' ?>>

            <button type="submit" name="proceed_to_pay" class="btn" <?= (isset($available_seats) && $available_seats == 0) ? 'disabled' : '' ?>>Proceed to Pay</button>
        </form>
    </div>
</body>
</html>
