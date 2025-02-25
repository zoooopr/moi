<?php
require_once "db_connect.php";
include "menu.php";

// ✅ Xử lý thêm, sửa, xóa
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["add"])) { // Thêm mới
        $item_id = $_POST["item_id"];
        $option_id = $_POST["option_id"];
        $param = $_POST["param"];
        $sql = "INSERT INTO item_options (item_id, option_id, param) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$item_id, $option_id, $param]);
        echo "🟢 Đã thêm thành công!";
    } elseif (isset($_POST["update"])) { // Sửa
        $id = $_POST["id"];
        $item_id = $_POST["item_id"];
        $option_id = $_POST["option_id"];
        $param = $_POST["param"];
        $sql = "UPDATE item_options SET item_id=?, option_id=?, param=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$item_id, $option_id, $param, $id]);
        echo "🟡 Đã cập nhật thành công!";
    } elseif (isset($_POST["delete"])) { // Xóa
        $id = $_POST["id"];
        $sql = "DELETE FROM item_options WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);
        echo "🔴 Đã xóa thành công!";
    }
}

// ✅ Xử lý tìm kiếm
$search = isset($_GET["search"]) ? trim($_GET["search"]) : "";
$sql = "SELECT * FROM item_options";

$params = [];
if ($search !== "") {
    $sql .= " WHERE item_id LIKE ? OR option_id LIKE ? OR param LIKE ?";
    $params = ["%$search%", "%$search%", "%$search%"];
}

// ✅ Thêm ORDER BY để sắp xếp theo ID tăng dần
$sql .= " ORDER BY id DESC";

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$item_options = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$item_options = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Item Options</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        form {
            margin-bottom: 20px;
        }

        button {
            padding: 5px 10px;
            margin: 2px;
            cursor: pointer;
        }

        .search-box {
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

    <h2>Quản lý Item Options</h2>

    <!-- Form Tìm Kiếm -->
    <form method="GET" class="search-box">
        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Tìm theo Item ID, Option ID hoặc Param">
        <button type="submit">🔍 Tìm kiếm</button>
        <a href="optionitem.php"><button type="button">🔄 Reset</button></a>
    </form>

    <!-- Form Thêm Mới -->
    <h3>Thêm Item Option</h3>
    <form method="POST">
        <label>Item ID:</label> <input type="number" name="item_id" required>
        <label>Option ID:</label> <input type="number" name="option_id" required>
        <label>Param:</label> <input type="number" name="param" required>
        <button type="submit" name="add">Thêm</button>
    </form>

    <!-- Hiển thị danh sách -->
    <h3>Danh sách Item Options</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Item ID</th>
            <th>Option ID</th>
            <th>Param</th>
            <th>Hành động</th>
        </tr>
        <?php if (count($item_options) > 0): ?>
            <?php foreach ($item_options as $row): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['item_id'] ?></td>
                    <td><?= $row['option_id'] ?></td>
                    <td><?= $row['param'] ?></td>
                    <td>
                        <!-- Form Sửa -->
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <input type="number" name="item_id" value="<?= $row['item_id'] ?>" required>
                            <input type="number" name="option_id" value="<?= $row['option_id'] ?>" required>
                            <input type="number" name="param" value="<?= $row['param'] ?>" required>
                            <button type="submit" name="update">Sửa</button>
                        </form>

                        <!-- Form Xóa -->
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <button type="submit" name="delete" onclick="return confirm('Xác nhận xóa?');">Xóa</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" style="text-align:center;">🚫 Không tìm thấy kết quả!</td>
            </tr>
        <?php endif; ?>
    </table>

</body>

</html>
