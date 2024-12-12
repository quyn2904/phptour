<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    try {
        require_once "db.inc.php";
        $query = "SELECT * FROM account WHERE email = :email;";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            if ($password == $result["password"]) {
                $_SESSION["user_id"] = $result["id"];
                $_SESSION["user_name"] = $result["firstname"] . " " . $result["lastname"];
                if ($result['role'] == 'admin') {
                    header("Location: ../user-management.php");
                } else {
                    header("Location: ../index.php");
                }
                exit();
            } else {
                die("Invalid password.");
            }
        } else {
            die("Account not found.");
        }
    } catch (Exception $e) {
        die("Query failed: " . $e->getMessage());
    } finally {
        $pdo = null;
        $stmt = null;
    }
} else {
    header("Location: ../index.php");
    die();
}
?>