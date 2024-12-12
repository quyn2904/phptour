<?php
session_start();
require_once "include/db.inc.php";

try {
  // Truy vấn thông tin sản phẩm
  $stmt = $pdo->prepare("SELECT * FROM account");
  $stmt->execute();
  $accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  echo "Lỗi khi truy vấn dữ liệu: " . $e->getMessage();
}

if (isset($_SESSION['message'])) {
  echo "<script>alert('".$_SESSION['message']."')</script>";
  unset($_SESSION['message']);
}

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
          <div class="flex items-center justify-between">
            <h1 class="text-3xl font-semibold mb-6">User</h1>
            <a href="user-management-detail.php">
              <button class="w-40 h-12 bg-slate-300 font-semibold border rounded-lg">Add new user</button>
            </a>
          </div>
          <div class="bg-white mt-5 rounded-lg shadow-md overflow-hidden">
            <table class="min-w-full bg-white">
              <thead class="bg-gray-50">
                <tr>
                  <th
                    class="py-2 px-4 border-b border-gray-200 text-left text-sm font-semibold text-gray-600"
                  >
                    ID
                  </th>
                  <th
                    class="py-2 px-4 border-b border-gray-200 text-left text-sm font-semibold text-gray-600"
                  >
                    FIRST NAME
                  </th>
                  <th
                    class="py-2 px-4 border-b border-gray-200 text-left text-sm font-semibold text-gray-600"
                  >
                    LAST NAME
                  </th>
                  <th
                    class="py-2 px-4 border-b border-gray-200 text-left text-sm font-semibold text-gray-600"
                  >
                    EMAIL
                  </th>
                  <th
                    class="py-2 px-4 border-b border-gray-200 text-left text-sm font-semibold text-gray-600"
                  >
                    ROLE
                  </th>
                  <th
                    class="py-2 px-4 border-b border-gray-200 text-left text-sm font-semibold text-gray-600"
                  >
                    ACTION
                  </th>
                </tr>
              </thead>
              <tbody>
                <?php if ($accounts): ?>
                  <?php foreach($accounts as $account): ?>
                    <tr>
                      <td
                        class="py-2 px-4 border-b border-gray-200 "
                      >
                        <p><?= $account['id'] ?></p>
                      </td>
                      <td
                        class="py-2 px-4 border-b border-gray-200 "
                      >
                        <p><?= $account['firstname'] ?></p>
                      </td>
                      <td class="py-2 px-4 border-b border-gray-200 text-sm">
                      <p><?= $account['lastname'] ?></p>
                      </td>
                      <td class="py-2 px-4 border-b border-gray-200 text-sm">
                      <p><?= $account['email'] ?></p>
                      </td>
                      <td class="py-2 px-4 border-b border-gray-200 text-sm">
                      <p><?= $account['role'] ?></p>
                      </td>
                      <td
                        class="py-2 px-4 border-b border-gray-200 text-sm text-blue-500 cursor-pointer"
                      >
                      <a href="user-management-detail.php?account_id=<?= $account['id'] ?>">
                          <button   
                            class="rounded-md bg-slate-800 py-2 px-4 border border-transparent text-center text-sm text-white transition-all shadow-md hover:shadow-lg focus:bg-slate-700 focus:shadow-none active:bg-slate-700 hover:bg-slate-700 active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none ml-2" type="button">
                            Edit
                          </button>
                      </a>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </main>
      </div>
    </div>
    <script>
    </script>
  </body>
</html>
