<?php
session_start();
require_once "db.inc.php";

// Kiểm tra xem tham số productId có tồn tại không
if (isset($_GET['productId'])) {
    $productId = $_GET['productId'];

    try {
        // Truy vấn cập nhật trạng thái xóa sản phẩm
        $stmt = $pdo->prepare("UPDATE product SET isDeleted = true WHERE id = :productId");
        $stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $_SESSION['message'] = "Product has been deleted successfully.";
        } else {
            $_SESSION['message'] = "Product not found or already deleted.";
        }
    } catch (PDOException $e) {
        $_SESSION['message'] = "Error deleting product: " . $e->getMessage();
    }
} else {
    $_SESSION['message'] = "Invalid product ID.";
}

// Chuyển hướng về trang quản lý sản phẩm
header("Location: ../product-management.php");
exit;