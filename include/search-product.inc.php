<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $searchTerm = $_POST["searchTerm"];
    if (isset($searchTerm))
    header("Location: ../product-list.php?searchTerm=".$searchTerm);
} else {
    header("Location: ../index.php");
    die();
}
?>