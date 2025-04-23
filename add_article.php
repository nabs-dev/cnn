<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin'])) {
    header('Location: admin.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $image = $_POST['image'];
    $content = $_POST['content'];

    $stmt = $conn->prepare("INSERT INTO articles (title, description, content, image) VALUES (?, ?, ?, ?)");
    $stmt->execute([$title, substr($content, 0, 100), $content, $image]);

    header('Location: admin_dashboard.php');
    exit();
}
?>
