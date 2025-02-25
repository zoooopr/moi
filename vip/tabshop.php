<?php
require_once "db_connect.php";
include_once "menu.php";

// Thêm mới Tab Shop
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add"])) {
    $stmt = $conn->prepare("INSERT INTO tab_shop (shop_id, NAME) VALUES (?, ?)");
    $stmt->execute([$_POST['shop_id'], $_POST['name']]);
}

// Cập nhật Tab Shop
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update"])) {
    $stmt = $conn->prepare("UPDATE tab_shop SET shop_id=?, NAME=? WHERE id=?");
    $stmt->execute([$_POST['shop_id'], $_POST['name'], $_POST['id']]);
}

// Xóa Tab Shop
if (isset($_GET['delete'])) {
    $stmt = $conn->prepare("DELETE FROM tab_shop WHERE id=?");
    $stmt->execute([$_GET['delete']]);
    header("Location: tabshop.php");
    exit();
}

// Tìm kiếm Tab Shop
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter_by = isset($_GET['filter_by']) ? $_GET['filter_by'] : 'id';

if ($search) {
    $stmt = $conn->prepare("SELECT * FROM tab_shop WHERE $filter_by LIKE ? ORDER BY id DESC");
    $stmt->execute(["%$search%"]);
    $tab_shops = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $tab_shops = $conn->query("SELECT * FROM tab_shop ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Tab Shop</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Quản lý Tab Shop</h2>

    <!-- Form tìm kiếm và filter -->
    <form method="GET">
        <label>Tìm kiếm: <input type="text" name="search" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>"></label>
        <label for="filter_by">Lọc theo:</label>
        <select name="filter_by" id="filter_by">
            <option value="id" <?= isset($_GET['filter_by']) && $_GET['filter_by'] == 'id' ? 'selected' : '' ?>>ID</option>
            <option value="shop_id" <?= isset($_GET['filter_by']) && $_GET['filter_by'] == 'shop_id' ? 'selected' : '' ?>>Shop ID</option>
            <option value="NAME" <?= isset($_GET['filter_by']) && $_GET['filter_by'] == 'NAME' ? 'selected' : '' ?>>Tên Tab</option>
        </select>
        <button type="submit">Tìm</button>
    </form>

    <!-- Form thêm mới -->
    <form method="POST">
        <input type="hidden" name="id" id="tab_shop_id">
        <label>Shop ID: <input type="number" name="shop_id" id="shop_id" required></label>
        <label>Tên Tab: <input type="text" name="name" id="name" required></label>
        <button type="submit" name="add">Thêm Tab Shop</button>
        <button type="submit" name="update" style="display: none;" id="updateBtn">Cập nhật</button>
    </form>

    <h3>Danh sách Tab Shop</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Shop ID</th>
            <th>Tên Tab</th>
            <th>Hành động</th>
        </tr>
        <?php foreach ($tab_shops as $tab_shop): ?>
            <tr>
                <td><?= $tab_shop['id'] ?></td>
                <td><?= $tab_shop['shop_id'] ?></td>
                <td><?= $tab_shop['NAME'] ?></td>
                <td>
                    <button onclick="editTabShop(<?= $tab_shop['id'] ?>, <?= $tab_shop['shop_id'] ?>, '<?= htmlspecialchars($tab_shop['NAME']) ?>')">Sửa</button>
                    <a href="?delete=<?= $tab_shop['id'] ?>" onclick="return confirm('Xác nhận xóa?');">Xóa</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <script>
        function editTabShop(id, shop_id, name) {
            document.getElementById('tab_shop_id').value = id;
            document.getElementById('shop_id').value = shop_id;
            document.getElementById('name').value = name;

            document.querySelector('[name="add"]').style.display = 'none';
            document.getElementById('updateBtn').style.display = 'inline-block';
        }
    </script>

</body>
</html>
