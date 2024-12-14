<?php
session_start();
require_once "db.inc.php";

// Kiểm tra nếu dữ liệu được gửi qua POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Kiểm tra nếu có dữ liệu title, content và blogId (optional)
    if (isset($_POST['title']) && isset($_POST['content'])) {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $coverImgPath = $_POST['coverImg'] ?? null; // Lấy đường dẫn cover image
        $accountId = $_SESSION['user_id'] ?? 0;
        $blogId = $_POST['blogId'] ?? null; // Lấy blogId nếu có

        try {
            // Nếu có blogId, thực hiện cập nhật bài blog
            if ($blogId) {
                // Cập nhật bài blog
                $stmt = $pdo->prepare("UPDATE blog SET title = :title, content = :content, timestamp = NOW() WHERE id = :blogId AND account_id = :account_id");
                $stmt->bindParam(':blogId', $blogId, PDO::PARAM_INT);
                $stmt->bindParam(':title', $title);
                $stmt->bindParam(':content', $content);
                $stmt->bindParam(':account_id', $accountId);
                $stmt->execute();
            
                // Kiểm tra xem blog đã có ảnh hay chưa
                if ($coverImgPath) {
                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM image WHERE blog_id = :blogId");
                    $stmt->bindParam(':blogId', $blogId, PDO::PARAM_INT);
                    $stmt->execute();
                    $imageExists = $stmt->fetchColumn(); // Trả về số lượng bản ghi
            
                    if ($imageExists) {
                        // Nếu đã có ảnh, thực hiện UPDATE
                        $stmt = $pdo->prepare(query: "UPDATE image SET path = :coverImg WHERE blog_id = :blogId");
                        $stmt->bindParam(':coverImg', $coverImgPath);
                        $stmt->bindParam(':blogId', $blogId, PDO::PARAM_INT);
                        $stmt->execute();
                    } else {
                        // Nếu chưa có ảnh, thực hiện INSERT
                        $stmt = $pdo->prepare("INSERT INTO image (path, blog_id) VALUES (:coverImg, :blogId)");
                        $stmt->bindParam(':coverImg', $coverImgPath);
                        $stmt->bindParam(':blogId', $blogId, PDO::PARAM_INT);
                        $stmt->execute();
                    }
                }
            
                echo "Blog updated successfully!";
            } else {
                // Nếu không có blogId, tạo mới bài blog
                $stmt = $pdo->prepare("INSERT INTO blog (title, content, timestamp, account_id) VALUES (:title, :content, NOW(), :account_id)");
                $stmt->bindParam(':title', $title);
                $stmt->bindParam(':content', $content);
                $stmt->bindParam(':account_id', $accountId);
                $stmt->execute();
                
                // Lấy ID của blog mới tạo
                $blogId = $pdo->lastInsertId();

                // Lưu ảnh vào bảng image nếu có đường dẫn ảnh
                if ($coverImgPath) {
                    $stmt = $pdo->prepare("INSERT INTO image (path, blog_id) VALUES (:coverImg, :blogId)");
                    $stmt->bindParam(':coverImg', $coverImgPath);
                    $stmt->bindParam(':blogId', $blogId, PDO::PARAM_INT);
                    $stmt->execute();
                }

                echo "Blog created successfully!";
            }

        } catch (PDOException $e) {
            // Trả về thông báo lỗi nếu gặp sự cố
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Title and content are required!";
    }
} else {
    echo "Invalid request method!";
}
?>
