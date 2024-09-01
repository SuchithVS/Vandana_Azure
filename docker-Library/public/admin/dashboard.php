<?php
require_once '../config.php';

session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit();
}

$sort = isset($_GET['sort']) ? $_GET['sort'] : 'title';
$order = isset($_GET['order']) ? $_GET['order'] : 'ASC';
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';

$sql = "SELECT * FROM books WHERE title LIKE '%$filter%' OR author LIKE '%$filter%' OR genre LIKE '%$filter%' ORDER BY $sort $order";
$result = mysqli_query($link, $sql);

$books = [];
while ($book = mysqli_fetch_assoc($result)) {
    $books[] = $book;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/styles.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Library Management System - Admin Dashboard</h1>

        <div class="mb-4">
            <a href="add_book.php" class="btn btn-primary">Add New Book</a>
            <a href="logout.php" class="btn btn-secondary">Logout</a>
        </div>

        <form method="GET" action="dashboard.php" class="mb-4">
            <input type="text" name="filter" class="form-control" placeholder="Search by title, author, genre..." value="<?php echo htmlspecialchars($filter); ?>">
            <button type="submit" class="btn btn-info mt-2">Search</button>
        </form>

        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Image</th>
                    <th><a href="dashboard.php?sort=title&order=<?php echo $order === 'ASC' ? 'DESC' : 'ASC'; ?>">Title</a></th>
                    <th><a href="dashboard.php?sort=author&order=<?php echo $order === 'ASC' ? 'DESC' : 'ASC'; ?>">Author</a></th>
                    <th><a href="dashboard.php?sort=genre&order=<?php echo $order === 'ASC' ? 'DESC' : 'ASC'; ?>">Genre</a></th>
                    <th><a href="dashboard.php?sort=published_year&order=<?php echo $order === 'ASC' ? 'DESC' : 'ASC'; ?>">Published Year</a></th>
                    <th><a href="dashboard.php?sort=status&order=<?php echo $order === 'ASC' ? 'DESC' : 'ASC'; ?>">Status</a></th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($books as $book): ?>
                <tr>
                    <td><img src="<?php echo htmlspecialchars($book['image_url']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>" style="width: 50px; height: 75px;"></td>
                    <td><?php echo htmlspecialchars($book['title']); ?></td>
                    <td><?php echo htmlspecialchars($book['author']); ?></td>
                    <td><?php echo htmlspecialchars($book['genre']); ?></td>
                    <td><?php echo htmlspecialchars($book['published_year']); ?></td>
                    <td><?php echo htmlspecialchars($book['status']); ?></td>
                    <td>
                        <a href="edit_book.php?id=<?php echo $book['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="delete_book.php?id=<?php echo $book['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this book?');">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>