<?php
session_start();
require_once "include/db.inc.php";

try {
    // Kiểm tra nếu có categoryId trên URL
    $categoryId = isset($_GET['categoryId']) ? intval($_GET['categoryId']) : null;

    // Kiểm tra nếu có sort trên URL
    $sort = isset($_GET['sort']) ? $_GET['sort'] : 'default';

    // Xây dựng câu truy vấn SQL
    $sql = "
        SELECT 
            p.id AS product_id,
            p.name AS product_name,
            p.description,
            p.quantity,
            pp.price AS product_price,
            c.name AS category_name,
            i.path AS image_path
        FROM product p
        LEFT JOIN (
            SELECT product_id, price 
            FROM productprice
            WHERE starting_timestamp = (
                SELECT MIN(starting_timestamp) 
                FROM productprice pp2 
                WHERE pp2.product_id = productprice.product_id
            )
        ) pp ON p.id = pp.product_id
        LEFT JOIN (
            SELECT product_id, path 
            FROM image i
            WHERE id = (
                SELECT MIN(id) 
                FROM image i2 
                WHERE i2.product_id = i.product_id
            )
        ) i ON p.id = i.product_id
        LEFT JOIN category c ON p.category_id = c.id
    ";

    // Thêm điều kiện WHERE nếu có categoryId
    if ($categoryId !== null) {
        $sql .= " WHERE p.category_id = :categoryId ";
    }

    // Thêm điều kiện ORDER BY nếu có sắp xếp
    if ($sort === 'asc') {
        $sql .= "ORDER BY pp.price ASC ";
    } elseif ($sort === 'desc') {
        $sql .= "ORDER BY pp.price DESC ";
    } else {
        $sql .= "ORDER BY p.id ASC ";  // Sắp xếp theo ID nếu không có sắp xếp
    }

    $sql .= "LIMIT 10;";

    // Chuẩn bị và thực thi câu truy vấn
    $stmt = $pdo->prepare($sql);

    // Bind giá trị nếu có categoryId
    if ($categoryId !== null) {
        $stmt->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);
    }

    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Lấy tên danh mục nếu có categoryId
    $categoryName = "Tất Cả Sản Phẩm";
    if ($categoryId !== null) {
        $stmtCategory = $pdo->prepare("SELECT name FROM category WHERE id = :categoryId");
        $stmtCategory->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);
        $stmtCategory->execute();
        $category = $stmtCategory->fetch(PDO::FETCH_ASSOC);
        if ($category) {
            $categoryName = $category['name'];
        }
    }

    // Lấy tất cả danh mục
    $stmtAllCategories = $pdo->prepare("SELECT id, name FROM category");
    $stmtAllCategories->execute();
    $categories = $stmtAllCategories->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Lỗi khi truy vấn dữ liệu: " . $e->getMessage();
}
?>



