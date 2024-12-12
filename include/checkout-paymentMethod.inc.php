<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $paymentMethod = $_POST["paymentMethod"];
    if ($_SESSION['order_id'] == null) {
        header("Location: ../login.php");
        exit();
    }
    require_once "db.inc.php";
    $status = 'successed';
    try {
        $updateOrder = "UPDATE `order` SET paymentMethod = :paymentMethod, status = :status WHERE id = :id;";
        $stmtOrder = $pdo->prepare($updateOrder);
        $stmtOrder->bindParam(":id", $_SESSION['order_id']);
        $stmtOrder->bindParam(":paymentMethod", $paymentMethod);
        $stmtOrder->bindParam(":status", $status);
        $stmtOrder->execute();
        header("Location: ../checkout-success.php");
        unset($_SESSION["cart"]);
        die();
    } catch (Exception $e) {
        die("Query failed: " . $e->getMessage());
    } finally {
        $pdo = null;
        $stmtOrder = null;
    }
} else {
    header("Location: ../index.php");
    die();
}

?>