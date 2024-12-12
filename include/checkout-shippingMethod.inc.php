<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $shippingMethod = $_POST["shippingMethod"];
    if ($_SESSION['order_id'] == null) {
        header("Location: ../login.php");
        exit();
    }
    require_once "db.inc.php";
    $shippingFee = 0;
    if ($shippingMethod == 'grab') {
        $shippingFee = 20000;
    } else if ($shippingMethod == 'bee') {
        $shippingFee = 25000;
    }

    $_SESSION['shippingMethod'] = $shippingMethod;
    $_SESSION['shippingFee'] = $shippingFee;

    try {
        $queryOrder = "SELECT * FROM  `order` WHERE id = :id;";

        $stmtOrder = $pdo->prepare($queryOrder);
        $stmtOrder->bindParam(":id", $_SESSION['order_id']);
        $stmtOrder->execute();

        $result = $stmtOrder->fetch(PDO::FETCH_ASSOC);
        $total = $result['total'];
        if ($result) {
            if ($result['shippingMethod'] == 'grab') {
                $shippingFee -= 20000;
            } else if ($result['shippingMethod'] == 'bee') {
                $shippingFee -= 25000;
            }
            $total += $shippingFee; 
            $updateOrder = "UPDATE `order` SET shippingMethod = :shippingMethod, total = :total WHERE id = :id;";
            $stmtOrder = $pdo->prepare($updateOrder);
            $stmtOrder->bindParam(":id", $_SESSION['order_id']);
            $stmtOrder->bindParam(":shippingMethod", $shippingMethod);
            $stmtOrder->bindParam(":total", $total);
            $stmtOrder->execute();
            header("Location: ../checkout-paymentMethod.php");
        };
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