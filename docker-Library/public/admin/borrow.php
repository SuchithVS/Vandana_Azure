<?php
require_once '../config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM books WHERE id = $id";
    $result = mysqli_query($link, $query);

    if ($result && mysqli_num_rows($result) == 1) {
        $book = mysqli_fetch_assoc($result);

        if ($book['status'] === 'available') {
            // Update the book's status to borrowed
            $updateQuery = "UPDATE books SET status = 'borrowed' WHERE id = $id";
            if (mysqli_query($link, $updateQuery)) {
                echo "Book borrowed successfully!";
            } else {
                echo "Error: Could not borrow the book. " . mysqli_error($link);
            }
        } else {
            echo "Book is already borrowed.";
        }
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
    <title>Borrow Book</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Borrow Book</h1>
        <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>