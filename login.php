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

$error_message = ""; 


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    
    $username = mysqli_real_escape_string($conn, $username);

    
    $sql = "SELECT user_id, password FROM users WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];

        
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $row['user_id']; 
            header("Location: user_option.php");
            exit();
        } else {
            $error_message = "Wrong username or password!";
        }
    } else {
        $error_message = "Wrong username or password!";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Bus Ticket Management</title>
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
        .login-container {
            width: 30%;
            margin: 80px auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }
        input {
            width: 80%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
        }
        .btn {
            background-color: #cc3333;
            color: white;
            padding: 10px;
            width: 85%;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn:hover {
            background-color: #aa2222;
        }
        .error {
            color: red;
            font-size: 14px;
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

    <div class="login-container">
        <h2>Login</h2>
        <form action="" method="POST">
            <input type="text" name="username" placeholder="Enter Username" required><br>
            <input type="password" name="password" placeholder="Enter Password" required><br>
            <button type="submit" class="btn">Login</button>
        </form><br>
		 <form action="registeration.php" method="get">
            <button type="submit" class="btn">Not a User?</button>
        </form>
        <?php if (!empty($error_message)) { echo "<p class='error'>$error_message</p>"; } ?>
    </div>
</body>
</html>