<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
  </head>
  <body>
        <!-- header -->
        <div class="flex items-center justify-between px-20 py-4">
      <h1 class="text-2xl font-bold text-red-500">Usbibracelet</h1>
      <div class="relative flex w-3/5 items-center">
        <input
          class="w-full rounded-xl border bg-[#FFEAEA] p-2"
          placeholder="Tìm kiếm ..."
        />
        <button class="absolute right-3 h-6">
          <img src="./assets/images/search.png" class="h-full w-auto" />
        </button>
      </div>
      <div class="flex items-center gap-4">
        <?php
          if (isset($_SESSION["user_name"])) {
            echo "<p class='text-lg font-bold text-red-500'>" . $_SESSION["user_name"] . "</p>";
            echo "<form method='post' action='include/logout.inc.php'><button class='rounded-lg border bg-green-400 px-6 py-2 font-bold'>Log Out</button></button></form>";
          } else {
            echo "<button id='btn_login' class='rounded-lg border bg-blue-400 px-6 py-2 font-bold'>Login</button>";
            echo "<button class='rounded-lg border bg-green-400 px-6 py-2 font-bold'>Register</button>";
          }
        ?>
      </div>
    </div>
    <div class="bg-[#FFEAEA]">
      <ul
        class="mt-2 flex items-center justify-around py-4 text-2xl font-bold text-[#CE112D]"
      >
        <li>Trang chủ</li>
        <li>Bài viết</li>
        <li><a href="product-list.php">Cửa hàng</a></li>
        <li>Về chúng tôi</li>
        <li>Tin tức</li>
      </ul>
    </div>
    <!-- end header -->

    <!-- product grid -->
    <div class="mx-10 mt-12 grid grid-cols-10 gap-10">
      <div class="col-span-2 border-r px-1">
        <div class="border-b pb-3">
        <select id="sort" class="w-full border p-2">
          <option value="default">Sắp xếp theo</option>
          <option value="asc">Giá từ thấp đến cao</option>
          <option value="desc">Giá từ cao đến thấp</option>
        </select>
        </div>
        <div class="mt-3">
          <?php foreach ($categories as $category): ?>
            <div class="mb-2 flex items-center gap-2">
              <?php if ($category['id'] == $categoryId): ?>
                <a href="?categoryId=<?= $category['id'] ?>" class="font-semibold italic underline"><?= $category['name'] ?></a>
              <?php else: ?>
                <a href="?categoryId=<?= $category['id'] ?>" class="font-semibold"><?= $category['name'] ?></a>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="col-span-8">
        <p class="text-2xl font-bold text-[#CE112D]"><?= $categoryName ?></p>
        <div class="mt-3 grid grid-cols-4 gap-x-6 gap-y-1">
          <!-- product -->
          <?php foreach ($products as $product): ?>
            <div id="product-detail" class="h-96 w-72 rounded-sm border bg-slate-200">
            <img class="h-3/4 w-full" src="<?= $product['image_path'] ?>"/>
            <div class="mt-3 px-3">
              <a href="product-detail.php?productId=<?= $product['product_id'] ?>" class="font-bold"><?= $product['product_name'] ?></a>
              <p class="mt-1"><?php echo number_format($product['product_price'], 0, ',', '.') . 'đ'; ?></p>
              <div class="flex items-center gap-2">
                <div class="mt-1 flex gap-2">
                  <img src="./assets/images/star.png" />
                  <img src="./assets/images/star.png" />
                  <img src="./assets/images/star.png" />
                  <img src="./assets/images/star.svg" />
                  <img src="./assets/images/star.svg" />
                </div>
                <p class="translate-y-0.5">(30)</p>
              </div>
            </div>
          </div>
          <?php endforeach; ?> 

          <!-- product -->
        </div>
      </div>
    </div>

    <!-- end product grid -->

    <!-- footer -->
    <div
      class="mt-20 min-h-40 grid-cols-4 bg-[#FDF8F8] px-16 pt-6 text-[#CE112D]"
    >
      <div class="mb-3">
        <input
          class="py-2 px-5 border rounded"
          placeholder="Nhập email của bạn ..."
        />
        <button class="bg-[#FFEAEA] w-32 font-bold h-10 rounded">
          Đăng ký
        </button>
      </div>
      <div class="grid grid-cols-4 gap-10">
        <div>
          <h1 class="text-3xl font-bold">Usbibracelet</h1>
          <h3 class="mt-2 text-lg">Đăng ký</h3>
          <h1 class="mt-2 text-xl font-semibold italic">
            Nhận ngay mã giảm giá 12%
          </h1>
        </div>
        <div>
          <h1 class="text-lg">Hỗ trợ</h1>
          <h3 class="mt-2 text-sm">Đường CMT8, Quận 10, TP HCM</h3>
          <h3 class="mt-2 text-sm">Usbi@gmail.com</h3>
          <h3 class="mt-2 text-sm">08358588484</h3>
        </div>
        <div>
          <h1 class="text-lg">Menu</h1>
          <a href="/index.html" class="mt-2 block text-sm">Trang chủ</a>
          <a href="/blog.html" class="mt-2 block text-sm">Bài viết</a>
          <a class="mt-2 block text-sm">Cửa hàng</a>
          <a class="mt-2 block text-sm">Câu chuyện Usbi</a>
          <a class="mt-2 block text-sm">Giỏ hàng</a>
        </div>
        <div>
          <h1 class="text-lg">Theo dõi Usbi tại</h1>
        </div>
      </div>
    </div>
    <!-- end footer -->
    <script>
      btn_login = document.getElementById("btn_login");
      btn_login.addEventListener("click", function () {
        window.location.href = "/login.php";
      });
      document.getElementById("product-detail").addEventListener("click", function () {
        window.location.href = "/product-detail.php?product_id=1";
      });
      // Chỉ cho phép chọn một category

      document.addEventListener('DOMContentLoaded', function() {
        const sortElement = document.getElementById('sort');
        if (sortElement) {
          sortElement.addEventListener('change', function() {
            const sortValue = this.value;
            const url = new URL(window.location.href);

            if (sortValue !== 'default') {
              url.searchParams.set('sort', sortValue);  // Thêm tham số sort vào URL
            } else {
              url.searchParams.delete('sort');  // Nếu không chọn sắp xếp, xóa tham số sort
            }

            window.location.href = url.toString();  // Làm mới trang với URL mới
          });
        }
      });
     </script>
  </body>
</html>
