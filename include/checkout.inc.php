<?php
session_start();

// Kiểm tra nếu phương thức request là POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Kiểm tra nếu dữ liệu quantities tồn tại và là một mảng
    if (isset($_POST['quantities']) && is_array($_POST['quantities'])) {
        // Khởi tạo giỏ hàng nếu chưa tồn tại
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Duyệt qua các sản phẩm và cập nhật số lượng
        foreach ($_POST['quantities'] as $productId => $quantity) {
            $quantity = (int)$quantity; // Ép kiểu số nguyên

            // Bỏ qua sản phẩm nếu số lượng không hợp lệ
            if ($quantity <= 0) {
                continue;
            }

            // Kiểm tra nếu sản phẩm đã tồn tại trong giỏ hàng
            $productExists = false;
            foreach ($_SESSION['cart'] as &$item) {
                if ($item['productId'] == $productId) {
                    $item['quantity'] = $quantity; // Cập nhật số lượng mới
                    $productExists = true;
                    break;
                }
            }

            // Nếu sản phẩm chưa tồn tại trong giỏ hàng, thêm mới
            if (!$productExists) {
                $_SESSION['cart'][] = [
                    'productId' => $productId,
                    'quantity' => $quantity
                ];
            }
        }

        // Xóa các sản phẩm có số lượng bằng 0 khỏi giỏ hàng
        $_SESSION['cart'] = array_filter($_SESSION['cart'], function ($item) {
            return $item['quantity'] > 0;
        });

        // Gửi phản hồi hoặc chuyển hướng (tuỳ yêu cầu)
        $_SESSION['cart_updated'] = true;
        header('Location: ../checkout-ship.php');
        exit;
    }
} else {
    // Chuyển hướng nếu truy cập không hợp lệ
    header('Location: /');
    exit;
}
?>
