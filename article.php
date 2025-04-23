<?php include 'db.php'; ?>
<?php
$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM articles WHERE id = ?");
$stmt->execute([$id]);
$article = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $article['title']; ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>My News</h1>
    </header>
    <main>
        <article>
            <h2><?php echo $article['title']; ?></h2>
            <img src="<?php echo $article['image']; ?>" alt="">
            <p><?php echo $article['content']; ?></p>
        </article>
    </main>
</body>
</html>
