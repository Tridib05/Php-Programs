<?php
    $server = "localhost";
    $username = "root";
    $password = "";
    $db_name = "gist";

    $con = mysqli_connect($server, $username, $password, $db_name);

    if ($con) {
        $sql = "SELECT * FROM student";
        $result = $con->query($sql);

        if ($result->num_rows > 0) {
            echo '<!DOCTYPE html>
            <html>
            <head>
                <style>
                    body {
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        min-height: 100vh;
                        background-color: #f0f0f0;
                        font-family: Arial, sans-serif;
                    }
                    table {
                        border-collapse: collapse;
                        width: 70%;
                        background: white;
                        box-shadow: 0px 4px 8px rgba(0,0,0,0.2);
                        text-align: center;
                        font-size: 18px;
                    }
                    th, td {
                        border: 1px solid #444;
                        padding: 12px;
                    }
                    th {
                        background-color: #4CAF50;
                        color: white;
                        font-size: 20px;
                    }
                    tr:nth-child(even) {
                        background-color: #f2f2f2;
                    }
                    tr:hover {
                        background-color: #ddd;
                    }
                </style>
            </head>
            <body>';

            echo '<table>';
            echo '<tr><th>Roll</th><th>Name</th><th>Dept</th><th>Phone</th></tr>';

            while ($row = $result->fetch_assoc()) {
                echo '<tr>
                        <td>'.$row["roll"].'</td>
                        <td>'.$row["name"].'</td>
                        <td>'.$row["dept"].'</td>
                        <td>'.$row["phone no"].'</td>
                      </tr>';
            }

            echo '</table>';
            
            echo '</body></html>';
        } else {
            echo "No results found!";
        }
    } else {
        echo "Connection failed!";
    }

    $con->close();
?>
