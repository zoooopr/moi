<?php
require_once "db_connect.php";
include_once "menu.php";

// Thêm mới NPC Template
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add"])) {
    $stmt = $conn->prepare("INSERT INTO npc_template (NAME, head, body, leg, avatar) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$_POST['name'], $_POST['head'], $_POST['body'], $_POST['leg'], $_POST['avatar']]);
}

// Cập nhật NPC Template
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update"])) {
    $stmt = $conn->prepare("UPDATE npc_template SET NAME=?, head=?, body=?, leg=?, avatar=? WHERE id=?");
    $stmt->execute([$_POST['name'], $_POST['head'], $_POST['body'], $_POST['leg'], $_POST['avatar'], $_POST['id']]);
}

// Xóa NPC Template
if (isset($_GET['delete'])) {
    $stmt = $conn->prepare("DELETE FROM npc_template WHERE id=?");
    $stmt->execute([$_GET['delete']]);
    header("Location: npc_template.php");
    exit();
}

// Tìm kiếm NPC Template
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($search) {
    $stmt = $conn->prepare("SELECT * FROM npc_template WHERE id LIKE ? OR NAME LIKE ? ORDER BY id DESC");
    $stmt->execute(["%$search%", "%$search%"]);
    $npcs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $npcs = $conn->query("SELECT * FROM npc_template ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý NPC Template</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Quản lý NPC Template</h2>

    <!-- Form tìm kiếm -->
    <form method="GET">
        <label>Tìm kiếm: <input type="text" name="search" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>"></label>
        <button type="submit">Tìm</button>
    </form>

    <!-- Form thêm mới -->
    <form method="POST">
        <input type="hidden" name="id" id="npc_id">
        <label>Tên NPC: <input type="text" name="name" id="name" required></label>
        <label>Head: <input type="number" name="head" id="head" required></label>
        <label>Body: <input type="number" name="body" id="body" required></label>
        <label>Leg: <input type="number" name="leg" id="leg" required></label>
        <label>Avatar: <input type="number" name="avatar" id="avatar" value="0"></label>
        <button type="submit" name="add">Thêm NPC</button>
        <button type="submit" name="update" style="display: none;" id="updateBtn">Cập nhật</button>
    </form>

    <h3>Danh sách NPC Templates</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Tên NPC</th>
            <th>Head</th>
            <th>Body</th>
            <th>Leg</th>
            <th>Avatar</th>
            <th>Hành động</th>
        </tr>
        <?php foreach ($npcs as $npc): ?>
            <tr>
                <td><?= $npc['id'] ?></td>
                <td><?= $npc['NAME'] ?></td>
                <td><?= $npc['head'] ?></td>
                <td><?= $npc['body'] ?></td>
                <td><?= $npc['leg'] ?></td>
                <td><?= $npc['avatar'] ?></td>
                <td>
                    <button onclick="editNpc(<?= $npc['id'] ?>, '<?= htmlspecialchars($npc['NAME']) ?>', <?= $npc['head'] ?>, <?= $npc['body'] ?>, <?= $npc['leg'] ?>, <?= $npc['avatar'] ?>)">Sửa</button>
                    <a href="?delete=<?= $npc['id'] ?>" onclick="return confirm('Xác nhận xóa?');">Xóa</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <script>
        function editNpc(id, name, head, body, leg, avatar) {
            document.getElementById('npc_id').value = id;
            document.getElementById('name').value = name;
            document.getElementById('head').value = head;
            document.getElementById('body').value = body;
            document.getElementById('leg').value = leg;
            document.getElementById('avatar').value = avatar;

            document.querySelector('[name="add"]').style.display = 'none';
            document.getElementById('updateBtn').style.display = 'inline-block';
        }
    </script>

</body>
</html>
