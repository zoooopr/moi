<?php
require_once "db_connect.php"; // Kết nối database
include 'menu.php'; 
// Xử lý thêm tài khoản
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
    // Kiểm tra username đã tồn tại chưa
    $checkSql = "SELECT COUNT(*) FROM account WHERE username = :username";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->execute(['username' => $_POST['username']]);
    $exists = $checkStmt->fetchColumn();

    if ($exists) {
        echo "<script>alert('Lỗi: Username đã tồn tại!');</script>";
    } else {
        $sql = "INSERT INTO account (username, password, email, ban, is_admin, cash, vang) 
                VALUES (:username, :password, :email, :ban, :is_admin, :cash, :vang)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'username' => $_POST['username'],
            'password' => $_POST['password'], // Bỏ hash, password lưu dạng text
            'email' => $_POST['email'],
            'ban' => $_POST['ban'],
            'is_admin' => $_POST['is_admin'],
            'cash' => $_POST['cash'],
            'vang' => $_POST['vang']
        ]);
    }
}

// Xử lý cập nhật tài khoản
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $sql = "UPDATE account 
            SET username = :username, password = :password, email = :email, ban = :ban, is_admin = :is_admin, cash = :cash, vang = :vang
            WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        'id' => $_POST['id'],
        'username' => $_POST['username'],
        'password' => $_POST['password'], // Hiển thị password bình thường
        'email' => $_POST['email'],
        'ban' => $_POST['ban'],
        'is_admin' => $_POST['is_admin'],
        'cash' => $_POST['cash'],
        'vang' => $_POST['vang']
    ]);
}

// Xử lý xóa tài khoản
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $sql = "DELETE FROM account WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['id' => $_POST['id']]);
}

// Xử lý tìm kiếm tài khoản
$search = isset($_GET['search']) ? $_GET['search'] : '';
$params = [];
$sql = "SELECT * FROM account";

if (!empty($search)) {
    $sql .= " WHERE id LIKE :search OR username LIKE :search OR email LIKE :search";
    $params['search'] = "%$search%";
}

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Tài Khoản</title>
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        input, button { margin: 5px; padding: 5px; }
    </style>
</head>
<body>
    <h2>Quản lý Tài Khoản</h2>

    <!-- Form tìm kiếm -->
    <form method="GET">
        <input type="text" name="search" placeholder="Nhập từ khóa..." value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Tìm kiếm</button>
    </form>

    <!-- Form thêm mới -->
    <h3>Thêm tài khoản</h3>
    <form method="POST">
        <input type="hidden" name="add" value="1">
        <input type="text" name="username" placeholder="Username" required>
        <input type="text" name="password" placeholder="Mật khẩu" required>
        <input type="email" name="email" placeholder="Email">
        <input type="number" name="ban" placeholder="Ban (0/1)">
        <input type="number" name="is_admin" placeholder="Admin (0/1)">
        <input type="number" name="cash" placeholder="Cash">
        <input type="number" name="vang" placeholder="Vàng">
        <button type="submit">Thêm</button>
    </form>

    <h3>Danh sách tài khoản</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Password</th>
            <th>Email</th>
            <th>Ban</th>
            <th>Admin</th>
            <th>Cash</th>
            <th>Vàng</th>
            <th>Hành động</th>
        </tr>
        <?php foreach ($accounts as $row): ?>
            <tr>
                <form method="POST">
                    <td><?= $row['id']; ?></td>
                    <td><input type="text" name="username" value="<?= $row['username']; ?>"></td>
                    <td><input type="text" name="password" value="<?= $row['password']; ?>"></td>
                    <td><input type="email" name="email" value="<?= $row['email']; ?>"></td>
                    <td><input type="number" name="ban" value="<?= $row['ban']; ?>"></td>
                    <td><input type="number" name="is_admin" value="<?= $row['is_admin']; ?>"></td>
                    <td><input type="number" name="cash" value="<?= $row['cash']; ?>"></td>
                    <td><input type="number" name="vang" value="<?= $row['vang']; ?>"></td>
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
