<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $email = $_POST["email"];
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $role = $_POST["role"];
    require_once "db.inc.php";

    if ($role == "") {
        $role = "user";
    } else if ($role == "admin") {
        $role = "admin";
    }   else if ($role == "user") {
        $role = "user";
    }
    try {
        if (!$id) {
            // create
            $insertAccount = "INSERT INTO account (email, firstname, lastname, role) VALUES (:email, :firstname, :lastname, :role);";
            $stmtAccount = $pdo->prepare($insertAccount);
            $stmtAccount->bindParam(":email", $email);
            $stmtAccount->bindParam(":firstname", $firstname);
            $stmtAccount->bindParam(":lastname", $lastname);
            $stmtAccount->bindParam(":role", $role);
            $stmtAccount->execute();
        } else {
            // update
            $updateAccount = "UPDATE account SET firstname = :firstname, lastname = :lastname, role = :role WHERE id = :id;";
            $stmtAccount = $pdo->prepare($updateAccount);
            $stmtAccount->bindParam(":id", $id);
            $stmtAccount->bindParam(":firstname", $firstname);
            $stmtAccount->bindParam(":lastname", $lastname);
            $stmtAccount->bindParam(":role", $role);
            $stmtAccount->execute();
        }
        header("Location: ../user-management.php");
        $_SESSION["message"] = "Account saved successfully!";
        die();
    } catch (Exception $e) {
        die("Query failed: " . $e->getMessage());
    } finally {
        $pdo = null;
        $stmtAccount = null;
    }
} else {
    header("Location: ../index.php");
    die();
}

?>