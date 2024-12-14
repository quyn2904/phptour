<?php
session_start();
require_once "db.inc.php";

// Kiểm tra nếu dữ liệu được gửi qua POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Kiểm tra nếu có dữ liệu title, content và blogId (optional)
    if (isset($_POST['title']) && isset($_POST['content'])) {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $accountId = $_SESSION['user_id'] ?? 0;
        $blogId = $_POST['blogId'] ?? null; // Lấy blogId nếu có

        try {
            if ($blogId) {
                // Nếu có blogId, thực hiện cập nhật
                $stmt = $pdo->prepare("UPDATE blog SET title = :title, content = :content, timestamp = NOW() WHERE id = :blogId AND account_id = :account_id");
                $stmt->bindParam(':blogId', $blogId, PDO::PARAM_INT);
            } else {
                // Nếu không có blogId, tạo mới
                $stmt = $pdo->prepare("INSERT INTO blog (title, content, timestamp, account_id) VALUES (:title, :content, NOW(), :account_id)");
            }

            // Bind parameters
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':content', $content);
            $stmt->bindParam(':account_id', $accountId);
            $stmt->execute();

            // Trả về phản hồi thành công
            if ($blogId) {
                echo "Blog updated successfully!";
            } else {
                echo "Blog created successfully!";
            }

        } catch (PDOException $e) {
            // Trả về thông báo lỗi nếu gặp sự cố
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Title and content are required!";
    }
} else {
    echo "Invalid request method!";
}
?>