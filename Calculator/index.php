<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Animated PHP Calculator</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body {
      background: linear-gradient(135deg, #00c6ff 0%, #6f42c1 50%, #c154c1 100%);
      min-height: 100vh;
      margin: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Segoe UI', Arial, sans-serif;
      animation: bgMove 8s linear infinite alternate;
    }
    @keyframes bgMove {
      0% { background-position: 0% 50%; }
      100% { background-position: 100% 50%; }
    }
    .calculator {
      background: rgba(255,255,255,0.95);
      padding: 40px 32px 32px 32px;
      border-radius: 28px;
      box-shadow: 0 8px 40px 0 rgba(111,66,193,0.18), 0 1.5px 8px 0 rgba(0,0,0,0.18);
      max-width: 350px;
      width: 100%;
      position: relative;
      animation: cardPop 1.2s cubic-bezier(.68,-0.55,.27,1.55);
    }
    @keyframes cardPop {
      0% { transform: scale(0.7) translateY(80px); opacity: 0; }
      80% { transform: scale(1.05) translateY(-10px); opacity: 1; }
      100% { transform: scale(1) translateY(0); }
    }
    h2 {
      text-align: center;
      font-size: 2em;
      font-weight: 700;
      background: linear-gradient(90deg, #6f42c1 0%, #c154c1 100%);
      background-clip: text;
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      margin-bottom: 28px;
      letter-spacing: 2px;
      animation: headingPop 1.2s cubic-bezier(.68,-0.55,.27,1.55);
    }
    @keyframes headingPop {
      0% { transform: scale(0.7) translateY(-60px); opacity: 0; }
      80% { transform: scale(1.08) translateY(10px); opacity: 1; }
      100% { transform: scale(1) translateY(0); }
    }
    .form-row {
      display: flex;
      gap: 10px;
      align-items: center;
      margin-bottom: 14px;
    }
    .form-row label {
      flex: 1;
      font-weight: 600;
      color: #6f42c1;
    }
    .form-row input[type=number] {
      flex: 1.2;
      border-radius: 18px;
      padding: 10px 18px;
      font-size: 1em;
      border: 2px solid #c154c1;
      background: linear-gradient(90deg, #f0e6ff 0%, #e6e6ff 100%);
      color: #333;
      transition: border 0.3s, box-shadow 0.3s;
      box-shadow: 0 2px 8px rgba(111,66,193,0.08);
    }
    .form-row input[type=number]:focus {
      border: 2.5px solid #6f42c1;
      box-shadow: 0 0 16px #c154c1;
      background: linear-gradient(90deg, #e6e6ff 0%, #f0e6ff 100%);
    }
    select {
      width: 100%;
      padding: 10px;
      border-radius: 18px;
      border: 2px solid #6f42c1;
      font-size: 1em;
      margin-bottom: 18px;
      background: linear-gradient(90deg, #f0e6ff 0%, #e6e6ff 100%);
      color: #333;
      font-weight: 600;
      transition: border 0.3s, box-shadow 0.3s;
    }
    select:focus {
      border: 2.5px solid #c154c1;
      box-shadow: 0 0 16px #c154c1;
    }
    .form-btn {
      display: flex;
      justify-content: center;
      margin-top: 10px;
    }
    .form-btn button {
      border-radius: 30px;
      padding: 12px 38px;
      font-size: 1.15em;
      background: linear-gradient(90deg,#6f42c1 0%,#c154c1 100%);
      color: #fff;
      border: none;
      font-weight: 700;
      box-shadow: 0 4px 18px rgba(111,66,193,.18);
      cursor: pointer;
      transition: background 0.3s, transform 0.2s, box-shadow 0.3s;
      animation: floatBtn 2.5s infinite;
    }
    @keyframes floatBtn {
      0% { transform: translateY(0); }
      50% { transform: translateY(-7px) scale(1.03); }
      100% { transform: translateY(0); }
    }
    .form-btn button:hover {
      background: linear-gradient(90deg,#c154c1 0%,#6f42c1 100%);
      transform: translateY(-3px) scale(1.04);
      box-shadow: 0 8px 32px #c154c1;
    }
    .result {
      margin-top: 24px;
      font-size: 1.3em;
      text-align: center;
      font-weight: bold;
      color: #6f42c1;
      background: linear-gradient(90deg, #e6e6ff 0%, #f0e6ff 100%);
      border-radius: 18px;
      padding: 16px;
      box-shadow: 0 2px 8px rgba(111,66,193,0.08);
      animation: fadeIn 1.2s;
    }
    @keyframes fadeIn {
      0% { opacity: 0; transform: translateY(20px); }
      100% { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>
  <div class="calculator">
    <h2>Animated PHP Calculator</h2>
    <form method="POST">
      <div class="form-row">
        <label>First Number:</label>
        <input type="number" name="num1" step="any" required>
      </div>
      <div class="form-row">
        <label>Second Number:</label>
        <input type="number" name="num2" step="any" required>
      </div>
      <div class="form-row">
        <label>Third Number:</label>
        <input type="number" name="num3" step="any" required>
      </div>
      <select name="operator" required>
        <option value="">Select Operation</option>
        <option value="add">Addition (+)</option>
        <option value="sub">Subtraction (-)</option>
        <option value="mul">Multiplication (×)</option>
        <option value="div">Division (÷)</option>
      </select>
      <div class="form-btn">
        <button type="submit" name="calculate">Calculate</button>
      </div>
    </form>
    <div class="result">
      <?php
        if (isset($_POST['calculate'])) {
          $num1 = $_POST['num1'];
          $num2 = $_POST['num2'];
          $num3 = $_POST['num3'];
          $op = $_POST['operator'];
          $result = '';
          switch ($op) {
            case 'add':
              $result = $num1 + $num2 + $num3;
              break;
            case 'sub':
              $result = $num1 - $num2 - $num3;
              break;
            case 'mul':
              $result = $num1 * $num2 * $num3;
              break;
            case 'div':
              if ($num2 != 0 && $num3 != 0) {
                $result = $num1 / $num2 / $num3;
              } else {
                $result = "Cannot divide by zero";
              }
              break;
            default:
              $result = "Invalid operation selected.";
          }
          echo "Result: " . $result;
        }
      ?>
    </div>
  </div>
</body>
</html>
