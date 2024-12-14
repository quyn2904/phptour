<?php
  session_start();
  require_once "include/db.inc.php";

  // Kiểm tra nếu có blogId trong URL
  if (isset($_GET['blogId'])) {
    $blogId = (int)$_GET['blogId'];

    // Truy vấn cơ sở dữ liệu để lấy thông tin bài viết, bao gồm ngày đăng
    $sql = "SELECT * FROM blog WHERE id = :blogId";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':blogId', $blogId, PDO::PARAM_INT);
    $stmt->execute();
    
    // Kiểm tra nếu có kết quả trả về
    if ($stmt->rowCount() > 0) {
      $blog = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
      echo "Bài viết không tồn tại.";
      exit;
    }
  } else {
    echo "Không có blogId trong URL.";
    exit;
  }

  // Chuyển đổi ngày đăng thành định dạng dễ đọc
  $createdAt = new DateTime($blog['timestamp']);
  $formattedDate = $createdAt->format('d-m-Y H:i:s');
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Chi tiết Bài Viết</title>
    <link
      href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css"
      rel="stylesheet"
    />
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
          <img src="{search}" class="h-full w-auto" />
        </button>
      </div>
      <div class="flex items-center gap-4">
        <button class="rounded-lg border bg-blue-400 px-6 py-2 font-bold">
          Login
        </button>
        <button class="rounded-lg border bg-green-400 px-6 py-2 font-bold">
          Register
        </button>
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

    <!-- Render Blog Detail -->
    <div class="px-20 py-10">
      <h2 class="text-3xl font-bold text-[#CE112D]"><?= $blog['title']; ?></h2>
      <div class="mt-2 text-sm italic text-gray-500">
        <strong>Ngày đăng: </strong><?= $formattedDate; ?>
      </div>
      <div class="mt-6">
        <?= $blog['content']; ?>
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
  </body>
</html>
