<?php
require_once "db_connect.php";
include_once "menu.php";

// Xử lý thêm mới item
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add"])) {
    $stmt = $conn->prepare("INSERT INTO item_shop (tab_id, temp_id, is_new, is_sell, type_sell, cost, icon_spec) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$_POST['tab_id'], $_POST['temp_id'], $_POST['is_new'], $_POST['is_sell'], $_POST['type_sell'], $_POST['cost'], $_POST['icon_spec']]);
}

// Xử lý cập nhật item
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update"])) {
    $stmt = $conn->prepare("UPDATE item_shop SET tab_id=?, temp_id=?, is_new=?, is_sell=?, type_sell=?, cost=?, icon_spec=? WHERE id=?");
    $stmt->execute([$_POST['tab_id'], $_POST['temp_id'], $_POST['is_new'], $_POST['is_sell'], $_POST['type_sell'], $_POST['cost'], $_POST['icon_spec'], $_POST['id']]);
}

// Xử lý xóa item
if (isset($_GET['delete'])) {
    $stmt = $conn->prepare("DELETE FROM item_shop WHERE id=?");
    $stmt->execute([$_GET['delete']]);
    header("Location: itemshop.php");
    exit();
}

// Kiểm tra nếu có từ khóa tìm kiếm và bộ lọc
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter_by = isset($_GET['filter_by']) ? $_GET['filter_by'] : 'id';

if ($search) {
    $stmt = $conn->prepare("SELECT * FROM item_shop 
                            WHERE $filter_by LIKE ? 
                            ORDER BY id DESC");
    $stmt->execute(["%$search%"]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $items = $conn->query("SELECT * FROM item_shop ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Item Shop</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<h2>Quản lý Item Shop</h2>

<!-- Form tìm kiếm và filter -->
<form method="GET">
    <label>Tìm kiếm: <input type="text" name="search" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>"></label>
    <label for="filter_by">Lọc theo:</label>
    <select name="filter_by" id="filter_by">
        <option value="id" <?= isset($_GET['filter_by']) && $_GET['filter_by'] == 'id' ? 'selected' : '' ?>>ID</option>
        <option value="tab_id" <?= isset($_GET['filter_by']) && $_GET['filter_by'] == 'tab_id' ? 'selected' : '' ?>>Tab ID</option>
        <option value="temp_id" <?= isset($_GET['filter_by']) && $_GET['filter_by'] == 'temp_id' ? 'selected' : '' ?>>Temp ID</option>
        <option value="cost" <?= isset($_GET['filter_by']) && $_GET['filter_by'] == 'cost' ? 'selected' : '' ?>>Cost</option>
    </select>
    <button type="submit">Tìm</button>
</form>

<!-- Form thêm mới item -->
<form method="POST">
    <input type="hidden" name="id" id="item_id">
    <label>Tab ID: <input type="number" name="tab_id" id="tab_id" required></label>
    <label>Temp ID: <input type="number" name="temp_id" id="temp_id" required></label>
    <label>Is New: <input type="number" name="is_new" id="is_new" value="1"></label>
    <label>Is Sell: <input type="number" name="is_sell" id="is_sell" value="1"></label>
    <label>Type Sell: <input type="number" name="type_sell" id="type_sell" value="1"></label>
    <label>Cost: <input type="number" name="cost" id="cost" value="0"></label>
    <label>Icon Spec: <input type="number" name="icon_spec" id="icon_spec" value="0"></label>
    <button type="submit" name="add">Thêm Item</button>
    <button type="submit" name="update" style="display: none;" id="updateBtn">Cập nhật</button>
</form>

<h3>Danh sách Item Shop</h3>
<table>
    <tr>
        <th>ID</th>
        <th>Tab ID</th>
        <th>Temp ID</th>
        <th>Is New</th>
        <th>Is Sell</th>
        <th>Type Sell</th>
        <th>Cost</th>
        <th>Icon Spec</th>
        <th>Hành động</th>
    </tr>
    <?php foreach ($items as $item): ?>
        <tr>
            <td><?= $item['id'] ?></td>
            <td><?= $item['tab_id'] ?></td>
            <td><?= $item['temp_id'] ?></td>
            <td><?= $item['is_new'] ?></td>
            <td><?= $item['is_sell'] ?></td>
            <td><?= $item['type_sell'] ?></td>
            <td><?= $item['cost'] ?></td>
            <td><?= $item['icon_spec'] ?></td>
            <td>
                <button onclick="editItem(<?= $item['id'] ?>, <?= $item['tab_id'] ?>, <?= $item['temp_id'] ?>, <?= $item['is_new'] ?>, <?= $item['is_sell'] ?>, <?= $item['type_sell'] ?>, <?= $item['cost'] ?>, <?= $item['icon_spec'] ?>)">Sửa</button>
                <a href="?delete=<?= $item['id'] ?>" onclick="return confirm('Xác nhận xóa?');">Xóa</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<script>
    function editItem(id, tab_id, temp_id, is_new, is_sell, type_sell, cost, icon_spec) {
        document.getElementById('item_id').value = id;
        document.getElementById('tab_id').value = tab_id;
        document.getElementById('temp_id').value = temp_id;
        document.getElementById('is_new').value = is_new;
        document.getElementById('is_sell').value = is_sell;
        document.getElementById('type_sell').value = type_sell;
        document.getElementById('cost').value = cost;
        document.getElementById('icon_spec').value = icon_spec;

        document.querySelector('[name="add"]').style.display = 'none';
        document.getElementById('updateBtn').style.display = 'inline-block';
    }
</script>

</body>
</html>
<?php $conn = null; ?>
