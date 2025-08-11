<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>PHP Calculator</title>
  <style>
    body{
        background:#f0f0f0;
        font-family:Arial,sans-serif;
        display:flex;
        justify-content:center;
        align-items:center;
        height:70vh
    }
    .calculator{
        background:#fff;
        padding:30px;
        border-radius:10px;
        width:300px
    }
    .calculator h2{
        text-align:center;
        margin-bottom:20px
    }
    input[type=number],select{
        width:100%;
        padding:10px;
        margin:10px 0;
        border:1px solid #ccc;
        border-radius:5px
    }
    button{
        width:100%;
        padding:10px;
        background:#28a745;
        color:#fff;
        font-size:16px;
        border:none;
        border-radius:5px;
        margin-top:10px
    }
    .result{
        margin-top:15px;
        font-size:18px;
        text-align:center;
        font-weight:bold
    }
  </style>
</head>
<body>
  <div class="calculator">
    <h2>Simple PHP Calculator</h2>
    <form method="POST">
      <input type="number" name="num1" step="any" placeholder="Enter first number" required>
      <input type="number" name="num2" step="any" placeholder="Enter second number" required> 
      <select name="operator" required>
        <option value="add">Addition (+)</option>
        <option value="sub">Subtraction (-)</option>
        <option value="mul">Multiplication (×)</option>
        <option value="div">Division (÷)</option>
      </select>
      <button type="submit" name="calculate">Calculate</button>
    </form>
    <div class="result">
      <?php
        if (isset($_POST['calculate'])) {
          $num1 = $_POST['num1'];
          $num2 = $_POST['num2'];
          $op = $_POST['operator'];
          $result = '';
          switch ($op) {
            case 'add':
              $result = $num1 + $num2;
              break;
            case 'sub':
              $result = $num1 - $num2;
              break;
            case 'mul':
              $result = $num1 * $num2;
              break;
            case 'div':
              if ($num2 != 0) {
                $result = $num1 / $num2;
              } 
              else {
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