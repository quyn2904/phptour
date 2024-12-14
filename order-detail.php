<?php
session_start();
require_once "include/db.inc.php";

// Lấy orderId từ URL
$orderId = isset($_GET['orderId']) ? (int)$_GET['orderId'] : 0;

// Nếu không có orderId, thông báo lỗi
if (!$orderId) {
    die("Order ID is required.");
}

// Câu truy vấn 1: Lấy thông tin đơn hàng từ bảng `order`
$sqlOrder = "
    SELECT 
        o.id AS order_id, 
        a.firstname, 
        a.lastname, 
        o.phonenumber, 
        o.address, 
        o.shippingMethod, 
        o.paymentMethod, 
        o.total, 
        o.status
    FROM `order` AS o
    JOIN `account` AS a ON o.account_id = a.id
    WHERE o.id = :orderId
";

// Chuẩn bị và thực thi câu lệnh lấy thông tin đơn hàng
$stmt = $pdo->prepare($sqlOrder);
$stmt->bindValue(':orderId', $orderId, PDO::PARAM_INT);
$stmt->execute();

// Lấy dữ liệu đơn hàng
$orderInfo = $stmt->fetch(PDO::FETCH_ASSOC);

// Nếu không tìm thấy đơn hàng, báo lỗi
if (!$orderInfo) {
    die("No order found with ID: " . htmlspecialchars($orderId));
}

// Câu truy vấn 2: Lấy thông tin chi tiết đơn hàng từ bảng `orderdetail` và `product`
$sqlOrderDetails = "
    SELECT 
        od.product_id, 
        od.quantity, 
        od.price, 
        p.name
    FROM `orderdetail` AS od
    JOIN `product` AS p ON od.product_id = p.id
    WHERE od.order_id = :orderId
";

// Chuẩn bị và thực thi câu lệnh lấy chi tiết đơn hàng
$stmtDetails = $pdo->prepare($sqlOrderDetails);
$stmtDetails->bindValue(':orderId', $orderId, PDO::PARAM_INT);
$stmtDetails->execute();

// Lấy các chi tiết sản phẩm trong đơn hàng
$orderDetails = $stmtDetails->fetchAll(PDO::FETCH_ASSOC);

?>

<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Order Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
      rel="stylesheet"
    />
  </head>
  <body class="bg-gray-100">
    <div class="flex h-screen">
      <!-- Sidebar -->
      <div class="bg-gray-900 h-vh text-white w-64 space-y-6 py-7 px-2">
        <div class="flex items-center space-x-2 px-4">
          <img
            alt="Logo"
            class="h-8 w-8"
            src="https://storage.googleapis.com/a1aa/image/FEJbZin0JpKKJNiVCRapiITHPGQisQ0ejxFpXqiVNl0eOZ5TA.jpg"
          />
          <span class="text-2xl font-extrabold"> Usbibracelet </span>
        </div>
        <nav class="space-y-2">
          <a class="block py-2.5 px-4 hover:bg-gray-700" href="user-management.php">USER</a>
          <a class="block py-2.5 px-4 hover:bg-gray-700" href="product-management.php">PRODUCT</a>
          <a class="block py-2.5 px-4 hover:bg-gray-700" href="#">ORDER</a>
          <a class="block py-2.5 px-4 hover:bg-gray-700" href="blog-management.php">BLOG</a>
        </nav>
      </div>

      <!-- Main Content -->
      <div class="flex-1 flex flex-col">
        <!-- Header -->
        <header class="bg-white py-4 px-6 border-b">
          <h1 class="text-2xl font-semibold">Order Management</h1>
        </header>

        <!-- Orders Table -->
        <main class="flex-1 bg-gray-100 p-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <!-- Thông tin chung của Order -->
                <h2 class="text-xl font-semibold mb-4">Order Details</h2>
                <p><strong>Order ID:</strong> <?= htmlspecialchars($orderInfo['order_id']) ?></p>
                <p><strong>Customer Name:</strong> <?= htmlspecialchars($orderInfo['firstname'] . ' ' . $orderInfo['lastname']) ?></p>
                <p><strong>Phone:</strong> <?= htmlspecialchars($orderInfo['phonenumber']) ?></p>
                <p><strong>Address:</strong> <?= htmlspecialchars($orderInfo['address']) ?></p>
                <p><strong>Shipping Method:</strong> <?= htmlspecialchars($orderInfo['shippingMethod']) ?></p>
                <p><strong>Payment Method:</strong> <?= htmlspecialchars($orderInfo['paymentMethod']) ?></p>
                <p><strong>Total:</strong> <?= number_format($orderInfo['total'], 0, ',', '.') ?>đ</p>
                <p><strong>Status:</strong> <?= htmlspecialchars($orderInfo['status']) ?></p>

                <!-- Bảng chi tiết sản phẩm -->
                <h3 class="text-lg font-semibold mt-6">Products</h3>
                <table class="min-w-full bg-white border mt-4">
                <thead class="bg-gray-50">
                    <tr>
                    <th class="py-2 px-4 border">Product Name</th>
                    <th class="py-2 px-4 border">Quantity</th>
                    <th class="py-2 px-4 border">Price</th>
                    <th class="py-2 px-4 border">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orderDetails as $detail): ?>
                    <tr>
                        <td class="py-2 px-4 border"><?= htmlspecialchars($detail['name']) ?></td>
                        <td class="py-2 px-4 border"><?= htmlspecialchars($detail['quantity']) ?></td>
                        <td class="py-2 px-4 border"><?= number_format($detail['price'], 0, ',', '.') ?>đ</td>
                        <td class="py-2 px-4 border"><?= number_format($detail['quantity'] * $detail['price'], 0, ',', '.') ?>đ</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                </table>
            </div>
        </main>
      </div>
    </div>
  </body>
</html>
