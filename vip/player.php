<?php
require_once "db_connect.php"; // Kết nối database

$sql = "SELECT * FROM player";
$stmt = $conn->query($sql);
$players = $stmt->fetchAll(PDO::FETCH_ASSOC);
include 'menu.php'; 
$conn = null;
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách Player</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
            white-space: nowrap;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #ddd;
        }
        .container {
            max-width: 95%;
            margin: auto;
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Danh sách Player</h2>
        <table>
            <tr>
                <?php foreach (array_keys($players[0]) as $colName): ?>
                    <th><?= htmlspecialchars($colName) ?></th>
                <?php endforeach; ?>
            </tr>
            <?php foreach ($players as $player): ?>
            <tr>
                <?php foreach ($player as $value): ?>
                    <td><?= htmlspecialchars($value) ?></td>
                <?php endforeach; ?>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
