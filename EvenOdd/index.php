<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Even Odd Number Checker</title>
  <style>
    body {
      font-family: 'Segoe UI', Arial, sans-serif;
      min-height: 100vh;
      margin: 0;
      padding: 0;
      background: linear-gradient(135deg, #1a0033 0%, #6f42c1 50%, #c154c1 100%);
      background-attachment: fixed;
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .container {
      max-width: 340px;
      margin: 18px auto;
      background: rgba(30, 30, 60, 0.85);
      padding: 16px 12px 14px 12px;
      border-radius: 18px;
      box-shadow: 0 4px 20px 0 rgba(111,66,193,0.18), 0 1px 4px 0 rgba(0,0,0,0.12);
      backdrop-filter: blur(6px);
      border: 2px solid rgba(111,66,193,0.18);
      position: relative;
      overflow: hidden;
      animation: cardPop 1.2s cubic-bezier(.68,-0.55,.27,1.55);
    }
    @keyframes cardPop {
      0% { transform: scale(0.7) translateY(80px); opacity: 0; }
      80% { transform: scale(1.05) translateY(-10px); opacity: 1; }
      100% { transform: scale(1) translateY(0); }
    }
    h2 {
      text-align: center;
      margin-bottom: 28px;
      font-size: 2.1em;
      font-weight: 700;
      background: linear-gradient(90deg, #fff 0%, #c154c1 50%, #6f42c1 100%);
      background-clip: text;
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      text-shadow: 0 2px 16px rgba(111,66,193,0.18);
      letter-spacing: 2px;
      position: relative;
    }
    h2::after {
      content: '';
      display: block;
      width: 80px;
      height: 4px;
      margin: 12px auto 0 auto;
      border-radius: 2px;
      background: linear-gradient(90deg, #6f42c1, #c154c1, #9d50bb, #6f42c1);
      background-size: 200% 200%;
      animation: underlineMove 2.5s linear infinite;
    }
    @keyframes underlineMove {
      0% { background-position: 0% 50%; }
      100% { background-position: 100% 50%; }
    }
    label {
      font-weight: 600;
      letter-spacing: 1px;
      color: #e0d6ff;
      margin-top: 10px;
      margin-bottom: 2px;
      display: block;
      transition: color 0.3s;
    }
    label:hover {
      color: #c154c1;
      text-shadow: 0 0 8px #c154c1;
    }
    input[type=number] {
      width: 100%;
      padding: 1px 2px;
      margin: 4px 0 7px 0;
      font-size: 0.75em;
      border-radius: 7px;
      border: 1.5px solid #6f42c1;
      background: linear-gradient(90deg, #1a0033 0%, #2a1a3a 100%);
      color: #fff;
      outline: none;
      transition: border 0.3s, box-shadow 0.3s;
      box-shadow: 0 1px 4px rgba(111,66,193,0.06);
      height: 16px;
      min-height: 12px;
      max-height: 20px;
    }
    input[type=number]:focus {
      border: 2.5px solid #c154c1;
      box-shadow: 0 0 16px #c154c1;
      background: linear-gradient(90deg, #2a1a3a 0%, #1a0033 100%);
    }
    button[type=submit] {
      width: 100%;
      padding: 14px 0;
      margin: 10px 0 0 0;
      font-size: 1.1em;
      font-weight: 700;
      border-radius: 30px;
      border: none;
      background: linear-gradient(90deg, #6f42c1 0%, #c154c1 100%);
      color: #fff;
      letter-spacing: 2px;
      box-shadow: 0 4px 18px rgba(111,66,193,0.18);
      cursor: pointer;
      position: relative;
      overflow: hidden;
      transition: background 0.3s, transform 0.2s, box-shadow 0.3s;
      animation: floatBtn 2.5s infinite;
    }
    button[type=submit]::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.18), transparent);
      transition: left 0.6s;
    }
    button[type=submit]:hover::before {
      left: 100%;
    }
    button[type=submit]:hover {
      background: linear-gradient(90deg, #c154c1 0%, #6f42c1 100%);
      transform: translateY(-3px) scale(1.04);
      box-shadow: 0 8px 32px #c154c1;
    }
    @keyframes floatBtn {
      0% { transform: translateY(0); }
      50% { transform: translateY(-7px) scale(1.03); }
      100% { transform: translateY(0); }
    }
    table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
      margin-top: 28px;
      background: rgba(26,0,51,0.85);
      border-radius: 18px;
      overflow: hidden;
      box-shadow: 0 4px 24px rgba(111,66,193,0.18);
      animation: tableFadeIn 1.2s cubic-bezier(.68,-0.55,.27,1.55);
    }
    @keyframes tableFadeIn {
      0% { opacity: 0; transform: translateY(40px); }
      100% { opacity: 1; transform: translateY(0); }
    }
    th, td {
      padding: 14px 0;
      border: none;
      text-align: center;
      font-size: 1.1em;
      font-weight: 500;
      color: #fff;
      position: relative;
      z-index: 1;
      transition: background 0.3s, color 0.3s, box-shadow 0.3s;
    }
    th {
      background: linear-gradient(90deg, #6f42c1 0%, #c154c1 100%);
      color: #fff;
      font-size: 1.15em;
      letter-spacing: 1.5px;
      border-bottom: 3px solid #fff2;
      text-shadow: 0 2px 8px #6f42c1;
      animation: thGlow 2.5s infinite alternate;
    }
    @keyframes thGlow {
      0% { box-shadow: 0 0 0 0 #c154c1; }
      100% { box-shadow: 0 0 16px 2px #c154c1; }
    }
    tr {
      animation: rowFadeIn 1.1s cubic-bezier(0.4,0,0.2,1);
    }
    @keyframes rowFadeIn {
      0% { opacity: 0; left: -40px; }
      60% { opacity: 0.7; left: 10px; }
      100% { opacity: 1; left: 0; }
    }
    td {
      background: linear-gradient(90deg, #1a0033 0%, #2a1a3a 100%);
      border-bottom: 1.5px solid #6f42c1;
      border-right: 1.5px solid #c154c1;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(111,66,193,0.08);
    }
    td:last-child {
      border-right: none;
    }
    tr:last-child td {
      border-bottom: none;
    }
    td:hover {
      background: linear-gradient(90deg, #c154c1 0%, #6f42c1 100%);
      color: #fff6ff;
      font-weight: 700;
      box-shadow: 0 0 16px #c154c1;
      z-index: 2;
      transform: scale(1.07);
      transition: all 0.3s;
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

      echo "<table>\n<tr><th>✨ Even</th><th>🌀 Odd</th></tr>";
      $maxCount = max(count($even), count($odd));
      for ($i = 0; $i < $maxCount; $i++) {
        $delay = 0.1 + $i * 0.07;
        echo "<tr style='animation-delay: {$delay}s;'>";
        echo "<td>" . ($even[$i] ?? '') . "</td><td>" . ($odd[$i] ?? '') . "</td>";
        echo "</tr>";
      }
      echo "</table>";
    }
    ?>
  </div>
</body>
</html>