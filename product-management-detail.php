<?php
session_start();
require_once "include/db.inc.php";

// Khởi tạo mảng sản phẩm mặc định
$product = [
  'id' => null,
  'name' => null,
  'description' => null,
  'price' => null,
  'quantity' => null,
  'category' => null,
  'image' => null
];

// Khởi tạo mảng categories mặc định
$categories = [];

try {
    // Lấy danh sách các danh mục từ bảng categories (giả sử có bảng riêng cho categories)
    $stmt = $pdo->query("SELECT * FROM category");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Lỗi khi truy vấn danh mục: " . $e->getMessage();
}

// Kiểm tra xem có 'productId' trong URL không
if (isset($_GET['productId'])) {
  $product_id = $_GET['productId']; 
  try {
    // Truy vấn thông tin sản phẩm từ cơ sở dữ liệu
    $stmt = $pdo->prepare("SELECT * FROM product WHERE id = :id");
    $stmt->bindParam(":id", $product_id);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmtImages = $pdo->prepare("SELECT path FROM image WHERE product_id = :productId");
    $stmtImages->bindParam(':productId', $product_id, PDO::PARAM_INT);
    $stmtImages->execute();
    $images = $stmtImages->fetch(PDO::FETCH_ASSOC);

    $stmtPrice = $pdo->prepare('SELECT * FROM productprice WHERE product_id = :productId');
    $stmtPrice->bindParam(':productId', $product_id, PDO::PARAM_INT);
    $stmtPrice->execute();
    $price = $stmtPrice->fetch(PDO::FETCH_ASSOC);
    
    // Kiểm tra nếu sản phẩm không tồn tại
    if (!$product) {
      echo "Sản phẩm không tồn tại.";
      exit;
    }

    if (!$price) {
        echo "Price không tồn tại.";
        exit;
      }
  } catch (PDOException $e) {
    echo "Lỗi khi truy vấn dữ liệu: " . $e->getMessage();
    exit;
  }
}
?>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Product Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
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
            href="blog-management.php"
          >
            <i class="fas fa-edit"> </i>
            BLOG
          </a>
        </nav>
      </div>

      <!-- Main content -->
      <div class="flex-1 flex flex-col">
        <!-- Header -->
        <header class="flex items-center justify-between bg-white py-4 px-6 border-b-2 border-gray-200">
          <div class="flex items-center">
            <input class="bg-gray-100 rounded-lg px-4 py-2 focus:outline-none" placeholder="Search" type="text" />
          </div>
          <div class="flex items-center space-x-4">
            <i class="fas fa-bell"> </i>
            <img alt="User Avatar" class="h-8 w-8 rounded-full" height="30" src="https://storage.googleapis.com/a1aa/image/MTKO2Sffe3GPdpV1fFlPEDfTgoIYJnACU6dUPZ54eGAyuTWeJA.jpg" width="30" />
          </div>
        </header>

        <!-- Product Management Form -->
        <main class="flex-1 bg-gray-100 p-6">
          <h1 class="text-2xl font-semibold mb-6"><?= $product['id'] ? 'Update Product' : 'Create Product' ?></h1>
          <div class="bg-white w-[800px] mx-auto py-5 px-10 rounded-lg shadow-md overflow-hidden">
            <form action="include/product-management-detail.inc.php" method="POST">
                <div class="flex justify-between gap-10">
                    <!-- ID Field (readonly) -->
                    <div class="mb-4">
                        <label for="id" class="block text-sm font-medium text-gray-700 mb-1">Product ID</label>
                        <input type="text" id="id" name="id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-indigo-200" placeholder="Enter ID" value="<?= $product['id'] ?>" readonly />
                    </div>

                    <!-- Name Field -->
                    <div class="mb-4 w-4/5">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Product Name</label>
                        <input type="text" id="name" name="name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-indigo-200" placeholder="Enter Product Name" value="<?= htmlspecialchars($product['name']) ?>" required />
                    </div>
                </div>
              
              <!-- Description Field -->
              <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea id="description" name="description" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-indigo-200" placeholder="Enter Product Description" required><?= htmlspecialchars($product['description']) ?></textarea>
              </div>

              <!-- Price Field -->
                <div class="flex grid grid-cols-3 gap-10">
                    <div class="mb-4">
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                        <input type="number" id="price" name="price" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-indigo-200" placeholder="Enter Product Price" value="<?= $price['price'] ?>" required />
                    </div>
                    <div class="mb-4">
                        <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                        <input type="number" id="quantity" name="quantity" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-indigo-200" placeholder="Enter Quantity" value="<?= htmlspecialchars($product['quantity']) ?>" required />
                    </div>
                    <!-- Category Field -->
                    <div class="mb-4">
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select id="category" name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-indigo-200" required>
                            <option value="">Select Category</option>
                            <?php foreach($categories as $category): ?>
                                <option value="<?= $category['id'] ?>" <?= (isset($product['category_id']) && $product['category_id'] == $category['id']) ? 'selected' : '' ?>>
                                    <?= $category['name'] ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
              <!-- Image Path Field -->
              <div class="mb-4">
                <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Image Path</label>
                <?php if (isset($images)): ?>
                    <input type="text" id="image" name="image" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-indigo-200" placeholder="Enter Image Path" value="<?= $images['path'] ?>" />
                <?php else: ?>
                    <input type="text" id="image" name="image" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-indigo-200" placeholder="Enter Image Path" value="" />
                <?php endif; ?>
              </div>

              <!-- Submit Button -->
              <div class="mt-6">
                <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring focus:ring-indigo-300">
                  <?= $product['id'] ? 'Update Product' : 'Create Product' ?>
                </button>
              </div>
            </form>
          </div>
        </main>
      </div>
    </div>
  </body>
</html>
