<?php
$servername = "localhost";
$username = "root"; // Thay bằng user của bạn
$password = ""; // Thay bằng mật khẩu của bạn
$dbname = "nrotobei"; // Thay bằng tên database
$tableName = "item_option_template"; // Thay bằng tên bảng cần hiển thị

include 'menu.php'; 

$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy dữ liệu tìm kiếm
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Lấy danh sách các cột của bảng
$columns_query = $conn->query("SHOW COLUMNS FROM `$tableName`");
$columns = [];
while ($col = $columns_query->fetch_assoc()) {
    $columns[] = "`" . $col['Field'] . "`";
}

// Tạo câu SQL lấy dữ liệu
$sql = "SELECT * FROM `$tableName`";
if (!empty($search)) {
    $conditions = [];
    foreach ($columns as $column) {
        $conditions[] = "$column LIKE '%$search%'";
    }
    $sql .= " WHERE " . implode(" OR ", $conditions);
}
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách dữ liệu</title>
    <style>
        body { font-family: Arial, sans-serif; }
        input, button { margin: 10px 0; padding: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
    </style>
</head>
<body>

    <h2>Danh sách dữ liệu trong bảng: <b><?php echo $tableName; ?></b></h2>
    <form method="GET">
        <input type="text" name="search" placeholder="Nhập từ khóa..." value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit">Tìm kiếm</button>
    </form>

    <table>
        <thead>
            <tr>
                <?php foreach ($columns as $col): ?>
                    <th><?php echo htmlspecialchars(str_replace('`', '', $col)); ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <?php foreach ($columns as $col): ?>
                            <td><?php echo htmlspecialchars($row[str_replace('`', '', $col)]); ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="<?php echo count($columns); ?>">Không tìm thấy dữ liệu.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>

<?php $conn->close(); ?>
