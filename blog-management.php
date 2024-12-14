<?php
session_start();
require_once "./include/db.inc.php";

// Truy vấn tất cả các bài viết của người dùng
$stmt = $pdo->prepare("SELECT id, title, timestamp FROM blog ORDER BY timestamp DESC");
$stmt->execute();

// Lấy tất cả bài viết vào một mảng
$blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>User Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
      rel="stylesheet"
    />
    <link
      href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css"
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
          <div class="flex items-center justify-between">
            <h1 class="text-3xl font-semibold mb-6">Blog</h1>
            <a href="blog-management-detail.php">
              <button
                class="w-40 h-12 bg-slate-300 font-semibold border rounded-lg"
              >
                Add new blog
              </button>
            </a>
          </div>
          <div
            class="bg-white mt-5 rounded-lg shadow-md h-[72vh] overflow-hidden"
          >
            <!-- blog list -->
            <div class="space-y-4 h-full overflow-auto">
              <?php if ($blogs): ?>
                <?php foreach ($blogs as $blog): ?>
                  <div class="border-b border-gray-300 p-4">
                    <div class="flex justify-between">
                      <h2 class="text-xl font-semibold"><?= htmlspecialchars($blog['title']) ?></h2>
                      <span class="text-sm text-gray-500"><?= date("F j, Y", strtotime($blog['timestamp'])) ?></span>
                    </div>
                    <a href="blog-management-detail.php?blogId=<?= $blog['id'] ?>" class="text-blue-500">Edit</a>
                  </div>
                <?php endforeach; ?>
              <?php else: ?>
                <p class="text-center text-gray-500">No blogs found.</p>
              <?php endif; ?>
            </div>
          </div>

        </main>
      </div>
    </div>
    <!-- Include the Quill library -->
  </body>
</html>
