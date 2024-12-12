<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $email = $_POST["email"];
    $phoneNumber = $_POST["phoneNumber"];
    $provinceId = $_POST["provinceId"];
    $districtId = $_POST["districtId"];
    $wardId = $_POST["wardId"];
    $address = $_POST["address"];
    $account_id = $_SESSION["user_id"];
    if ($account_id == null) {
        header("Location: ../login.php");
        exit();
    }

    $total = 0;

    require_once "db.inc.php";

    $cartItems = [];

    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $productId = $item['productId'];
            $quantity = $item['quantity'];
        
            // Truy vấn thông tin sản phẩm từ database
            $stmt = $pdo->prepare("SELECT * FROM product WHERE id = :id");
            $stmt->bindParam(':id', $productId, PDO::PARAM_INT);
            $stmt->execute();
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
        
            if ($product) { // Kiểm tra xem sản phẩm có tồn tại
                $stmtImages = $pdo->prepare("SELECT path FROM image WHERE product_id = :productId");
                $stmtImages->bindParam(':productId', $productId, PDO::PARAM_INT);
                $stmtImages->execute();
                $images = $stmtImages->fetchAll(PDO::FETCH_ASSOC);
        
                $stmtPrice = $pdo->prepare('SELECT * FROM productprice WHERE product_id = :productId');
                $stmtPrice->bindParam(':productId', $productId, PDO::PARAM_INT);
                $stmtPrice->execute();
                $price = $stmtPrice->fetch(PDO::FETCH_ASSOC);
        
                if ($price) { // Kiểm tra xem giá có tồn tại
                    // Kết hợp thông tin
                    $cartItems[] = [
                        'productId' => $product['id'],
                        'productName' => $product['name'],
                        'productQuantity' => $product['quantity'],
                        'quantity' => $quantity,
                        'images' => $images,
                        'price' => $price['price'], // Thay 'price' bằng tên cột tương ứng nếu cần
                    ];
                    $total += $quantity * $price['price'];
                }
            }
        }
    }

    try {
        $queryOrder = "INSERT INTO `order` (account_id, status, phonenumber, provinceId, districtId, wardId, address, total) 
            VALUE (?, ?, ?, ?, ?, ?, ?, ?);";

        $stmtOrder = $pdo->prepare($queryOrder);

        $stmtOrder->execute([$account_id, "pending", $phoneNumber, $provinceId, $districtId, $wardId, $address, $total]);
        $orderId = $pdo->lastInsertId();

        $_SESSION['order_id'] = $orderId;

        foreach ($cartItems as $cartItem) {
            $queryOrderDetail = "INSERT INTO `orderdetail` (product_id, quantity, price, order_id) 
                VALUE (?, ?, ?, ?);";
            $stmtOrderDetail = $pdo->prepare($queryOrderDetail);
            $stmtOrderDetail->execute([$cartItem['productId'], $cartItem['quantity'], $cartItem['price'], $orderId]);
        }

        header("Location: ../checkout-shippingMethod.php");
        die();
    } catch (Exception $e) {
        die("Query failed: " . $e->getMessage());
    } finally {
        $pdo = null;
        $stmtOrder = null;
        $stmtOrderDetail = null;
    }
} else {
    header("Location: ../index.php");
    die();
}

?>
