<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My News</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>My News</h1>
        <nav>
            <a href="#">Politics</a>
            <a href="#">Sports</a>
            <a href="#">Tech</a>
        </nav>
    </header>
    <main>
        <section id="headlines">
            <h2>Top Headlines</h2>
            <div class="articles">
                <?php
                $stmt = $conn->query("SELECT * FROM articles ORDER BY created_at DESC LIMIT 5");
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<article>
                        <h3>{$row['title']}</h3>
                        <img src='{$row['image']}' alt=''>
                        <p>{$row['description']}</p>
                        <a href='article.php?id={$row['id']}'>Read More</a>
                    </article>";
                }
                ?>
            </div>
        </section>
    </main>
</body>
</html>
