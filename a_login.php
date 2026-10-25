<?php
session_start();

// Hardcoded admin credentials
$admin_username = "kamakshi";
$admin_password = "1234";

$error_message = ""; // Initialize error message variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the entered credentials match the hardcoded ones
    if ($username === $admin_username && $password === $admin_password) {
        $_SESSION['admin_logged_in'] = true; // Set session variable
        header("Location: a_options.php"); // Redirect to admin dashboard
        exit();
    } else {
        $error_message = "Wrong username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        body {
            text-align: center;
            font-family: Arial, sans-serif;
            background-color: black;
            color: white;
        }
        .container {
            margin-top: 100px;
            padding: 20px;
            width: 300px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            margin-left: auto;
            margin-right: auto;
        }
        input {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
        }
        .btn {
            background-color: #cc3333;
            color: white;
            padding: 10px;
            width: 100%;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #aa2222;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <h2>Welcome Admin</h2>
    <div class="container">
        <form action="" method="POST">
            <input type="text" name="username" placeholder="Enter Username" required><br>
            <input type="password" name="password" placeholder="Enter Password" required><br>
            <button type="submit" class="btn">Login</button>
        </form>
        <br>
        <form action="index1.php" method="get">
            <button type="submit" class="btn">Go to Dashboard</button>
        </form>
        <?php if (!empty($error_message)) { echo "<p class='error'>$error_message</p>"; } ?>
    </div>
</body>
</html>
