<?php
require_once "db_connect.php";
include 'menu.php'; 
// Xử lý thêm mới
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
    $sql = "INSERT INTO map_template (NAME, zones, max_player, data, type, planet_id, bg_type, tile_id, bg_id, waypoints, mobs, npcs, is_map_double) 
            VALUES (:NAME, :zones, :max_player, :data, :type, :planet_id, :bg_type, :tile_id, :bg_id, :waypoints, :mobs, :npcs, :is_map_double)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        'NAME' => $_POST['NAME'],
        'zones' => $_POST['zones'],
        'max_player' => $_POST['max_player'],
        'data' => $_POST['data'],
        'type' => $_POST['type'],
        'planet_id' => $_POST['planet_id'],
        'bg_type' => $_POST['bg_type'],
        'tile_id' => $_POST['tile_id'],
        'bg_id' => $_POST['bg_id'],
        'waypoints' => $_POST['waypoints'],
        'mobs' => $_POST['mobs'],
        'npcs' => $_POST['npcs'],
        'is_map_double' => $_POST['is_map_double']
    ]);
}

// Xử lý cập nhật
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $sql = "UPDATE map_template SET NAME = :NAME, zones = :zones, max_player = :max_player, data = :data, 
            type = :type, planet_id = :planet_id, bg_type = :bg_type, tile_id = :tile_id, bg_id = :bg_id, 
            waypoints = :waypoints, mobs = :mobs, npcs = :npcs, is_map_double = :is_map_double 
            WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        'id' => $_POST['id'],
        'NAME' => $_POST['NAME'],
        'zones' => $_POST['zones'],
        'max_player' => $_POST['max_player'],
        'data' => $_POST['data'],
        'type' => $_POST['type'],
        'planet_id' => $_POST['planet_id'],
        'bg_type' => $_POST['bg_type'],
        'tile_id' => $_POST['tile_id'],
        'bg_id' => $_POST['bg_id'],
        'waypoints' => $_POST['waypoints'],
        'mobs' => $_POST['mobs'],
        'npcs' => $_POST['npcs'],
        'is_map_double' => $_POST['is_map_double']
    ]);
}

// Xử lý xóa
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $sql = "DELETE FROM map_template WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['id' => $_POST['id']]);
}

// Xử lý tìm kiếm
$search = isset($_GET['search']) ? $_GET['search'] : '';
$params = [];
$sql = "SELECT * FROM map_template";

if (!empty($search)) {
    $sql .= " WHERE NAME LIKE :search OR zones LIKE :search OR max_player LIKE :search OR data LIKE :search";
    $params['search'] = "%$search%";
}

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$maps = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản lý Map Template</title>
    <style>
        body {
            width: 100%;
        }
        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        input,
        button {
            margin: 5px;
            padding: 5px;
        }
        form {
            display: flex;
            flex-direction: column;
            width: 50%; 
        }
        textarea {
            width: 50%;
            height: 50px;
        }

        /* Chỉnh kích thước cột */
        th:nth-child(3),
        td:nth-child(3),
        /* Zones */
        th:nth-child(4),
        td:nth-child(4),
        /* Max Player */
        th:nth-child(6),
        td:nth-child(6),
        /* Type */
        th:nth-child(7),
        td:nth-child(7),
        /* Planet ID */
        th:nth-child(8),
        td:nth-child(8),
        /* BG Type */
        th:nth-child(9),
        td:nth-child(9),
        /* Tile ID */
        th:nth-child(10),
        td:nth-child(10),
        /* BG ID */
        th:nth-child(14),
        td:nth-child(14)

        /* Map Double */
            {
            width: 10%;
            text-align: center;
        }

        /* Các ô khác sẽ rộng hơn */
        th:nth-child(2),
        td:nth-child(2),
        /* NAME */
        th:nth-child(5),
        td:nth-child(5),
        /* Data */
        th:nth-child(11),
        td:nth-child(11),
        /* Waypoints */
        th:nth-child(12),
        td:nth-child(12),
        /* Mobs */
        th:nth-child(13),
        td:nth-child(13)

        /* NPCs */
            {
            width: 200px;
        }
    </style>
</head>

<body>
    <h2>Quản lý Map Template</h2>

    <!-- Form tìm kiếm -->
    <form method="GET">
        <input type="text" name="search" placeholder="Nhập từ khóa..." value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Tìm kiếm</button>
    </form>

    <!-- Form thêm mới -->
    <h3>Thêm Map Template</h3>
    <form method="POST">
        <input type="hidden" name="add" value="1">
        <input type="text" name="NAME" placeholder="Tên bản đồ" required>
        <input type="number" name="zones" placeholder="Zones" required>
        <input type="number" name="max_player" placeholder="Max Player" required>
        <textarea name="data" placeholder="Data JSON"></textarea>
        <input type="number" name="type" placeholder="Type" required>
        <input type="number" name="planet_id" placeholder="Planet ID" required>
        <input type="number" name="bg_type" placeholder="Background Type" required>
        <input type="number" name="tile_id" placeholder="Tile ID" required>
        <input type="number" name="bg_id" placeholder="Background ID" required>
        <textarea name="waypoints" placeholder="Waypoints"></textarea>
        <textarea name="mobs" placeholder="Mobs"></textarea>
        <textarea name="npcs" placeholder="NPCs"></textarea>
        <input type="number" name="is_map_double" placeholder="Map Double (0/1)" required>
        <button type="submit">Thêm</button>
    </form>

    <h3>Danh sách Map Template</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Tên</th>
            <th>Zones</th>
            <th>Max Player</th>
            <th>Data</th>
            <th>Type</th>
            <th>Planet ID</th>
            <th>BG Type</th>
            <th>Tile ID</th>
            <th>BG ID</th>
            <th>Waypoints</th>
            <th>Mobs</th>
            <th>NPCs</th>
            <th>Map Double</th>
            <th>Hành động</th>
        </tr>
        <?php foreach ($maps as $row): ?>
            <tr>
                <form method="POST">
                    <td><?= $row['id']; ?></td>
                    <td><input type="text" name="NAME" value="<?= $row['NAME']; ?>"></td>
                    <td><input type="number" name="zones" value="<?= $row['zones']; ?>"></td>
                    <td><input type="number" name="max_player" value="<?= $row['max_player']; ?>"></td>
                    <td><textarea name="data"><?= $row['data']; ?></textarea></td>
                    <td><input type="number" name="type" value="<?= $row['type']; ?>"></td>
                    <td><input type="number" name="planet_id" value="<?= $row['planet_id']; ?>"></td>
                    <td><input type="number" name="bg_type" value="<?= $row['bg_type']; ?>"></td>
                    <td><input type="number" name="tile_id" value="<?= $row['tile_id']; ?>"></td>
                    <td><input type="number" name="bg_id" value="<?= $row['bg_id']; ?>"></td>
                    <td><textarea name="waypoints"><?= $row['waypoints']; ?></textarea></td>
                    <td><textarea name="mobs"><?= $row['mobs']; ?></textarea></td>
                    <td><textarea name="npcs"><?= $row['npcs']; ?></textarea></td>
                    <td><input type="number" name="is_map_double" value="<?= $row['is_map_double']; ?>"></td>
                    <td>
                        <input type="hidden" name="id" value="<?= $row['id']; ?>">
                        <button type="submit" name="update">Lưu</button>
                        <button type="submit" name="delete" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</button>
                    </td>
                </form>
            </tr>
        <?php endforeach; ?>
    </table>
</body>

</html>

<?php $conn = null; ?>