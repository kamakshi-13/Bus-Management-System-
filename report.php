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

// Ensure admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: a_login.php");
    exit();
}

// Fetch all bookings
$sql = "SELECT b.booking_id, u.user_id, u.username, bu.source, bu.destination, 
               b.tickets_booked, b.total_price, b.travel_date 
        FROM bookings b
        JOIN users u ON b.user_id = u.user_id
        JOIN buses bu ON b.bus_id = bu.bus_id
        ORDER BY b.travel_date DESC";

$result = $conn->query($sql);

// Debugging: Check if query executed successfully
if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Report</title>
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
            width: 80%;
            margin: 50px auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            color: black;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid black;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #cc3333;
            color: white;
        }
        .btn {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 10px;
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
    </style>
</head>
<body>

    <div class="container">
        <h2>Booking Report</h2>

        <table>
            <tr>
                <th>Booking ID</th>
                <th>User ID</th>
                <th>Username</th>
                <th>Bus Details</th>
                <th>Tickets Booked</th>
                <th>Price Per Ticket</th>
                <th>Total Price</th>
                <th>Date of Travel</th>
            </tr>

            <?php
            if (isset($result) && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $pricePerTicket = ($row['tickets_booked'] > 0) ? $row['total_price'] / $row['tickets_booked'] : 0;
                    echo "<tr>
                        <td>{$row['booking_id']}</td>
                        <td>{$row['user_id']}</td>
                        <td>{$row['username']}</td>
                        <td>{$row['source']} → {$row['destination']}</td>
                        <td>{$row['tickets_booked']}</td>
                        <td>₹" . number_format($pricePerTicket, 2) . "</td>
                        <td>₹{$row['total_price']}</td>
                        <td>{$row['travel_date']}</td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No bookings found.</td></tr>";
            }
            ?>
        </table>

        <a href="a_options.php" class="btn">Go Back</a>
    </div>

</body>
</html>

<?php $conn->close(); ?>
