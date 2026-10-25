<?php
session_start();
if (!isset($_SESSION['booking'])) {
    die("Invalid access. Please book a ticket first.");
}

$total_price = $_SESSION['booking']['total_price'];

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selected_month = (int)$_POST['exp_month'];
    $selected_year = (int)$_POST['exp_year'];

    $current_year = (int)date('Y');
    $current_month = (int)date('m');

    if ($selected_year < $current_year || ($selected_year == $current_year && $selected_month < $current_month)) {
        $error_message = "Card has expired";
    } else {
        header("Location: confirm_booking.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: url('payment.jpeg') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            color: white;
            text-align: center;
        }
        .container {
            width: 40%;
            margin: 100px auto;
            background: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-radius: 10px;
        }
        h2 {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
        }
        td {
            padding: 10px;
            font-size: 18px;
            border-bottom: 1px solid white;
        }
        input, select {
            width: 90%;
            padding: 10px;
            font-size: 16px;
            margin: 10px 0;
        }
        .btn {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            font-size: 18px;
            border: none;
            cursor: pointer;
            margin-top: 20px;
            width: 100%;
        }
        .btn:hover {
            background-color: #218838;
        }
        .error {
            color: red;
            font-size: 16px;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Complete Your Payment</h2>

    <table>
        <tr>
            <td><strong>Amount to Pay:</strong></td>
            <td>₹<?= $total_price ?></td>
        </tr>
        <tr>
            <td><strong>Payment Method:</strong></td>
            <td>Credit/Debit Card</td>
        </tr>
    </table>

    <form method="POST">
        <label>Enter Card Number:</label>
        <input type="text" name="card_number" pattern="\d{16}" title="Enter a 16-digit card number" required>

        <label>Expiry Month:</label>
        <select name="exp_month" required>
            <?php
            for ($m = 1; $m <= 12; $m++) {
                $month_value = str_pad($m, 2, "0", STR_PAD_LEFT);
                echo "<option value='$month_value'>$month_value</option>";
            }
            ?>
        </select>

        <label>Expiry Year:</label>
        <select name="exp_year" required>
            <?php
            $current_year = (int)date("Y");
            for ($y = $current_year; $y <= $current_year + 5; $y++) {
                echo "<option value='$y'>$y</option>";
            }
            ?>
        </select>
		<br>

        <label>CVV:</label>
        <input type="password" name="cvv" pattern="\d{3}" title="Enter a 3-digit CVV" required>

        <?php if (!empty($error_message)): ?>
            <div class="error"><?= $error_message ?></div>
        <?php endif; ?>

        <button type="submit" class="btn">Pay Now</button>
    </form>
</div>

</body>
</html>
