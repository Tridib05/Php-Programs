<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<head>
    <title>Electricity Bill Calculator</title>
    <style>
    body {
        background: linear-gradient(135deg, #00c6ff 0%, #6f42c1 50%, #c154c1 100%);
        min-height: 100vh;
        margin: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Segoe UI', Arial, sans-serif;
    }
    .animated-container {
        background: rgba(255,255,255,0.98);
        padding: 48px 38px 38px 38px;
        border-radius: 38px;
        box-shadow: 0 12px 48px 0 rgba(111,66,193,0.22), 0 2px 12px 0 rgba(0,0,0,0.13);
        max-width: 420px;
        width: 100%;
        position: relative;
        animation: popIn 1.2s cubic-bezier(.68,-0.55,.27,1.55);
    }
    @keyframes popIn {
        0% { transform: scale(0.7) translateY(60px); opacity: 0; }
        80% { transform: scale(1.05) translateY(-8px); opacity: 1; }
        100% { transform: scale(1) translateY(0); }
    }
    h1 {
        text-align: center;
        font-size: 2.7em;
        font-weight: 800;
        background: linear-gradient(90deg, #6f42c1 0%, #c154c1 100%);
        background-clip: text;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 32px;
        letter-spacing: 2.5px;
        animation: fadeInText 1.2s;
    }
    @keyframes fadeInText {
        0% { opacity: 0; transform: scale(0.95) translateY(-20px); }
        100% { opacity: 1; transform: scale(1) translateY(0); }
    }
    .form-group {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-bottom: 18px;
        animation: fadeInText 1.2s;
    }
    label {
        font-size: 1.25em;
        font-weight: 700;
        color: #6f42c1;
        letter-spacing: 1px;
        margin-bottom: 6px;
        animation: fadeInText 1.2s;
    }
    input[type=number] {
        border-radius: 22px;
        padding: 14px 22px;
        font-size: 1.15em;
        border: 2px solid #c154c1;
        background: linear-gradient(90deg, #f0e6ff 0%, #e6e6ff 100%);
        color: #333;
        transition: border 0.3s, box-shadow 0.3s;
        box-shadow: 0 2px 8px rgba(111,66,193,0.08);
    }
    input[type=number]:focus {
        border: 2.5px solid #6f42c1;
        box-shadow: 0 0 16px #c154c1;
        background: linear-gradient(90deg, #e6e6ff 0%, #f0e6ff 100%);
    }
    .form-group button {
        border-radius: 30px;
        padding: 14px 0;
        font-size: 1.25em;
        background: linear-gradient(90deg,#6f42c1 0%,#c154c1 100%);
        color: #fff;
        border: none;
        font-weight: 800;
        box-shadow: 0 4px 18px rgba(111,66,193,.18);
        cursor: pointer;
        transition: background 0.3s, transform 0.2s, box-shadow 0.3s;
        margin-top: 10px;
        animation: fadeInText 1.2s;
    }
    .form-group button:hover {
        background: linear-gradient(90deg,#c154c1 0%,#6f42c1 100%);
        transform: translateY(-3px) scale(1.04);
        box-shadow: 0 8px 32px #c154c1;
    }
    .result {
        margin-top: 28px;
        font-size: 1.5em;
        text-align: center;
        font-weight: bold;
        color: #6f42c1;
        background: linear-gradient(90deg, #e6e6ff 0%, #f0e6ff 100%);
        border-radius: 22px;
        padding: 20px;
        box-shadow: 0 2px 12px rgba(111,66,193,0.13);
        animation: fadeInResult 1.2s;
    }
    @keyframes fadeInResult {
        0% { opacity: 0; transform: scale(0.95); }
        100% { opacity: 1; transform: scale(1); }
    }
    </style>
    <div class="animated-container">
        <h1>Electricity Bill Calculator</h1>
        <form method="post">
            <div class="form-group">
                <label for="units">Enter number of units:</label>
                <input type="number" id="units" name="units" min="0" required>
                <button type="submit" name="calculate">Calculate Bill</button>
            </div>
        </form>
        <?php
        if (isset($_POST['calculate'])) {
            function calculateElectricityBill($units) {
                $rate1 = 3.50;
                $rate2 = 4.00;
                $rate3 = 5.20;
                $rate4 = 6.50;
                $totalBill = 0.00;
                if ($units <= 50) {
                    $totalBill = $units * $rate1;
                }
                elseif ($units <= 150) {
                    $totalBill = (50 * $rate1) + (($units - 50) * $rate2);
                }
                elseif ($units <= 250) {
                    $totalBill = (50 * $rate1) + (100 * $rate2) + (($units - 150) * $rate3);
                }
                else {
                    $totalBill = (50 * $rate1) + (100 * $rate2) + (100 * $rate3) + (($units - 250) * $rate4);
                }
                return $totalBill;
            }
            $unitsUsed = $_POST['units'];
            $billAmount = calculateElectricityBill($unitsUsed);
            echo "<div class='result'>Total electricity bill for $unitsUsed units is Rs " . number_format($billAmount, 2) . "</div>";
        }
        ?>
    </div>
</body>
</html>