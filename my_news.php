<?php
// Database connection include karein
include 'db.php';

// Function to fetch articles
function fetchArticles() {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT * FROM articles ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error fetching articles: " . $e->getMessage();
        return [];
    }
}

// Fetch articles
$articles = fetchArticles();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My News</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #444;
        }

        .article {
            border-bottom: 1px solid #ddd;
            padding: 15px;
        }

        .article h2 {
            margin: 0;
            color: #007bff;
        }

        .article img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
            margin-top: 10px;
        }

        .article p {
            color: #666;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Latest News</h1>

    <?php if (!empty($articles)): ?>
        <?php foreach ($articles as $article): ?>
            <div class="article">
                <h2><?= htmlspecialchars($article['title']) ?></h2>
                <?php if (!empty($article['image'])): ?>
                    <img src="<?= htmlspecialchars($article['image']) ?>" alt="Article Image">
                <?php endif; ?>
                <p><?= nl2br(htmlspecialchars($article['content'])) ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No news articles available.</p>
    <?php endif; ?>
</div>

</body>
</html>
