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

$error = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $age = trim($_POST['age']);
    $address = trim($_POST['address']);
    $city = trim($_POST['city']);
    $pincode = trim($_POST['pincode']);
    $phone = trim($_POST['phone']);
    $aadhaar = trim($_POST['aadhaar']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']); 

    if (!preg_match("/^[0-9]{10}$/", $phone)) {
        $error = "Phone number must be exactly 10 digits.";
    } elseif (!preg_match("/^[0-9]{6}$/", $pincode)) {
        $error = "Pincode must contain 6 digits exactly.";
    } elseif (!preg_match("/^(?=.*[0-9])(?=.*[a-zA-Z]).{6,}$/", $password)) {
        $error = "Password must be at least 6 characters long and contain both letters and numbers.";
    } else {
        // Check if username already exists
        $check_sql = "SELECT id FROM users WHERE username = '$username'";
        $result = $conn->query($check_sql);

        if ($result && $result->num_rows > 0) {
            $error = "Username already taken. Please choose another.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (name, age, address, city, pin_code, phone, username, password,aadhaar) 
                    VALUES ('$name', '$age', '$address', '$city', '$pincode', '$phone', '$username', '$hashed_password','$aadhaar')";

            if ($conn->query($sql) === TRUE) {
                header("Location: login.php");
                exit();
            } else {
                $error = "Error: " . $conn->error;
            }
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Bus Ticket Management</title>
    <style>
        body {
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
        .content {
            width: 40%;
            margin: 50px auto;
            background: rgba(0, 0, 0, 0.7);
            padding: 30px;
            border-radius: 10px;
        }
        input, button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
        }
        button {
            background-color: #cc3333;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #a82a2a;
        }
        .error {
            color: red;
            font-size: 16px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="index1.php">Home</a>
        <a href="aboutus.php">About Us</a>
        <a href="contactus.php">Contact Us</a>
        <a href="needhelp.php">Need Help</a>
    </div>
    
    <div class="content">
        <h2>Register</h2>

        <?php if (!empty($error)) { echo "<p class='error'>$error</p>"; } ?>

        <form method="POST">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="number" name="age" placeholder="age" min="19" max="99" required>
            <input type="text" name="address" placeholder="Address" required>
            <input type="text" name="city" placeholder="City" required>
            <input type="number" name="pincode" placeholder="Pincode" required>
            <input type="number" name="phone" placeholder="Phone Number" required>
			
            <input type="number" name="aadhaar" placeholder="Adhar Number" pattern="\d{12}" maxlength="12" minlength="12" required title="Aadhaar must be exactly 12 digits">

            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>
