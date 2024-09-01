<?php
require_once '../config.php';

session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $genre = $_POST['genre'];
    $published_year = $_POST['published_year'];
    $isbn = $_POST['isbn'];
    $image_url = $_POST['image_url'];
    $status = $_POST['status'];

    $sql = "INSERT INTO books (title, author, genre, published_year, isbn, image_url, status) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "sssssss", $title, $author, $genre, $published_year, $isbn, $image_url, $status);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "ERROR: Could not execute query. " . mysqli_error($link);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Book</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/styles.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Add New Book</h1>
        <form method="POST" action="add_book.php">
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="mb-3">
                <label for="author" class="form-label">Author</label>
                <input type="text" class="form-control" id="author" name="author" required>
            </div>
            <div class="mb-3">
                <label for="genre" class="form-label">Genre</label>
                <input type="text" class="form-control" id="genre" name="genre">
            </div>
            <div class="mb-3">
                <label for="published_year" class="form-label">Published Year</label>
                <input type="number" class="form-control" id="published_year" name="published_year">
            </div>
            <div class="mb-3">
                <label for="isbn" class="form-label">ISBN</label>
                <input type="text" class="form-control" id="isbn" name="isbn">
            </div>
            <div class="mb-3">
                <label for="image_url" class="form-label">Image URL</label>
                <input type="text" class="form-control" id="image_url" name="image_url" required>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="available">Available</option>
                    <option value="borrowed">Borrowed</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Add Book</button>
        </form>
        <a href="dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>