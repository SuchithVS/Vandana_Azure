<?php
require_once '../config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM books WHERE id = $id";
    $result = mysqli_query($link, $query);

    if ($result && mysqli_num_rows($result) == 1) {
        $book = mysqli_fetch_assoc($result);
    } else {
        die("Book not found.");
    }
} else {
    die("Invalid request.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Book Details</h1>
        <p><strong>Title:</strong> <?php echo htmlspecialchars($book['title']); ?></p>
        <p><strong>Author:</strong> <?php echo htmlspecialchars($book['author']); ?></p>
        <p><strong>Genre:</strong> <?php echo htmlspecialchars($book['genre']); ?></p>
        <p><strong>Published Year:</strong> <?php echo htmlspecialchars($book['published_year']); ?></p>
        <p><strong>Status:</strong> <?php echo htmlspecialchars($book['status']); ?></p>
        <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
