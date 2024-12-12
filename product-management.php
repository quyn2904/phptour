<?php
session_start();
require_once "include/db.inc.php";

try {
    // Truy vấn chỉ lấy một ảnh đầu tiên cho mỗi sản phẩm
    $stmt = $pdo->prepare("
        SELECT 
            p.id,
            p.name,
            p.description,
            p.quantity,
            c.name AS category_name,
            pp.price AS product_price,
            (SELECT i.path 
             FROM image i 
             WHERE i.product_id = p.id 
             ORDER BY i.id ASC LIMIT 1) AS image_path
        FROM product p
        LEFT JOIN category c ON p.category_id = c.id
        LEFT JOIN productprice pp ON p.id = pp.product_id AND pp.starting_timestamp = (
            SELECT MIN(starting_timestamp) 
            FROM productprice 
            WHERE product_id = p.id
        )
        GROUP BY p.id
        LIMIT 10
    ");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Lỗi khi truy vấn dữ liệu: " . $e->getMessage();
}
?>


<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Product Management</title>
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
            height="30"
            src="https://storage.googleapis.com/a1aa/image/FEJbZin0JpKKJNiVCRapiITHPGQisQ0ejxFpXqiVNl0eOZ5TA.jpg"
            width="30"
          />
          <span class="text-2xl font-extrabold"> Usbibracelet </span>
        </div>
        <nav class="space-y-2">
          <a
            class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700"
            href="user-management.php"
          >
            <i class="fas fa-tachometer-alt"> </i>
            USER
          </a>
          <a
            class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700"
            href="product-management.php"
          >
            <i class="fas fa-cube"> </i>
            PRODUCT
          </a>
          <a
            class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700"
            href="#"
          >
            <i class="fas fa-table"> </i>
            ORDER
          </a>
          <a
            class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700"
            href="#"
          >
            <i class="fas fa-edit"> </i>
            BLOG
          </a>
        </nav>
      </div>
      <!-- Main content -->
      <div class="flex-1 flex flex-col">
        <!-- Header -->
        <header
          class="flex items-center justify-between bg-white py-4 px-6 border-b-2 border-gray-200"
        >
          <div class="flex items-center">
            <input
              class="bg-gray-100 rounded-lg px-4 py-2 focus:outline-none"
              placeholder="Search"
              type="text"
            />
          </div>
          <div class="flex items-center space-x-4">
            <i class="fas fa-bell"> </i>
            <img
              alt="User Avatar"
              class="h-8 w-8 rounded-full"
              height="30"
              src="https://storage.googleapis.com/a1aa/image/MTKO2Sffe3GPdpV1fFlPEDfTgoIYJnACU6dUPZ54eGAyuTWeJA.jpg"
              width="30"
            />
          </div>
        </header>
        <!-- Dashboard content -->
        <main class="flex-1 bg-gray-100 p-6">
          <h1 class="text-2xl font-semibold mb-6">Dashboard</h1>
          <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="min-w-full bg-white">
              <thead class="bg-gray-50">
                <tr>
                  <th
                    class="py-2 px-4 border-b border-gray-200 text-left text-sm font-semibold text-gray-600"
                  >
                    
                  </th>
                  <th
                    class="py-2 px-4 border-b border-gray-200 text-left text-sm font-semibold text-gray-600"
                  >
                    ID
                  </th>
                  <th
                    class="py-2 px-4 border-b border-gray-200 text-left text-sm font-semibold text-gray-600"
                  >
                    NAME
                  </th>
                  <th
                    class="py-2 px-4 w-96 border-b border-gray-200 text-left text-sm font-semibold text-gray-600"
                  >
                    DESCRIPTION
                  </th>
                  <th
                    class="py-2 px-4 border-b border-gray-200 text-left text-sm font-semibold text-gray-600"
                  >
                    PRICE
                  </th>
                  <th
                    class="py-2 px-4 border-b border-gray-200 text-left text-sm font-semibold text-gray-600"
                  >
                    QUANTITY
                  </th>
                  <th
                    class="py-2 px-4 border-b border-gray-200 text-left text-sm font-semibold text-gray-600"
                  >
                    CATEGORY
                  </th>
                  <th
                    class="py-2 px-4 border-b border-gray-200 text-left text-sm font-semibold text-gray-600"
                  >
                    ACTION
                  </th>
                </tr>
              </thead>
              <tbody>
                <?php if ($products): ?>
                  <?php foreach($products as $product): ?>
                    <tr class="py-2 px-4 border-b border-gray-200 text-sm">
                      <td>
                        <!-- Hiển thị ảnh đầu tiên -->
                        <?php if ($product['image_path']): ?>
                          <img src="<?= htmlspecialchars($product['image_path']) ?>" alt="Product Image" class="h-12 w-12 object-cover">
                        <?php else: ?>
                          <span>No image</span>
                        <?php endif; ?>
                      </td>
                      <td><?= htmlspecialchars($product['id']) ?></td>
                      <td><?= htmlspecialchars($product['name']) ?></td>
                      <td class="w-96 line-clamp-3"><?= htmlspecialchars($product['description']) ?></td>
                      <td><?= htmlspecialchars($product['product_price'] ?? 'N/A') ?></td>
                      <td><?= htmlspecialchars($product['quantity']) ?></td>
                      <td><?= htmlspecialchars($product['category_name'] ?? 'N/A') ?></td>
                      <td class="text-blue-500 cursor-pointer">Edit</td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
          </div>
        </main>
      </div>
    </div>
  </body>
</html>
