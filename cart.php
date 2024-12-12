<?php
session_start();
require_once "include/db.inc.php";
$total = 0;

if (isset($_SESSION['cart'])) {
  $cartItems = [];
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
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link
      href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css"
      rel="stylesheet"
    />
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

    <form class="w-[70%] mt-10 mx-auto bg-white p-6 rounded-lg shadow-md" action="include/checkout.inc.php" method="post">
      <h1 class="text-2xl font-bold text-red-600 mb-6">Giỏ hàng của tôi</h1>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2 space-y-6 max-h-[500px] overflow-auto pr-4">
        <?php if (!empty($cartItems)): ?>
          <?php foreach ($cartItems as $item): ?>
            <div
              class="flex items-center justify-between p-4 border rounded-lg bg-white shadow-sm"
            >
              <img
                alt="Placeholder image for Sản phẩm 1"
                class="w-24 h-24 object-cover rounded-lg"
                height="100"
                src="<?php echo $item['images'][0]['path']; ?>"
                width="100"
              />
              <div class="flex-1 ml-4">
                <h2 class="text-lg font-semibold"><?= $item['productName'] ?></h2>
                <div class="flex items-center mt-2">
                  <span class="mr-2"> Số lượng: </span>
                  <button
                    class="bg-red-600 font-bold text-white rounded-full w-8 h-8 flex items-center justify-center btn-decrease"
                    data-product-id="<?= $item['productId']; ?>"
                    type="button"
                  >
                    <p>-</p>
                  </button>
                  <span class="mx-2" id="quantity-<?= $item['productId']; ?>"> <?= $item['quantity'] ?> </span>
                  <input
                    type="hidden"
                    name="quantities[<?= $item['productId']; ?>]"
                    id="hidden-quantity-<?= $item['productId']; ?>"
                    value="<?= $item['quantity']; ?>"
                  />
                  <button
                    class="bg-red-600 font-bold text-white rounded-full w-8 h-8 flex items-center justify-center btn-increase"
                    data-product-id="<?= $item['productId']; ?>"
                    type="button"
                  >
                    <p>+</p>
                  </button>
                </div>
                <p class="mt-2">
                  Thành tiền:
                  <span id="sub-total-<?= $item['productId']; ?>" class="font-bold">
                    <?php echo number_format($item['quantity'] * $item['price'], 0, ',', '.'); ?> <span class="underline">đ</span>
                  </span>
                </p>
                <p class="hidden" id="unit-price-<?= $item['productId']; ?>">
                  <?= $item['price']; ?>
                </p>
              </div>
              <button class="text-gray-500 hover:text-red-600">
                <i class="fas fa-trash-alt"> </i>
              </button>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
            <p>Không có sản phẩm trong giỏ hàng.</p>
        <?php endif; ?>
        </div>
        <div class="bg-white p-6 rounded-lg h-fit shadow-md">
          <h2 class="text-lg font-bold mb-4">Tóm tắt đơn hàng</h2>
          <div class="mb-4">
            <label class="block text-sm font-medium" for="discount-code">
              Mã giảm giá
            </label>
            <div class="flex mt-1">
              <input
                class="flex-1 border rounded-l-lg p-2"
                id="discount-code"
                type="text"
              />
              <button class="bg-red-600 text-white w-72 rounded-r-lg">
                <p>Áp dụng</p>
              </button>
            </div>
          </div>
          <div class="space-y-2">
            <div class="flex justify-between">
              <span> Tổng đơn hàng </span>
              <span id="order-total"> <?php echo number_format($total, 0, ',', '.'); ?> <span class="underline">đ</span> </span>
            </div>
            <div class="flex justify-between">
              <span> Giảm giá </span>
              <span> -0đ </span>
            </div>
            <div class="flex justify-between font-bold text-red-600">
              <span>Tổng thanh toán</span>
              <span id="total-payment">
                <?php echo number_format($total, 0, ',', '.'); ?> <span class="underline">đ</span>
              </span>
            </div>
          </div>
          <button type="submit" class="w-full bg-red-600 text-white py-2 mt-4 rounded-lg">
            Thanh toán
          </button>
        </div>
      </div>
    </form>
    <!-- footer -->
    <div
      class="mt-20 grid min-h-40 grid-cols-4 gap-10 bg-[#FDF8F8] px-16 pt-6 text-[#CE112D]"
    >
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
        <a class="mt-2 block text-sm">Trang chủ</a>
        <a class="mt-2 block text-sm">Bài viết</a>
        <a class="mt-2 block text-sm">Cửa hàng</a>
        <a class="mt-2 block text-sm">Câu chuyện Usbi</a>
        <a class="mt-2 block text-sm">Giỏ hàng</a>
      </div>
      <div>
        <h1 class="text-lg">Theo dõi Usbi tại</h1>
      </div>
    </div>
    <!-- footer -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      document.querySelectorAll('.btn-increase').forEach(button => {
        button.addEventListener('click', function () {
          const productId = this.getAttribute('data-product-id');
          const quantitySpan = document.getElementById(`quantity-${productId}`);
          const hiddenInput = document.getElementById(`hidden-quantity-${productId}`);
          const unitPrice = parseFloat(document.getElementById(`unit-price-${productId}`).textContent);
          const subTotalSpan = document.getElementById(`sub-total-${productId}`);
          let quantity = parseInt(quantitySpan.textContent);

          // Tăng số lượng
          quantity += 1;
          quantitySpan.textContent = quantity;
          hiddenInput.value = quantity; // Cập nhật giá trị input ẩn

          // Cập nhật thành tiền
          const subTotal = quantity * unitPrice;
          subTotalSpan.textContent = subTotal.toLocaleString('vi-VN', {
            style: 'currency',
            currency: 'VND'
          });

          // Cập nhật tổng thanh toán
          updateTotal();
        });
      });

      document.querySelectorAll('.btn-decrease').forEach(button => {
        button.addEventListener('click', function () {
          const productId = this.getAttribute('data-product-id');
          const quantitySpan = document.getElementById(`quantity-${productId}`);
          const hiddenInput = document.getElementById(`hidden-quantity-${productId}`);
          const unitPrice = parseFloat(document.getElementById(`unit-price-${productId}`).textContent);
          const subTotalSpan = document.getElementById(`sub-total-${productId}`);
          let quantity = parseInt(quantitySpan.textContent);

          // Giảm số lượng, nhưng không cho nhỏ hơn 1
          if (quantity > 1) {
            quantity -= 1;
            quantitySpan.textContent = quantity;
            hiddenInput.value = quantity; // Cập nhật giá trị input ẩn

            // Cập nhật thành tiền
            const subTotal = quantity * unitPrice;
            subTotalSpan.textContent = subTotal.toLocaleString('vi-VN', {
              style: 'currency',
              currency: 'VND'
            });

            // Cập nhật tổng thanh toán
            updateTotal();
          }
        });
      });

      function updateTotal() {
        let total = 0;

        // Tính tổng tiền của tất cả các sản phẩm
        document.querySelectorAll('[id^="sub-total-"]').forEach(subTotalSpan => {
            const subTotal = parseFloat(subTotalSpan.textContent.replace(/\D/g, '')); // Chuyển "12.000đ" thành "12000"
            total += subTotal;
        });

        // Hiển thị Tổng đơn hàng
        const orderTotalSpan = document.getElementById('order-total'); // Phần tử Tổng đơn hàng
        if (orderTotalSpan) {
            orderTotalSpan.textContent = total.toLocaleString('vi-VN', {
                style: 'currency',
                currency: 'VND'
            });
        }

        // Hiển thị Tổng thanh toán
        const totalPaymentSpan = document.getElementById('total-payment'); // Phần tử Tổng thanh toán
        if (totalPaymentSpan) {
            totalPaymentSpan.textContent = total.toLocaleString('vi-VN', {
                style: 'currency',
                currency: 'VND'
            });
        }
      }
    </script>
  </body>
</html>