<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Even Odd Checker</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 10vh;
            background: #f0f0f0;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            max-width: 400px;
            width: 100%;
        }
        input[type="number"], input[type="submit"] 
        {
            width: 100%;
            padding: 8px;
            margin-top: 8px;
            font-size: 16px;
        }
        .result 
        {
            margin-top: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>
  <div class="container">
    <h2>Even Odd Number Checker</h2>
    <form method="post">
      <label>Enter Starting Point:</label>
      <input type="number" name="start" required>
      <label>Enter Ending Point:</label>
      <input type="number" name="end" required>
      <button type="submit" name="submit">Go</button>
    </form>
    <?php
    if (isset($_POST['submit'])) {
      $start = $_POST['start'];
      $end = $_POST['end'];
      $even = [];
      $odd = [];
      for ($i = $start; $i <= $end; $i++) {
        if ($i % 2 == 0) {
          $even[] = $i;
        } else {
          $odd[] = $i;
        }
      }
      echo "<div style='margin-top:30px;'>";
      echo "<table border='1' style='width:100%;text-align:center;'>";
      echo "<tr><th>Even</th><th>Odd</th></tr>";
      $maxCount = max(count($even), count($odd));
      for ($i = 0; $i < $maxCount; $i++) {
        echo "<tr>";
        echo "<td>" . ($even[$i] ?? '') . "</td><td>" . ($odd[$i] ?? '') . "</td>";
        echo "</tr>";
      }
      echo "</table></div>";
    }
    ?>
    </div>
</body>
</html>