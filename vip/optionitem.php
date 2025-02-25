<?php
require_once "db_connect.php";
include 'menu.php'; 

// Xử lý thêm mới
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
    $sql = "INSERT INTO item_shop_option (item_shop_id, option_id, param) VALUES (:item_shop_id, :option_id, :param)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        'item_shop_id' => $_POST['item_shop_id'],
        'option_id' => $_POST['option_id'],
        'param' => $_POST['param']
    ]);
}

// Xử lý cập nhật
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $sql = "UPDATE item_shop_option SET item_shop_id = :item_shop_id, option_id = :option_id, param = :param WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        'id' => $_POST['id'],
        'item_shop_id' => $_POST['item_shop_id'],
        'option_id' => $_POST['option_id'],
        'param' => $_POST['param']
    ]);
}

// Xử lý xóa
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $sql = "DELETE FROM item_shop_option WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['id' => $_POST['id']]);
}

// Xử lý tìm kiếm và filter
$search = isset($_GET['search']) ? $_GET['search'] : '';
$filter_by = isset($_GET['filter_by']) ? $_GET['filter_by'] : 'id'; // Điều kiện lọc
$params = [];
$sql = "SELECT * FROM item_shop_option";

// Nếu có từ khóa tìm kiếm, áp dụng điều kiện lọc
if (!empty($search)) {
    $sql .= " WHERE $filter_by LIKE :search";  // Đảm bảo có dấu cách ở đây
    $params['search'] = "%$search%";
}

$sql .= " ORDER BY id DESC";  // Sắp xếp theo ID

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Item Shop Option</title>
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        input, button { margin: 5px; padding: 5px; }
    </style>
</head>
<body>
    <h2>Quản lý Item Shop Option</h2>

    <!-- Form tìm kiếm và filter -->
    <form method="GET">
        <input type="text" name="search" placeholder="Nhập từ khóa..." value="<?= htmlspecialchars($search) ?>">
        <label for="filter_by">Lọc theo:</label>
        <select name="filter_by" id="filter_by">
            <option value="id" <?= isset($_GET['filter_by']) && $_GET['filter_by'] == 'id' ? 'selected' : '' ?>>ID</option>
            <option value="item_shop_id" <?= isset($_GET['filter_by']) && $_GET['filter_by'] == 'item_shop_id' ? 'selected' : '' ?>>Item Shop ID</option>
            <option value="option_id" <?= isset($_GET['filter_by']) && $_GET['filter_by'] == 'option_id' ? 'selected' : '' ?>>Option ID</option>
            <option value="param" <?= isset($_GET['filter_by']) && $_GET['filter_by'] == 'param' ? 'selected' : '' ?>>Param</option>
        </select>
        <button type="submit">Tìm kiếm</button>
    </form>

    <!-- Form thêm mới -->
    <h3>Thêm Item Shop Option</h3>
    <form method="POST">
        <input type="hidden" name="add" value="1">
        <input type="number" name="item_shop_id" placeholder="Item Shop ID" required>
        <input type="number" name="option_id" placeholder="Option ID" required>
        <input type="number" name="param" placeholder="Param" required>
        <button type="submit">Thêm</button>
    </form>

    <h3>Danh sách Item Shop Option</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Item Shop ID</th>
            <th>Option ID</th>
            <th>Param</th>
            <th>Hành động</th>
        </tr>
        <?php foreach ($items as $row): ?>
            <tr>
                <form method="POST">
                    <td><?= $row['id']; ?></td>
                    <td><input type="number" name="item_shop_id" value="<?= $row['item_shop_id']; ?>"></td>
                    <td><input type="number" name="option_id" value="<?= $row['option_id']; ?>"></td>
                    <td><input type="number" name="param" value="<?= $row['param']; ?>"></td>
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
