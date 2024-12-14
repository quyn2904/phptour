<?php
  session_start();
  require_once "include/db.inc.php";

  // Get the current page from the query string (default to page 1 if not present)
  $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
  $perPage = 8; // Number of blogs per page
  $offset = ($page - 1) * $perPage;

  // Prepare the SQL query to fetch the blogs with images using LEFT JOIN
  $sql = "
      SELECT blog.*, image.path AS image_path
      FROM blog
      LEFT JOIN image ON blog.id = image.blog_id
      LIMIT :offset, :perPage
  ";
  $stmt = $pdo->prepare($sql);

  // Bind the offset and perPage values
  $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
  $stmt->bindParam(':perPage', $perPage, PDO::PARAM_INT);

  // Execute the query
  $stmt->execute();
  $blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // Get the total number of blogs for pagination logic
  $totalBlogsSql = "SELECT COUNT(*) FROM blog";
  $totalBlogsStmt = $pdo->query($totalBlogsSql);
  $totalBlogs = $totalBlogsStmt->fetchColumn();

  // Calculate total pages
  $totalPages = ceil($totalBlogs / $perPage);

  // Pass the blogs and total pages to the view
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Blogs with Pagination</title>
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
    <!-- header -->

    <div class="">
      <div class="bg-[#D9D9D9] grid h-[520px] grid-cols-7">
        <div class="col-span-3 flex items-center mx-auto">
          <div class="h-[440px] w-[480px]">
            <img class="h-full w-full object-fill" src="./assets/blogs/1.png" />
          </div>
        </div>
        <div class="col-span-4 flex flex-col items-center justify-center">
          <p class="text-3xl">Tỏa sáng cùng Usbibracelet</p>
          <div class="mt-5 text-center">
            <p class="text-6xl mt-1 font-bold text-red-500">
              "THE LOVE OF MINE"
            </p>
            <p class="text-5xl mt-1 font-bold text-red-500">
              QÙA TẶNG Ý NGHĨA CHO
            </p>
            <p class="text-5xl mt-1 font-bold text-red-500">
              NGÀY QUỐC TẾ NAM GIỚI
            </p>
          </div>
          <div class="mt-5 w-[90%]">
            <p class="text-lg">
              Vào ngày 19 tháng 11, Ngày Quốc tế Nam giới được tôn vinh trên
              toàn cầu: khám phá những món trang sức ý nghĩa dành cho anh ấy để
              làm quà tặng trong ngày đặc biệt này.
            </p>
          </div>
          <p class="text-6xl text-red-500">→</p>
        </div>
      </div>
      <div>
        <div class="mt-10 grid grid-cols-4 gap-10 px-20">

          <?php foreach ($blogs as $blog): ?>
            <div class="h-80 border rounded-lg gap-5">
              <div class="h-3/4">
                <?php if (!empty($blog['image_path'])): ?>
                  <img src="<?= $blog['image_path']; ?>" alt="Blog Image" class="h-full w-full object-cover" />
                <?php else: ?>
                  <div class="h-full w-full bg-gray-200 flex items-center justify-center">
                    <span>No Image</span>
                  </div>
                <?php endif; ?>
              </div>
              <div class="mt-2 px-3 items-center justify-center">
                <p>Tỏa sáng cùng Usbibracelet</p>
                <a href="blog-detail.php?blogId=<?= $blog['id'] ?>" class="text-red-500 font-bold text-lg">
                  <?= htmlspecialchars($blog['title']); ?>
                </a>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <div class="pagination mx-auto w-96 flex items-center justify-between gap-3">
            <div class="flex gap-3">
              <a href="?page=1" class="px-2 py-1 rounded bg-slate-200 pagination-btn <?= ($page == 1) ? 'disabled' : ''; ?>">First</a>
              <a href="?page=<?= max(1, $page - 1); ?>" class="px-2 py-1 rounded bg-slate-200 pagination-btn <?= ($page == 1) ? 'disabled' : ''; ?>">Previous</a>
            </div>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?= $i; ?>" class="pagination-btn <?= ($i == $page) ? 'active' : ''; ?>"><?= $i; ?></a>
            <?php endfor; ?>
            <div class="flex gap-3">
              <a href="?page=<?= min($totalPages, $page + 1); ?>" class="px-2 py-1 rounded bg-slate-200 pagination-btn <?= ($page == $totalPages) ? 'disabled' : ''; ?>">Next</a>
              <a href="?page=<?= $totalPages; ?>" class="px-2 py-1 rounded bg-slate-200 pagination-btn <?= ($page == $totalPages) ? 'disabled' : ''; ?>">Last</a>
            </div>
        </div>
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
    <script></script>
  </body>
</html>
