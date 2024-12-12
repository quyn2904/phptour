<?php
session_start();
require_once "include/db.inc.php";

$productId = isset($_GET['productId']) ? intval($_GET['productId']) : null;

$product = null;

if ($productId) {
  try {
      // Truy vấn thông tin sản phẩm
      $stmt = $pdo->prepare("SELECT * FROM product WHERE id = :id");
      $stmt->bindParam(':id', $productId, PDO::PARAM_INT);
      $stmt->execute();
      $product = $stmt->fetch(PDO::FETCH_ASSOC);

      $stmtImages = $pdo->prepare("SELECT path FROM image WHERE product_id = :productId");
      $stmtImages->bindParam(':productId', $productId, PDO::PARAM_INT);
      $stmtImages->execute();
      $images = $stmtImages->fetchAll(PDO::FETCH_ASSOC);

      $stmtPrice = $pdo->prepare('SELECT * FROM productprice WHERE product_id = :productId');
      $stmtPrice->bindParam(':productId', $productId, PDO::PARAM_INT);
      $stmtPrice->execute();
      $price = $stmtPrice->fetch(PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
      echo "Lỗi khi truy vấn dữ liệu: " . $e->getMessage();
  }
}

if (!$product) {
  echo "<h1>Sản phẩm không tồn tại!</h1>";
  exit;
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

    <div class="mt-8 grid grid-cols-4 gap-16 px-40">
      <div class="col-span-2 h-[480px] w-full">
        <div class="splide h-full w-full" role="group" aria-label="Splide">
          <div class="splide__track h-full w-full">
            <ul class="splide__list h-full w-full">
              <?php if (!empty($images)): ?>
                  <?php foreach ($images as $image): ?>
                    <li class="splide__slide">
                        <img
                          class="h-full w-full"
                          src="<?= $image['path'] ?>"
                        />
                    </li>
                  <?php endforeach; ?>
              <?php else: ?>
                  <p>Không có hình ảnh cho sản phẩm này.</p>
              <?php endif; ?>
            </ul>
          </div>
        </div>
      </div>
      <form class="col-span-2" method="post" action="include/add-to-cart.inc.php">
        <h1 class="text-4xl font-bold"><?= $product['name'] ?></h1>
        <div class="flex items-center mt-3 gap-2">
          <div class="mt-1 flex gap-2">
            <img src="./assets/images/star-yellow.svg" />
            <img src="./assets/images/star-yellow.svg" />
            <img src="./assets/images/star-yellow.svg" />
            <img src="./assets/images/star.svg" />
            <img src="./assets/images/star.svg" />
          </div>
          <p class="translate-y-0.5">(30)</p>
        </div>
        <div class="mt-5">
          <input type="hidden" name="productId" value="<?= $productId ?>"/>
          <h1 class="font-semibold text-3xl text-red-500"><?php echo number_format($price['price'], 0, ',', '.') . 'đ'; ?></h1>
        </div>
        <div class="flex text-2xl h-10 mt-5 font-semibold border-2 w-fit">
          <div id="decrease-quantity" class="w-10 text-center hover:bg-slate-200 cursor-pointer">
            -
          </div>
          <input type="hidden" name="quantity" id="quantityInput" value="1"/>
          <div id="quantity" class="border-r-2 border-l-2 w-20 text-center">1</div>
          <div id="increase-quantity" class="w-10 text-center hover:bg-slate-200 cursor-pointer">
            +
          </div>
        </div>
        <button
          class="block border border-2 py-2 font-semibold rounded-2xl border-red-500 w-96 text-center mt-5 text-2xl"
          type="submit"
        >
          THÊM VÀO GIỎ HÀNG
        </button>
        <button
          class="block py-2 font-semibold rounded-2xl bg-red-500 text-white w-96 text-center mt-5 text-2xl"
        >
          MUA NGAY
        </button>
        <div class="mt-10">
          <div
            id="product-details-container"
            class="w-full border-t border-b py-2 px-5 text-xl font-semibold"
          >
            <div class="flex items-center justify-between">
              <p>PRODUCT DETAILS</p>
              <p id="product-details-icon-trigger">+</p>
            </div>
            <p id="product-details" class="hidden text-sm font-normal italic">
            <?= $product['description'] ?>
            </p>
          </div>
          <div
            id="size-guide-container"
            class="w-full border-t border-b py-2 px-5 text-xl font-semibold"
          >
            <div class="flex items-center justify-between">
              <p>SIZE GUIDE</p>
              <p id="size-guide-icon-trigger">+</p>
            </div>
            <p id="size-guide" class="hidden text-sm font-normal italic">
              Accessorizing can be a challenging sport, but the Tennis Bracelet
              in silver is always at the top of its game. Comfortable and chic,
              this radiant piece gets you through the day, from morning to late
              cocktail hours. To achieve greater durability, each shimmering
              crystal is individually set and tightly secured. The chain itself
              is crafted from high-quality 316L stainless steel and features
              sizing rings to always fit you perfectly. The Tennis Bracelet is
              also a great team player, so pair it with your favorite Daniel
              Wellington watch or bracelets.
            </p>
          </div>
          <div
            id="shipping-return-container"
            class="w-full border-t border-b py-2 px-5 text-xl font-semibold"
          >
            <div class="flex items-center justify-between">
              <p>SHIPPING & RETURNS</p>
              <p id="shipping-return-icon-trigger">+</p>
            </div>
            <p id="shipping-return" class="hidden text-sm font-normal italic">
              Accessorizing can be a challenging sport, but the Tennis Bracelet
              in silver is always at the top of its game. Comfortable and chic,
              this radiant piece gets you through the day, from morning to late
              cocktail hours. To achieve greater durability, each shimmering
              crystal is individually set and tightly secured. The chain itself
              is crafted from high-quality 316L stainless steel and features
              sizing rings to always fit you perfectly. The Tennis Bracelet is
              also a great team player, so pair it with your favorite Daniel
              Wellington watch or bracelets.
            </p>
          </div>
        </div>
      </form>
    </div>

    <div class="mt-20 mx-32 pt-10 border-t-2 border-slate-400">
      <h1 class="text-3xl font-bold text-[#CE112D] text-center">
        SIMILAR PRODUCTS
      </h1>
      <section
        id="image-carousel"
        class="splide mt-10"
        aria-label="Beautiful Images"
      >
        <div class="splide__track mx-16">
          <ul id="splide-list-similar-product" class="splide__list">
            <!-- <li class="splide__slide mx-2">
              <div class="border bg-slate-200 h-[300px]">
                <img
                  class="h-2/3 w-full"
                  src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTN-DR6EPNafE7pyya09WL-HpjohmnqJMUZyA&s"
                />
                <div class="px-2">
                  <h1 class="text-base font-bold mt-2">USBI | Classic Charm</h1>
                  <div class="mt-1">
                    <h1 class="font-semibold text-lg text-red-500">
                      2.500.000 đ
                    </h1>
                  </div>
                  <div class="flex items-center mt-1 gap-2">
                    <div class="h-4 mt-1 flex gap-1">
                      <img src="./assets/images/star-yellow.svg" />
                      <img src="./assets/images/star-yellow.svg" />
                      <img src="./assets/images/star-yellow.svg" />
                      <img src="./assets/images/star.svg" />
                      <img src="./assets/images/star.svg" />
                    </div>
                    <p class="translate-y-0.5">(30)</p>
                  </div>
                </div>
              </div>
            </li> -->
          </ul>
        </div>
      </section>
    </div>

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
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      // Kiểm tra xem có thông báo thành công không
      document.addEventListener("DOMContentLoaded", function () {
        if (<?php echo isset($_SESSION['cart_added']) ? 'true' : 'false'; ?>) {
          alert('Sản phẩm đã được thêm vào giỏ hàng!');
          <?php unset($_SESSION['cart_added']); ?>
        }
      });

      var splide = new Splide(".splide", {
        type: "fade",
        rewind: true,
      });
      splide.mount();

      document.getElementById("splide-list-similar-product").innerHTML = `
        <li class="splide__slide mx-2">
            <div class="border bg-slate-200 h-[300px]">
            <img
                class="h-2/3 w-full"
                src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTN-DR6EPNafE7pyya09WL-HpjohmnqJMUZyA&s"
            />
            <div class="px-2">
                <h1 class="text-base font-bold mt-2">USBI | Classic Charm</h1>
                <div class="mt-1">
                <h1 class="font-semibold text-lg text-red-500">
                    2.500.000 đ
                </h1>
                </div>
                <div class="flex items-center mt-1 gap-2">
                <div class="h-4 mt-1 flex gap-1">
                    <img src="./assets/images/star-yellow.svg" />
                    <img src="./assets/images/star-yellow.svg" />
                    <img src="./assets/images/star-yellow.svg" />
                    <img src="./assets/images/star.svg" />
                    <img src="./assets/images/star.svg" />
                </div>
                <p class="translate-y-0.5">(30)</p>
                </div>
            </div>
            </div>
        </li>`;

      document.addEventListener("DOMContentLoaded", function () {
        new Splide("#image-carousel", {
          type: "loop",
          perPage: 5,
          perMove: 1,
        }).mount();
      });

      document
        .getElementById("product-details-container")
        .addEventListener("click", function () {
          var productDetails = document.getElementById("product-details");
          var productDetailsIconTrigger = document.getElementById(
            "product-details-icon-trigger"
          );
          if (productDetails.classList.contains("hidden")) {
            productDetails.classList.remove("hidden");
            productDetailsIconTrigger.innerText = "-";
          } else {
            productDetails.classList.add("hidden");
            productDetailsIconTrigger.innerText = "+";
          }
        });

      document
        .getElementById("size-guide-container")
        .addEventListener("click", function () {
          var sizeGuide = document.getElementById("size-guide");
          var sizeGuideIconTrigger = document.getElementById(
            "size-guide-icon-trigger"
          );
          if (sizeGuide.classList.contains("hidden")) {
            sizeGuide.classList.remove("hidden");
            sizeGuideIconTrigger.innerText = "-";
          } else {
            sizeGuide.classList.add("hidden");
            sizeGuideIconTrigger.innerText = "+";
          }
        });

      document
        .getElementById("shipping-return-container")
        .addEventListener("click", function () {
          var shippingReturn = document.getElementById("shipping-return");
          var shippingReturnIconTrigger = document.getElementById(
            "shipping-return-icon-trigger"
          );
          if (shippingReturn.classList.contains("hidden")) {
            shippingReturn.classList.remove("hidden");
            shippingReturnIconTrigger.innerText = "-";
          } else {
            shippingReturn.classList.add("hidden");
            shippingReturnIconTrigger.innerText = "+";
          }
        });

      let quantity = 1;
      const quantityElement = document.getElementById("quantity");
      const quantityInput =document.getElementById("quantityInput");
      const decreaseButton = document.getElementById("decrease-quantity");
      const increaseButton = document.getElementById("increase-quantity");

      decreaseButton.addEventListener("click", function () {
        if (quantity > 1) {
          quantity--;
          quantityElement.textContent = quantity;
          quantityInput.value = quantity;
        }
      });

      increaseButton.addEventListener("click", function () {
        quantity++;
        quantityElement.textContent = quantity;
        quantityInput.value = quantity;
      });
    </script>
  </body>
</html>
