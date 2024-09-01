<?php
require_once 'config.php';

$search = isset($_GET['search']) ? mysqli_real_escape_string($link, $_GET['search']) : '';
$genre = isset($_GET['genre']) ? mysqli_real_escape_string($link, $_GET['genre']) : '';
$status = isset($_GET['status']) ? mysqli_real_escape_string($link, $_GET['status']) : '';

$sql = "SELECT * FROM books WHERE 
        (title LIKE '%$search%' OR author LIKE '%$search%') 
        AND (genre = '$genre' OR '$genre' = '') 
        AND (status = '$status' OR '$status' = '') 
        ORDER BY title";

$result = mysqli_query($link, $sql);

if ($result === false) {
    die("ERROR: Could not execute $sql. " . mysqli_error($link));
}

// Get unique genres for the filter
$genre_sql = "SELECT DISTINCT genre FROM books ORDER BY genre";
$genre_result = mysqli_query($link, $genre_sql);
$genres = [];
while ($row = mysqli_fetch_assoc($genre_result)) {
    $genres[] = $row['genre'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Arial', sans-serif;
            color: #333;
        }
        header {
            background-color: #4a934a;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 1.8rem;
            font-weight: bold;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            color: #4a934a;
            font-weight: bold;
            text-align: center;
            margin: 30px 0;
            font-size: 2.5rem;
        }
        .book-card {
            height: 100%;
            border: none;
            border-radius: 15px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background: #ffffff;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .book-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
        }
        .book-image-container {
            height: 300px;
            overflow: hidden;
        }
        .book-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        .book-card:hover .book-image {
            transform: scale(1.05);
        }
        .card-body {
            padding: 20px;
        }
        .card-title {
            font-size: 1.3rem;
            font-weight: bold;
            color: #4a934a;
            margin-bottom: 10px;
        }
        .card-text {
            color: #555;
            font-size: 0.9rem;
            margin-bottom: 5px;
        }
        .admin-access {
            margin-top: 40px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .admin-access a {
            color: #4a934a;
            font-weight: bold;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        .admin-access a:hover {
            color: #3a733a;
        }
        footer {
            margin-top: 50px;
            padding: 20px 0;
            background-color: #4a934a;
            color: #ffffff;
            text-align: center;
            font-size: 0.9rem;
        }
        .search-container {
            background-color: #ffffff;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .btn-search {
            background-color: #4a934a;
            color: white;
        }
        .btn-search:hover {
            background-color: #3a733a;
            color: white;
        }

        /* Floating Action Button */
        .chatbot-fab {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #4a934a;
            color: white;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease;
        }
        .chatbot-fab:hover {
            background-color: #3a733a;
        }

        /* Chatbot Dialog Box */
        .chatbot-dialog {
            position: fixed;
            bottom: 90px;
            right: 20px;
            width: 300px;
            max-height: 400px;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: none;
            flex-direction: column;
            overflow: hidden;
        }
        .chatbot-header {
            background-color: #4a934a;
            color: white;
            padding: 10px;
            text-align: center;
            font-weight: bold;
        }
        .chatbot-body {
            padding: 10px;
            flex: 1;
            overflow-y: auto;
        }
        .chatbot-footer {
            padding: 10px;
            border-top: 1px solid #ddd;
        }
        .chatbot-footer input {
            width: 100%;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>
    <header>
        Library Management System
    </header>
    <div class="container mt-5">
        <h1>Our Book Collection</h1>
        
        <div class="search-container">
            <form action="" method="GET" class="row g-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" id="search" name="search" placeholder="Search by title or author" value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="genre" name="genre">
                        <option value="">All Genres</option>
                        <?php foreach ($genres as $g): ?>
                            <option value="<?php echo htmlspecialchars($g); ?>" <?php echo $g === $genre ? 'selected' : ''; ?>><?php echo htmlspecialchars($g); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="status" name="status">
                        <option value="">All Statuses</option>
                        <option value="available" <?php echo $status === 'available' ? 'selected' : ''; ?>>Available</option>
                        <option value="borrowed" <?php echo $status === 'borrowed' ? 'selected' : ''; ?>>Borrowed</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-search w-100"><i class="fas fa-search"></i> Search</button>
                </div>
            </form>
        </div>

        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php
            if (mysqli_num_rows($result) > 0) {
                while($book = mysqli_fetch_assoc($result)) {
            ?>
                <div class="col">
                    <div class="card book-card">
                        <div class="book-image-container">
                            <?php if (!empty($book['image_url'])): ?>
                                <img src="<?php echo htmlspecialchars($book['image_url']); ?>" class="book-image" alt="<?php echo htmlspecialchars($book['title']); ?>">
                            <?php else: ?>
                                <img src="https://via.placeholder.com/300x400?text=No+Image" class="book-image" alt="No image available">
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($book['title']); ?></h5>
                            <p class="card-text"><i class="fas fa-user"></i> Author: <?php echo htmlspecialchars($book['author']); ?></p>
                            <p class="card-text"><i class="fas fa-book"></i> Genre: <?php echo htmlspecialchars($book['genre']); ?></p>
                            <p class="card-text"><i class="fas fa-calendar-alt"></i> Published: <?php echo htmlspecialchars($book['published_year']); ?></p>
                            <p class="card-text">
                                <i class="fas fa-circle <?php echo $book['status'] === 'available' ? 'text-success' : 'text-danger'; ?>"></i> 
                                Status: <?php echo ucfirst(htmlspecialchars($book['status'])); ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php
                }
            } else {
                echo "<p class='text-center'>No books found matching your criteria.</p>";
            }
            ?>
        </div>
        <div class="admin-access mt-5">
            <a href="admin/"><i class="fas fa-lock"></i> Admin Access</a>
        </div>
    </div>
    <footer>
        &copy; 2024 Library Management System. All rights reserved.
    </footer>

    <!-- Floating Action Button -->
    <div class="chatbot-fab" id="chatbotFab">
        <i class="fas fa-comments"></i>
    </div>

    <!-- Chatbot Dialog Box -->
    <div class="chatbot-dialog" id="chatbotDialog">
        <div class="chatbot-header">
            Chat with Us
        </div>
        <div class="chatbot-body" id="chatbotBody">
            <!-- Chat content goes here -->
        </div>
        <div class="chatbot-footer">
            <input type="text" id="chatInput" placeholder="Type a message...">
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.getElementById('chatbotFab').addEventListener('click', function() {
            const dialog = document.getElementById('chatbotDialog');
            if (dialog.style.display === 'none' || dialog.style.display === '') {
                dialog.style.display = 'flex';
            } else {
                dialog.style.display = 'none';
            }
        });

        document.getElementById('chatInput').addEventListener('keypress', function(event) {
            if (event.key === 'Enter') {
                const input = event.target.value.trim();
                if (input) {
                    const message = document.createElement('div');
                    message.textContent = input;
                    message.style.marginBottom = '10px';
                    document.getElementById('chatbotBody').appendChild(message);
                    
                    $.ajax({
                        url: 'chatbot.php',
                        method: 'POST',
                        data: { message: input },
                        success: function(response) {
                            const botResponse = document.createElement('div');
                            botResponse.textContent = response;
                            botResponse.style.marginBottom = '10px';
                            botResponse.style.color = 'green'; // Bot response color
                            document.getElementById('chatbotBody').appendChild(botResponse);
                        }
                    });

                    event.target.value = '';
                }
            }
        });
    </script>
</body>
</html>
