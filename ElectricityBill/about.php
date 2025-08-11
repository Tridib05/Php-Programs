<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electricity Bill Calculator</title>
    <style>
    body { background: #f7f7f7; font-family: Arial, sans-serif; }
    .card {
        background: #fff;
        padding: 32px;
        border-radius: 10px;
        width: 380px;
        margin: 60px auto;
        box-shadow: 0 2px 10px #ccc;
        text-align: center;
        height:30vh
    }
    h1 {
        font-size: 2em;
        margin-bottom: 18px;
        font-weight: bold;
    }
    input[type="number"] {
        width: 90%;
        margin: 12px auto;
        padding: 12px;
        font-size: 1.2em;
        border-radius: 6px;
        border: 1px solid #ccc;
        display: block;
        text-align: center;
    }
    button {
        width: 92%;
        padding: 12px;
        font-size: 1.15em;
        border-radius: 6px;
        background: #28a745;
        color: #fff;
        border: none;
        margin: 14px auto 0 auto;
    }
    .result {
        margin-top: 25px;
        font-size: 1.7em;
        font-weight: bold;
        text-align: center;
    }
    </style>
</head>
<body>
    <div class="card">
        <h1>Electricity Bill Calculator</h1>
        <form method="post">
            <input type="number" id="units" name="units" min="0" placeholder="Units" required>
            <button type="submit" name="calculate">Calculate Bill</button>
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
            echo "<div class='result'>Bill for $unitsUsed units: Rs " . number_format($billAmount, 2) . "</div>";
        }
        ?>
    </div>
</body>
</html>