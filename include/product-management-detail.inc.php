<?php
require_once 'db.inc.php';  // Kết nối cơ sở dữ liệu

// Kiểm tra xem có dữ liệu từ form gửi lên không
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy dữ liệu từ form
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $category_id = $_POST['category']; // Lấy category_id từ form
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $image = $_POST['image'];

    try {
        // Kiểm tra xem category_id có tồn tại trong bảng category không
        $stmtCategory = $pdo->prepare("SELECT id FROM category WHERE id = :category_id");
        $stmtCategory->bindParam(':category_id', $category_id);
        $stmtCategory->execute();
        $category = $stmtCategory->fetch(PDO::FETCH_ASSOC);

        // Nếu category_id không tồn tại trong bảng category, dừng lại và thông báo lỗi
        if (!$category) {
            echo "Danh mục không tồn tại trong cơ sở dữ liệu.";
            exit;
        }

        // Cập nhật thông tin sản phẩm trong bảng product
        if ($id) {
            // Nếu đã có id, tức là đang cập nhật sản phẩm
            $stmt = $pdo->prepare("UPDATE product SET name = :name, description = :description, category_id = :category_id, quantity = :quantity WHERE id = :id");
            $stmt->bindParam(':category_id', $category_id);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':quantity', $quantity);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        } else {
            // Nếu chưa có id, tạo mới sản phẩm
            $stmt = $pdo->prepare("INSERT INTO product (name, description, quantity, category_id) VALUES (:name, :description, :quantity, :category_id)");
            $stmt->bindParam(':category_id', $category_id);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':quantity', $quantity);
            $stmt->execute();
            // Lấy lại id của sản phẩm vừa tạo
            $id = $pdo->lastInsertId();  // Lấy id của sản phẩm vừa được tạo
        }

        // Cập nhật giá sản phẩm vào bảng productprice
        $stmtPrice = $pdo->prepare("SELECT * FROM productprice WHERE product_id = :product_id");
        $stmtPrice->bindParam(':product_id', $id);
        $stmtPrice->execute();
        $existingPrice = $stmtPrice->fetch(PDO::FETCH_ASSOC);

        if ($existingPrice) {
            // Nếu giá đã tồn tại, cập nhật giá mới
            $stmtUpdatePrice = $pdo->prepare("UPDATE productprice SET price = :price WHERE product_id = :product_id");
            $stmtUpdatePrice->bindParam(':price', $price);
            $stmtUpdatePrice->bindParam(':product_id', $id);
            $stmtUpdatePrice->execute();
        } else {
            // Nếu giá chưa tồn tại, thêm mới giá
            $stmtInsertPrice = $pdo->prepare("INSERT INTO productprice (product_id, price) VALUES (:product_id, :price)");
            $stmtInsertPrice->bindParam(':price', $price);
            $stmtInsertPrice->bindParam(':product_id', $id);
            $stmtInsertPrice->execute();
        }

        // Cập nhật hình ảnh cho sản phẩm
        if ($image) {
            // Nếu có ảnh mới, xóa tất cả ảnh cũ của sản phẩm này
            $stmtDeleteImages = $pdo->prepare("DELETE FROM image WHERE product_id = :product_id");
            $stmtDeleteImages->bindParam(':product_id', $id);
            $stmtDeleteImages->execute();

            // Thêm ảnh mới vào bảng image
            $stmtInsertImage = $pdo->prepare("INSERT INTO image (product_id, image_path) VALUES (:product_id, :image_path)");
            $stmtInsertImage->bindParam(':product_id', $id);
            $stmtInsertImage->bindParam(':image_path', $image);
            $stmtInsertImage->execute();
        }

        // Sau khi hoàn thành, chuyển hướng về trang quản lý sản phẩm (hoặc trang nào đó)
        header("Location: ../product-management.php");
        $_SESSION['message'] = "Cập nhật sản phẩm thành công.";
        exit;

    } catch (PDOException $e) {
        echo "Lỗi khi xử lý dữ liệu: " . $e->getMessage();
        exit;
    }
}
?>
