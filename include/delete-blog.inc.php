<?php
session_start();
require_once "db.inc.php";

// Kiểm tra xem tham số blogId có tồn tại không
if (isset($_GET['blogId'])) {
    $blogId = $_GET['blogId'];

    try {
        // Truy vấn cập nhật trạng thái xóa blog
        $stmt = $pdo->prepare("UPDATE blog SET isDeleted = TRUE WHERE id = :blogId");
        $stmt->bindParam(':blogId', $blogId, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $_SESSION['message'] = "Blog has been deleted successfully.";
        } else {
            $_SESSION['message'] = "Blog not found or already deleted.";
        }
    } catch (PDOException $e) {
        $_SESSION['message'] = "Error deleting blog: " . $e->getMessage();
    }
} else {
    $_SESSION['message'] = "Invalid blog ID.";
}

// Chuyển hướng về trang danh sách blog
header("Location: ../blog-management.php");
exit;
