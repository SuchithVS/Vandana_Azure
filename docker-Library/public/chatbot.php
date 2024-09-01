<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = strtolower(trim($_POST['message']));

    // Default response
    $response = "I'm sorry, I didn't understand that. Can you please rephrase?";

    // Greeting responses
    if (in_array($message, ['hi', 'hello', 'hey', 'how are you', 'good morning', 'good evening'])) {
        $greetings = [
            "Hello! How can I assist you today?",
            "Hi there! What can I help you with?",
            "Hey! How can I be of service?",
            "Good day! What would you like to know?",
            "Hi! Need help finding a book?"
        ];
        $response = $greetings[array_rand($greetings)];
    }
    // Handling 'yes' and 'no' responses
    elseif (in_array($message, ['yes', 'yep', 'yeah'])) {
        $response = "Great! How can I assist you? You can ask me to suggest a book, tell you about available genres, or find a book by title or author.";
    } elseif (in_array($message, ['no', 'nope', 'nah'])) {
        $response = "Alright, feel free to ask anything whenever you need help!";
    }
    // Book suggestion
    elseif (strpos($message, 'suggest') !== false && strpos($message, 'book') !== false) {
        $sql = "SELECT title, author, genre, status FROM books ORDER BY RAND() LIMIT 1";
        $result = mysqli_query($link, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $book = mysqli_fetch_assoc($result);
            $response = "I suggest you check out '{$book['title']}' by {$book['author']}. It's a {$book['genre']} book and is currently {$book['status']}.";
        } else {
            $response = "Sorry, I couldn't find any books to suggest at the moment.";
        }
    }
    // Fetch genres
    elseif (strpos($message, 'genres') !== false) {
        $sql = "SELECT DISTINCT genre FROM books";
        $result = mysqli_query($link, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $genres = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $genres[] = $row['genre'];
            }
            $response = "Here are the available genres: " . implode(', ', $genres) . ".";
        } else {
            $response = "Sorry, I couldn't find any genres.";
        }
    }
    // Fetch book statuses
    elseif (strpos($message, 'status') !== false) {
        $sql = "SELECT DISTINCT status FROM books";
        $result = mysqli_query($link, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $statuses = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $statuses[] = ucfirst($row['status']);
            }
            $response = "Books are currently in the following statuses: " . implode(', ', $statuses) . ".";
        } else {
            $response = "Sorry, I couldn't find any statuses.";
        }
    }
    // Fetch book titles based on keyword
    elseif (strpos($message, 'title') !== false) {
        $keyword = mysqli_real_escape_string($link, $message);
        $sql = "SELECT title FROM books WHERE title LIKE '%$keyword%'";
        $result = mysqli_query($link, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $titles = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $titles[] = $row['title'];
            }
            $response = "Here are some book titles that might interest you: " . implode(', ', $titles) . ".";
        } else {
            $response = "Sorry, I couldn't find any titles matching your request.";
        }
    }
    // Fetch book authors based on keyword
    elseif (strpos($message, 'author') !== false) {
        $keyword = mysqli_real_escape_string($link, $message);
        $sql = "SELECT author FROM books WHERE author LIKE '%$keyword%'";
        $result = mysqli_query($link, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $authors = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $authors[] = $row['author'];
            }
            $response = "Here are some authors that might interest you: " . implode(', ', $authors) . ".";
        } else {
            $response = "Sorry, I couldn't find any authors matching your request.";
        }
    }

    echo $response;
}
?>
