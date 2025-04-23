<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin'])) {
    header('Location: admin.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM articles WHERE id = ?");
    $stmt->execute([$id]);

    header('Location: admin_dashboard.php');
    exit();
}
?>
