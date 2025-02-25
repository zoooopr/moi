<?php
require_once "db_connect.php";

header('Content-Type: application/json');
ob_start(); // Bắt đầu bộ đệm đầu ra để ngăn lỗi output không mong muốn

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        // Xóa mọi output trước khi gửi JSON (phòng lỗi ký tự không mong muốn)
        ob_clean();

        // Lấy danh sách tất cả cột trong bảng item_template
        $stmt = $conn->query("SHOW COLUMNS FROM item_template");
        $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Kiểm tra xem ID có tồn tại không
        if (!isset($_POST['id']) || empty($_POST['id'])) {
            echo json_encode(["success" => false, "message" => "⚠️ Thiếu ID"], JSON_UNESCAPED_UNICODE);
            exit;
        }
        $id = intval($_POST['id']);

        // Xây dựng danh sách các cột hợp lệ cần cập nhật
        $updateFields = [];
        $updateValues = [];

        foreach ($columns as $column) {
            if ($column !== 'id' && isset($_POST[$column])) {
                $updateFields[] = "`$column` = :$column";
                $updateValues[":$column"] = $_POST[$column];
            }
        }

        // Kiểm tra nếu không có cột nào được cập nhật
        if (empty($updateFields)) {
            echo json_encode(["success" => false, "message" => "⚠️ Không có dữ liệu cần cập nhật"], JSON_UNESCAPED_UNICODE);
            exit;
        }

        // Thêm ID vào danh sách tham số
        $updateValues[":id"] = $id;

        // Tạo câu lệnh SQL động
        $sql = "UPDATE item_template SET " . implode(", ", $updateFields) . " WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute($updateValues);

        $response = ["success" => true, "message" => "✅ Cập nhật thành công!"];
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "❌ Lỗi khi cập nhật: " . $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
} else {
    echo json_encode(["success" => false, "message" => "⚠️ Phương thức yêu cầu không hợp lệ!"], JSON_UNESCAPED_UNICODE);
}

ob_end_flush(); // Đảm bảo output JSON được gửi đúng cách
?>
