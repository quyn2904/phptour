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
            // echo "<p class='text-lg font-bold text-red-500'>" . $_SESSION["user_name"] . "</p>";
            echo "<a href='cart.php'><button class='rounded-lg border bg-green-400 px-6 py-2 font-bold'>Cart</button></a>";
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
        <li>Cửa hàng</li>
        <li>Về chúng tôi</li>
        <li>Tin tức</li>
      </ul>
    </div>
    <!-- end header -->

    <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow-lg">
      <div class="flex justify-between items-start">
        <!-- Order Summary -->
        <div class="w-1/2 pr-6">
          <h2 class="text-xl font-bold mb-4">Tóm tắt đơn hàng</h2>
          <?php if (!empty($cartItems)): ?>
            <?php foreach ($cartItems as $item): ?>
              <div class="flex items-center mb-4">
                <img
                  alt="Product 1 image"
                  class="w-16 h-16 mr-4"
                  height="60"
                  src="<?php echo $item['images'][0]['path']; ?>"
                  width="60"
                />
                <div class="flex-1">
                  <p><?= $item['productName'] ?></p>
                  <div class="flex items-center">
                    <button
                      class="border w-6 h-6 flex items-center justify-center font-bold rounded-lg bg-slate-200"
                    >
                      -
                    </button>
                    <span class="px-2"> <?= $item['quantity'] ?> </span>
                    <button
                      class="border w-6 h-6 flex items-center justify-center font-bold rounded-lg bg-slate-200"
                    >
                      +
                    </button>
                  </div>
                </div>
                <p class="w-20 text-right"><?php echo number_format($item['quantity'] * $item['price'], 0, ',', '.') . 'đ'; ?></p>
                <button class="text-gray-500 ml-4">
                  <i class="fas fa-trash"> </i>
                </button>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
              <p>Không có sản phẩm trong giỏ hàng.</p>
          <?php endif; ?>
          <div class="mb-4">
            <label class="block mb-2" for="discount-code"> Mã giảm giá </label>
            <div class="flex">
              <input
                class="border border-gray-300 p-2 flex-1"
                id="discount-code"
                placeholder="Nhập mã giảm giá"
                type="text"
              />
              <button class="bg-red-500 text-white px-4 py-2 ml-2">
                Áp dụng
              </button>
            </div>
          </div>
          <div class="border-t border-gray-300 pt-4">
            <div class="flex justify-between mb-2">
              <p>Tổng đơn hàng</p>
              <p><?php echo number_format($total, 0, ',', '.') . 'đ'; ?></p>
            </div>
            <div class="flex justify-between mb-2">
              <p>Phí vận chuyển</p>
              <p class="text-green-500" id="shippingFee">FREE</p>
            </div>
            <div class="flex justify-between mb-2">
              <p>Giảm giá</p>
              <p>-0đ</p>
            </div>
            <div class="flex justify-between font-bold text-red-500">
              <p>Tổng thanh toán</p>
              <p id="total"><?php echo number_format($total, 0, ',', '.') . 'đ'; ?></p>
            </div>
          </div>
        </div>
        <!-- Contact and Shipping Information -->
        <div class="w-1/2 pl-6" >
          <div class="mb-5 items-center flex gap-1">
            <p class="text-red-500 font-semibold">Vận chuyển</p>
            <div class="flex gap-2 items-center">
              <div class="border-red-500 border w-5 translate-y-0.5"></div>
              <div
                class="border flex items-center justify-center font-bold text-white text-xs translate-y-0.5 h-5 w-5 rounded-full bg-slate-500"
              >
                ✓
              </div>
              <div class="border border-red-500 w-5 translate-y-0.5"></div>
            </div>
            <p class="font-semibold text-red-500">Giao hàng</p>
            <div class="flex gap-2 items-center">
              <div class="border w-5 translate-y-0.5"></div>
              <div
                class="border flex items-center justify-center font-bold text-white text-xs translate-y-0.5 h-5 w-5 rounded-full bg-slate-200"
              >
                ✓
              </div>
              <div class="border w-5 translate-y-0.5"></div>
            </div>
            <p class="font-semibold text-slate-500">Thanh toán</p>
          </div>
          <form method="post" action="include/checkout-shippingMethod.inc.php">
            <div class="border rounded mb-5 px-4 py-2 shadow-lg">
              <h1 class="font-bold text-lg">Phương thức vận chuyển</h1>
              <div class="mt-3">
                <div class="flex items-center justify-between">
                  <div class="flex gap-5">
                    <input id="doityourself" value="doityourself" name="shippingMethod" type="radio" class="bg-red-500" />
                    <label for="doityourself" class="-translate-y-0.5">Tự sắp xếp</label>
                  </div>
                  <p class="text-green-400">FREE</p>
                </div>
                <div class="flex mt-1 items-center justify-between">
                  <div class="flex gap-5">
                    <input id="grab" value="grab" type="radio" name="shippingMethod" class="bg-red-500" />
                    <label for="grab" class="-translate-y-0.5">Grab giao hàng</label>
                  </div>
                  <p class="">+ 20 000đ</p>
                </div>
                <div class="flex mt-1 items-center justify-between">
                  <div class="flex gap-5">
                    <input id="bee" type="radio" value="bee" name="shippingMethod" class="bg-red-500" />
                    <label for="bee" class="-translate-y-0.5">Bee giao hàng</label>
                  </div>
                  <p class="">+ 25 000đ</p>
                </div>
              </div>
            </div>
            <div class="flex justify-end gap-7">
              <button
                id="back"
                type="button"
                class="border border-red-500 text-red-500 px-6 py-2 w-32"
              >
                Quay lại
              </button>
              <button
                type="submit"
                class="bg-red-500 text-white px-6 py-2 w-32"
              >
                Tiếp tục
              </button>
            </div>
          </form>
        </dì>
      </div>
    </div>
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
      const doityourself = document.getElementById("doityourself");
      const grab = document.getElementById("grab");
      const bee = document.getElementById("bee");
      
      doityourself.addEventListener("click", () => {
        grab.checked = false;
        bee.checked = false;
        shippingFee.textContent = "FREE";
        shippingFee.classList.add("text-green-500");
        document.getElementById("total").innerHTML = "<?php echo number_format($total, 0, ',', '.') . 'đ'; ?>";
      });

      grab.addEventListener("click", () => {
        doityourself.checked = false;
        bee.checked = false;
        shippingFee.textContent = "+ 20 000đ";
        shippingFee.classList.remove("text-green-500");
        document.getElementById("total").innerHTML = "<?php echo number_format($total + 20000, 0, ',', '.') . 'đ'; ?>";
      });

      bee.addEventListener("click", () => {
        doityourself.checked = false;
        grab.checked = false;
        shippingFee.textContent = "+ 25 000đ";
        shippingFee.classList.remove("text-green-500");
        document.getElementById("total").innerHTML = "<?php echo number_format($total + 25000, 0, ',', '.') . 'đ'; ?>";
      });

      document.getElementById("countinue").addEventListener("click", () => {
        window.location.href = "checkout-paymentMethod.html";
      });

      document.getElementById("back").addEventListener("click", () => {
        window.location.href = "checkout-ship.html";
      });
    </script>
  </body>
</html>
