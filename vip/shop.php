<?php
require_once "db_connect.php";
include_once "menu.php";

// Thêm mới Shop
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add"])) {
    $stmt = $conn->prepare("INSERT INTO shop (npc_id, tag_name, type_shop) VALUES (?, ?, ?)");
    $stmt->execute([$_POST['npc_id'], $_POST['tag_name'], $_POST['type_shop']]);
}

// Cập nhật Shop
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update"])) {
    $stmt = $conn->prepare("UPDATE shop SET npc_id=?, tag_name=?, type_shop=? WHERE id=?");
    $stmt->execute([$_POST['npc_id'], $_POST['tag_name'], $_POST['type_shop'], $_POST['id']]);
}

// Xóa Shop
if (isset($_GET['delete'])) {
    $stmt = $conn->prepare("DELETE FROM shop WHERE id=?");
    $stmt->execute([$_GET['delete']]);
    header("Location: shop.php");
    exit();
}

// Tìm kiếm Shop
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter_by = isset($_GET['filter_by']) ? $_GET['filter_by'] : 'id';

if ($search) {
    $stmt = $conn->prepare("SELECT * FROM shop WHERE $filter_by LIKE ? ORDER BY id DESC");
    $stmt->execute(["%$search%"]);
    $shops = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $shops = $conn->query("SELECT * FROM shop ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Shop</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Quản lý Shop</h2>

    <!-- Form tìm kiếm và filter -->
    <form method="GET">
        <label>Tìm kiếm: <input type="text" name="search" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>"></label>
        <label for="filter_by">Lọc theo:</label>
        <select name="filter_by" id="filter_by">
            <option value="id" <?= isset($_GET['filter_by']) && $_GET['filter_by'] == 'id' ? 'selected' : '' ?>>ID</option>
            <option value="npc_id" <?= isset($_GET['filter_by']) && $_GET['filter_by'] == 'npc_id' ? 'selected' : '' ?>>NPC ID</option>
            <option value="tag_name" <?= isset($_GET['filter_by']) && $_GET['filter_by'] == 'tag_name' ? 'selected' : '' ?>>Tên Tag</option>
            <option value="type_shop" <?= isset($_GET['filter_by']) && $_GET['filter_by'] == 'type_shop' ? 'selected' : '' ?>>Type Shop</option>
        </select>
        <button type="submit">Tìm</button>
    </form>

    <!-- Form thêm mới -->
    <form method="POST">
        <input type="hidden" name="id" id="shop_id">
        <label>NPC ID: <input type="number" name="npc_id" id="npc_id" required></label>
        <label>Tag Name: <input type="text" name="tag_name" id="tag_name" required></label>
        <label>Type Shop: <input type="number" name="type_shop" id="type_shop" required></label>
        <button type="submit" name="add">Thêm Shop</button>
        <button type="submit" name="update" style="display: none;" id="updateBtn">Cập nhật</button>
    </form>

    <h3>Danh sách Shop</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>NPC ID</th>
            <th>Tag Name</th>
            <th>Type Shop</th>
            <th>Hành động</th>
        </tr>
        <?php foreach ($shops as $shop): ?>
            <tr>
                <td><?= $shop['id'] ?></td>
                <td><?= $shop['npc_id'] ?></td>
                <td><?= $shop['tag_name'] ?></td>
                <td><?= $shop['type_shop'] ?></td>
                <td>
                    <button onclick="editShop(<?= $shop['id'] ?>, <?= $shop['npc_id'] ?>, '<?= htmlspecialchars($shop['tag_name']) ?>', <?= $shop['type_shop'] ?>)">Sửa</button>
                    <a href="?delete=<?= $shop['id'] ?>" onclick="return confirm('Xác nhận xóa?');">Xóa</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <script>
        function editShop(id, npc_id, tag_name, type_shop) {
            document.getElementById('shop_id').value = id;
            document.getElementById('npc_id').value = npc_id;
            document.getElementById('tag_name').value = tag_name;
            document.getElementById('type_shop').value = type_shop;

            document.querySelector('[name="add"]').style.display = 'none';
            document.getElementById('updateBtn').style.display = 'inline-block';
        }
    </script>

</body>
</html>
