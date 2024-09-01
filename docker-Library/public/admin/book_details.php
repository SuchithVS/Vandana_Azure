<?php
require_once 'config.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];
$sql = "SELECT * FROM books WHERE id = ?";
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    header("Location: index.php");
    exit();
}

$book = mysqli_fetch_assoc($result);

if (isset($_POST['borrow'])) {
    $update_sql = "UPDATE books SET status = 'borrowed' WHERE id = ?";
    $update_stmt = mysqli_prepare($link, $update_sql);
    mysqli_stmt_bind_param($update_stmt, "i", $id);
    mysqli_stmt_execute($update_stmt);
    $book['status'] = 'borrowed';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($book['title']); ?> - Library Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
            color: #333;
        }
        header {
            background-color: #28a745;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 1.5rem;
            font-weight: bold;
            border-bottom: 5px solid #218838;
        }
        .book-details {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 30px;
            margin-top: 30px;
        }
        .book-image {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
        }
        .btn-borrow {
            background-color: #28a745;
            border-color: #28a745;
        }
        .btn-borrow:hover {
            background-color: #218838;
            border-color: #218838;
        }
    </style>
</head>
<body>
    <header>
        Library Management System
    </header>
    <div class="container mt-5">
        <div class="book-details">
            <div class="row">
                <div class="col-md-4">
                    <?php if (!empty($book['image_url'])): ?>
                        <img src="<?php echo htmlspecialchars($book['image_url']); ?>" class="book-image" alt="<?php echo htmlspecialchars($book['title']); ?>">
                    <?php else: ?>
                        <img src="https://via.placeholder.com/300x400?text=No+Image" class="book-image" alt="No image available">
                    <?php endif; ?>
                </div>
                <div class="col-md-8">
                    <h2><?php echo htmlspecialchars($book['title']); ?></h2>
                    <p><strong>Author:</strong> <?php echo htmlspecialchars($book['author']); ?></p>
                    <p><strong>Genre:</strong> <?php echo htmlspecialchars($book['genre']); ?></p>
                    <p><strong>Published Year:</strong> <?php echo htmlspecialchars($book['published_year']); ?></p>
                    <p><strong>ISBN:</strong> <?php echo htmlspecialchars($book['isbn']); ?></p>
                    <p><strong>Status:</strong> <?php echo htmlspecialchars($book['status']); ?></p>
                    
                    <?php if ($book['status'] == 'available'): ?>
                        <form method="POST">
                            <button type="submit" name="borrow" class="btn btn-borrow">Borrow this Book</button>
                        </form>
                    <?php else: ?>
                        <p class="text-muted">This book is currently borrowed.</p>
                    <?php endif; ?>
                    
                    <a href="index.php" class="btn btn-secondary mt-3">Back to Book List</a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>