<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start session and check admin authentication
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: admin.php'); // Redirect to login page if not logged in
    exit();
}

// Include the database connection file
include 'db.php';

// Function to fetch all articles from the database
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

// Handle logout request
if (isset($_GET['logout'])) {
    session_destroy(); // Destroy the session
    header('Location: admin.php'); // Redirect to login page
    exit();
}

// Handle new article submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $imagePath = '';

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        $imageName = basename($_FILES['image']['name']);
        $imagePath = $uploadDir . $imageName;

        // Move the uploaded file to the upload directory
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
            echo "Failed to upload image.";
            $imagePath = ''; // Reset the image path if upload fails
        }
    }

    // Insert the article into the database
    try {
        $stmt = $conn->prepare("INSERT INTO articles (title, content, image) VALUES (?, ?, ?)");
        $stmt->execute([$title, $content, $imagePath]);
        header('Location: admin_dashboard.php'); // Refresh the page after submission
        exit();
    } catch (PDOException $e) {
        echo "Error adding article: " . $e->getMessage();
    }
}

// Fetch articles to display
$articles = fetchArticles();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }

        header {
            background-color: #007bff;
            color: #fff;
            padding: 20px;
            text-align: center;
            position: sticky;
            top: 0;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            margin: 0;
            font-size: 24px;
        }

        header a {
            color: #fff;
            text-decoration: none;
            font-size: 16px;
            position: absolute;
            top: 25px;
            right: 20px;
        }

        header a:hover {
            text-decoration: underline;
        }

        main {
            padding: 20px;
            max-width: 800px;
            margin: 20px auto;
        }

        section {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        section h2 {
            font-size: 20px;
            margin-bottom: 15px;
            color: #444;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
            font-size: 14px;
        }

        table th {
            background-color: #f4f4f9;
            font-weight: bold;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        table a {
            color: #007bff;
            text-decoration: none;
        }

        table a:hover {
            text-decoration: underline;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        form input[type="text"], form textarea, form input[type="file"] {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        form button {
            padding: 10px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form button:hover {
            background-color: #218838;
        }

        img {
            max-width: 100px;
            height: auto;
        }
    </style>
</head>
<body>
    <header>
        <h1>Admin Dashboard</h1>
        <a href="?logout=true">Logout</a>
    </header>
    <main>
        <!-- Section for managing articles -->
        <section>
            <h2>Manage Articles</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($articles)): ?>
                        <?php foreach ($articles as $article): ?>
                            <tr>
                                <td><?= $article['id'] ?></td>
                                <td><?= htmlspecialchars($article['title']) ?></td>
                                <td>
                                    <?php if (!empty($article['image'])): ?>
                                        <img src="<?= htmlspecialchars($article['image']) ?>" alt="Article Image">
                                    <?php else: ?>
                                        No Image
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="edit_article.php?id=<?= $article['id'] ?>">Edit</a> |
                                    <a href="delete_article.php?id=<?= $article['id'] ?>" onclick="return confirm('Are you sure you want to delete this article?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">No articles found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>

        <!-- Section for adding new articles -->
        <section>
            <h2>Add New Article</h2>
            <form action="admin_dashboard.php" method="POST" enctype="multipart/form-data">
                <input type="text" name="title" placeholder="Title" required>
                <textarea name="content" placeholder="Content" required></textarea>
                <input type="file" name="image" accept="image/*" required>
                <button type="submit">Add Article</button>
            </form>
        </section>
    </main>
</body>
</html>
