<?php
session_start();
require_once "db.inc.php";

if (isset($_GET['account_id'])) {
  $accountId = intval($_GET['account_id']);

  try {
    // Cập nhật isDeleted = true cho tài khoản
    $stmt = $pdo->prepare("UPDATE account SET isDeleted = true WHERE id = :id");
    $stmt->execute(['id' => $accountId]);

    $_SESSION['message'] = "User has been successfully deleted.";
  } catch (PDOException $e) {
    $_SESSION['message'] = "Error deleting user: " . $e->getMessage();
  }
} else {
  $_SESSION['message'] = "Invalid account ID.";
}

// Quay về trang quản lý người dùng
header("Location: ../user-management.php");
exit;
?>
