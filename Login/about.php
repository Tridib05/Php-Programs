<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 70vh;
        }
        .login-container {
            background: white;
            padding: 48px;
            width: 420px;
            border-radius: 16px;
            box-shadow: 0px 6px 18px rgba(8, 8, 8, 0.18);
            text-align: center;
        }
        .login-container h2 {
            margin-bottom: 28px;
            color: #333;
            font-size: 2.2em;
            font-weight: bold;
        }
        .input-box {
            margin-bottom: 22px;
        }
        .input-box input {
            width: 100%;
            padding: 16px;
            border: 1px solid #aaa;
            border-radius: 8px;
            outline: none;
            font-size: 1.3em;
            text-align: center;
        }
        .btn {
            width: 100%;
            padding: 16px;
            background: #28a745;
            border: none;
            border-radius: 8px;
            font-size: 1.3em;
            font-weight: bold;
            color: white;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .btn:hover {
            background: #218838;
        }
        .message {
            margin-top: 22px;
            font-size: 1.0em;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form method="POST">
            <div class="input-box">
                <input type="text" name="username" placeholder="Enter Username" required>
            </div>
            <div class="input-box">
                <input type="password" name="password" placeholder="Enter Password" required>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
        <div class="message">
        <?php
            // --- PHP Login Validation ---
            $valid_username = "admin";
            $valid_password = "12345";
            $message = "";
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $username = $_POST['username'];
                $password = $_POST['password'];
                if ($username === $valid_username && $password === $valid_password) {
                    $message = "<h2 style='color:green;'>✅ Welcome, " . htmlspecialchars($username) . " 🎉</h2>";
                } else {
                    $message = "<h2 style='color:red;'>❌ Invalid Username or Password!</h2>";
                }
            }
        ?>
        <?php echo $message; ?>
        </div>
    </div>
</body>
</html>