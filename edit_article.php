<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin'])) {
    header('Location: admin.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch article data
    $stmt = $conn->prepare("SELECT * FROM articles WHERE id = ?");
    $stmt->execute([$id]);
    $article = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $title = $_POST['title'];
        $image = $_POST['image'];
        $content = $_POST['content'];

        $stmt = $conn->prepare("UPDATE articles SET title = ?, description = ?, content = ?, image = ? WHERE id = ?");
        $stmt->execute([$title, substr($content, 0, 100), $content, $image, $id]);

        header('Location: admin_dashboard.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Article</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Edit Article</h1>
    <form method="POST">
        <input type="text" name="title" value="<?= htmlspecialchars($article['title']) ?>" required>
        <input type="text" name="image" value="<?= htmlspecialchars($article['image']) ?>" required>
        <textarea name="content" required><?= htmlspecialchars($article['content']) ?></textarea>
        <button type="submit">Save Changes</button>
    </form>
</body>
</html>
