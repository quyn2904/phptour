<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['previous_page'] = $_SERVER['HTTP_REFERER'];
    // Lấy productId và quantity từ request
    $productId = $_POST['productId'];
    $quantity = $_POST['quantity'];

    // Khởi tạo giỏ hàng nếu chưa tồn tại
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
    $productExists = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['productId'] == $productId) {
            $item['quantity'] += $quantity;
            $productExists = true;
            break;
        }
    }

    // Nếu sản phẩm chưa có trong giỏ hàng, thêm mới
    if (!$productExists) {
        $_SESSION['cart'][] = [
            'productId' => $productId,
            'quantity' => $quantity
        ];
    }
    $_SESSION['cart_added'] = true;

    header('Location: ' . $_SESSION['previous_page']);
    exit;
} else {
    header('Location: ' . $_SESSION['previous_page']);
}

?>