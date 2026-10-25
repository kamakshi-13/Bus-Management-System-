<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Your Bus</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            background: url('login.jpeg') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            color: black;
            height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        h1 {
            font-size: 40px;
            text-align: center;
            position: absolute;
            top: 20px;
			font-color:black;
        }
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 50px;
        }
        button {
            padding: 10px 20px;
            font-size: 18px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            margin: 10px;
        }
        .login-btn {
            background-color: #28a745;
            color: white;
        }
        .register-btn {
            background-color: #007bff;
            color: white;
        }
		.register-btn {
            background-color: #007bff;
            color: white;
        }
        .admin-login {
            background-color: #dc3545;
            color: white;
            position: absolute;
            bottom: 20px;
            right: 20px;
        }
    </style>
</head>
<body>

    <h1>Welcome to Your Bus</h1>

    <div class="container">
        <button class="login-btn" onclick="window.location.href='login.php'">Login</button>
        <button class="register-btn" onclick="window.location.href='registeration.php'">Register</button>
		<button class="admin-btn" onclick="window.location.href='a_login.php'">Admin</button>
    </div>
</body>
</html>
