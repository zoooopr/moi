<?php
require_once "db_connect.php";
include "menu.php";

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$column = isset($_GET['column']) ? trim($_GET['column']) : '';
$gender_filter = isset($_GET['gender_filter']) ? trim($_GET['gender_filter']) : '';

$validColumns = ['id', 'TYPE', 'gender', 'NAME', 'description', 'level', 'icon_id', 'part', 'is_up_to_up', 'power_require', 'gold', 'gem', 'head', 'body', 'leg'];
$sql = "SELECT id, TYPE, gender, NAME, description, level, icon_id, part, is_up_to_up, power_require, gold, gem, head, body, leg FROM item_template";
$params = [];
$conditions = [];

// Thêm điều kiện tìm kiếm nếu có giá trị nhập vào
if (!empty($search) && in_array($column, $validColumns)) {
    $conditions[] = "$column LIKE ?";
    $params[] = "%$search%";
}

// Lọc theo gender (chỉ hiển thị 0, 1, 2)
if ($gender_filter !== "" && in_array($gender_filter, ['0', '1', '2'])) {
    $conditions[] = "gender = ?";
    $params[] = $gender_filter;
}

// Gộp các điều kiện thành chuỗi WHERE
if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách Item</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .edit-input { width: 100px; }
    </style>
    <script>
        function updateItem(id) {
            let data = new FormData();
            data.append('id', id);
            
            ['TYPE', 'gender', 'NAME', 'description', 'level', 'icon_id', 'part', 'is_up_to_up', 'power_require', 'gold', 'gem', 'head', 'body', 'leg'].forEach(field => {
                let inputElement = document.getElementById(`${field}_${id}`);
                if (inputElement) {
                    data.append(field, inputElement.value.trim());
                }
            });

            fetch("update_item.php", {
                method: "POST",
                body: data
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.success) {
                    document.getElementById(`NAME_${id}`).style.backgroundColor = "#d4edda";
                }
            })
            .catch(error => console.error("Lỗi:", error));
        }
    </script>
</head>
<body>
    <h2>Danh sách Item</h2>
    <form method="GET">
        <input type="text" name="search" placeholder="Nhập từ khóa..." value="<?= htmlspecialchars($search) ?>">
        <select name="column">
            <option value="">-- Chọn cột --</option>
            <?php foreach ($validColumns as $col): ?>
                <option value="<?= $col ?>" <?= ($column == $col) ? 'selected' : '' ?>><?= ucfirst($col) ?></option>
            <?php endforeach; ?>
        </select>
        
        <!-- Dropdown lọc theo giới tính -->
        <select name="gender_filter">
            <option value="">-- Lọc theo giới tính --</option>
            <option value="0" <?= ($gender_filter === "0") ? 'selected' : '' ?>>0</option>
            <option value="1" <?= ($gender_filter === "1") ? 'selected' : '' ?>>1</option>
            <option value="2" <?= ($gender_filter === "2") ? 'selected' : '' ?>>2</option>
        </select>

        <button type="submit">Tìm kiếm</button>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Loại</th>
            <th>Giới tính</th>
            <th>Tên</th>
            <th>Mô tả</th>
            <th>Cấp độ</th>
            <th>Icon ID</th>
            <th>Phần</th>
            <th>Up To Up</th>
            <th>Yêu cầu sức mạnh</th>
            <th>Vàng</th>
            <th>Ngọc</th>
            <th>Đầu</th>
            <th>Thân</th>
            <th>Chân</th>
            <th>Cập nhật</th>
        </tr>
        <?php if ($items): foreach ($items as $row): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <?php foreach (['TYPE', 'gender', 'NAME', 'description', 'level', 'icon_id', 'part', 'is_up_to_up', 'power_require', 'gold', 'gem', 'head', 'body', 'leg'] as $field): ?>
                        <td><input type='text' id='<?= $field ?>_<?= $row['id'] ?>' value='<?= htmlspecialchars($row[$field] ?? '') ?>'></td>
                    <?php endforeach; ?>
                    <td><button onclick='updateItem(<?= $row['id'] ?>)'>Lưu</button></td>
                </tr>
            <?php endforeach;
        else: ?>
            <tr>
                <td colspan='16'>Không có dữ liệu</td>
            </tr>
        <?php endif; ?>
    </table>
</body>
</html>
