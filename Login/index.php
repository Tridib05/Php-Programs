<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advanced Login Page</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            font-family: 'Montserrat', 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #8ad9dbff 0%, #098b89ff 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .glass-box {
            background: rgba(255,255,255,0.15);
            border-radius: 32px;
            box-shadow: 0 12px 24px 0 rrgba(3, 33, 35, 0.18)
            backdrop-filter: blur(12px);
            padding: 60px 44px 44px 44px;
            max-width: 440px;
            width: 100%;
            text-align: center;
            position: relative;
            animation: glassPop 1.3s cubic-bezier(.68,-0.55,.27,1.55);
        }
        @keyframes glassPop {
            0% { transform: scale(0.7) translateY(80px); opacity: 0; }
            80% { transform: scale(1.08) translateY(-10px); opacity: 1; }
            100% { transform: scale(1) translateY(0); }
        }
        .glass-box::before {
            content: '';
            position: absolute;
            top: -30px; left: -30px; right: -30px; bottom: -30px;
            border-radius: 40px;
            background: linear-gradient(120deg, #ff6a00 0%, #ee0979 100%);
            opacity: 0.18;
            z-index: -1;
            filter: blur(18px);
        }
        h2 {
            font-size: 2.7em;
            font-weight: 900;
            background: linear-gradient(90deg, #ee0979 0%, #ff6a00 100%);
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 38px;
            letter-spacing: 3px;
            animation: fadeInText 1.3s;
        }
        @keyframes fadeInText {
            0% { opacity: 0; transform: scale(0.95) translateY(-30px); }
            100% { opacity: 1; transform: scale(1) translateY(0); }
        }
        .input-box {
            margin-bottom: 28px;
            animation: fadeInText 1.3s;
        }
        .input-box input {
            width: 100%;
            padding: 18px;
            border-radius: 16px;
            border: 2px solid #ee0979;
            outline: none;
            font-size: 1.25em;
            background: rgba(255,255,255,0.7);
            transition: border 0.3s, box-shadow 0.3s;
            box-shadow: 0 2px 12px rgba(238,9,121,0.10);
            text-align: center;
        }
        .input-box input:focus {
            border: 2.5px solid #ff6a00;
            box-shadow: 0 0 18px #ee0979;
            background: #fff3e6;
        }
        .btn {
            width: 100%;
            padding: 18px;
            background: linear-gradient(90deg,#ee0979 0%,#ff6a00 100%);
            border: none;
            border-radius: 16px;
            color: white;
            font-size: 1.25em;
            font-weight: 900;
            cursor: pointer;
            box-shadow: 0 4px 24px rgba(238,9,121,.18);
            margin-top: 10px;
            transition: background 0.3s, transform 0.2s, box-shadow 0.3s;
            animation: fadeInText 1.3s;
        }
        .btn:hover {
            background: linear-gradient(90deg,#ff6a00 0%,#ee0979 100%);
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 8px 32px #ee0979;
        }
        .message {
            margin-top: 28px;
            font-size: 1.4em;
            font-weight: bold;
            animation: fadeInResult 1.3s;
        }
        @keyframes fadeInResult {
            0% { opacity: 0; transform: scale(0.95); }
            100% { opacity: 1; transform: scale(1); }
        }
    </style>
</head>
<body>
    <div class="glass-box">
        <h2>Stylish Login</h2>
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
            $valid_username = "admin";
            $valid_password = "12345";
            $message = "";
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $username = $_POST['username'];
                $password = $_POST['password'];
                if ($username === $valid_username && $password === $valid_password) {
                    $message = "<span style='color:#ee0979;font-weight:bold;'>✅ Welcome, " . htmlspecialchars($username) . " 🎉</span>";
                } else {
                    $message = "<span style='color:#d32f2f;font-weight:bold;'>❌ Invalid Username or Password!</span>";
                }
            }
        ?>
        <?php echo $message; ?>
        </div>
    </div>
</body>
</html>
